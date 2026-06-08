<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyAdminController extends Controller
{
    private const COMPANY_PER_PAGE = 15;
    private const TRIAL_DAYS = 3;

    // ====================== DASHBOARD ======================
    public function dashboard()
    {
        $activeCompanies = Company::whereHas('subscription', fn($q) => 
            $q->where('status', 'active')
        )->count();

        $trialCompanies = Company::whereHas('subscription', fn($q) => 
            $q->where('status', 'trial')
        )->count();

        $suspendedCompanies = Company::whereHas('subscription', fn($q) => 
            $q->where('status', 'suspended')
        )->count();

        $totalEmployees = Company::sum('employee_count');

        $mrr = Subscription::where('status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum(DB::raw('plans.price'));

        $plansDistribution = Plan::withCount(['subscriptions' => fn($q) => $q->where('status', 'active')])
            ->get()
            ->map(function ($plan) use ($mrr) {
                $rev = $plan->price * $plan->subscriptions_count;
                return [
                    'name'  => $plan->name,
                    'color' => $this->getPlanColor($plan->slug),
                    'pct'   => $mrr > 0 ? round(($rev / $mrr) * 100) : 0,
                    'rev'   => $rev,
                    'active'=> $plan->subscriptions_count,
                ];
            });

        $recentActivity = Subscription::with(['company', 'plan'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($sub) => [
                'id'   => $sub->id,
                'text' => "{$sub->company->name} " . $this->getActivityText($sub),
                'time' => $sub->created_at->diffForHumans(),
                'type' => $sub->status === 'active' ? 'new' : ($sub->status === 'suspended' ? 'suspend' : 'renew')
            ]);

        return response()->json([
            'stats' => [
                'active_companies' => $activeCompanies,
                'trial_companies'  => $trialCompanies,
                'suspended'        => $suspendedCompanies,
                'total_employees'  => $totalEmployees,
                'mrr'              => round($mrr),
            ],
            'plans_distribution' => $plansDistribution,
            'recent_activity'    => $recentActivity,
        ]);
    }

    // ====================== EMPRESAS ======================
    public function index(Request $request)
    {
        $companies = Company::with(['subscription.plan'])
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%");
            })
            ->when($request->filter, function ($q, $filter) {
                if ($filter === 'active') {
                    $q->whereHas('subscription', fn($sq) => $sq->where('status', 'active'));
                } elseif ($filter === 'trial') {
                    $q->whereHas('subscription', fn($sq) => $sq->where('status', 'trial'));
                } elseif ($filter === 'suspended') {
                    $q->whereHas('subscription', fn($sq) => $sq->where('status', 'suspended'));
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(self::COMPANY_PER_PAGE);

        $companies->getCollection()->transform(
            fn (Company $company) => $this->serializeCompany($company)
        );

        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'rfc'      => 'nullable|string|unique:companies,rfc',
            'email'    => 'required|email|unique:companies,email',
            'phone'    => 'nullable|string',
            'plan_id'  => 'required|exists:plans,id',
            'status'   => 'required|in:trial,active,suspended',
        ]);

        $company = Company::create([
            'name'  => $validated['name'],
            'rfc'   => $validated['rfc'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Crear suscripción inicial
        Subscription::create([
            'company_id'           => $company->id,
            'plan_id'              => $validated['plan_id'],
            'status'               => $validated['status'],
            'current_period_start' => now(),
            'current_period_end'   => now()->addMonths(1),
            'trial_ends_at'        => $validated['status'] === 'trial' ? now()->addDays(self::TRIAL_DAYS) : null,
        ]);

        return response()->json(
            $this->serializeCompany($company->load('subscription.plan')),
            201
        );
    }

    public function show(Company $company)
    {
        return response()->json(
            $this->serializeCompany($company->load('subscription.plan'))
        );
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'rfc'      => 'nullable|string|unique:companies,rfc,'.$company->id,
            'email'    => 'sometimes|required|email|unique:companies,email,'.$company->id,
            'phone'    => 'nullable|string',
            'address'  => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'active'   => 'nullable|boolean',
            'plan_id'  => 'nullable|exists:plans,id',
            'status'   => 'nullable|in:trial,active,suspended',
        ]);

        $company->update(collect($validated)->except(['plan_id', 'status'])->all());

        if (array_key_exists('plan_id', $validated) || array_key_exists('status', $validated)) {
            $subscription = $company->subscription;

            if (!$subscription) {
                $subscription = Subscription::create([
                    'company_id'           => $company->id,
                    'plan_id'              => $validated['plan_id'] ?? Plan::query()->value('id'),
                    'status'               => $validated['status'] ?? 'trial',
                    'current_period_start' => now(),
                    'current_period_end'   => now()->addMonth(),
                    'trial_ends_at'        => ($validated['status'] ?? 'trial') === 'trial' ? now()->addDays(self::TRIAL_DAYS) : null,
                ]);
            } else {
                if (array_key_exists('plan_id', $validated)) {
                    $subscription->plan_id = $validated['plan_id'];
                }

                if (array_key_exists('status', $validated)) {
                    $subscription->status = $validated['status'];
                    $subscription->trial_ends_at = $validated['status'] === 'trial'
                        ? ($subscription->trial_ends_at ?? now()->addDays(self::TRIAL_DAYS))
                        : null;
                }

                $subscription->save();
            }
        }

        return response()->json(
            $this->serializeCompany($company->fresh()->load('subscription.plan'))
        );
    }

    public function toggleStatus(Company $company)
    {
        $subscription = $company->subscription;

        if (!$subscription) {
            return response()->json(['message' => 'No tiene suscripción'], 400);
        }

        $subscription->status = $subscription->status === 'active' ? 'suspended' : 'active';
        $subscription->save();

        return response()->json(['message' => 'Estado actualizado', 'status' => $subscription->status]);
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json(['message' => 'Empresa eliminada']);
    }

    // ====================== PLANES ======================
    public function plans()
    {
        $plans = Plan::where('active', true)->get();
        return response()->json($plans);
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:plans,slug',
            'price'                 => 'required|numeric|min:0',
            'interval'              => 'nullable|string|max:50',
            'max_employees'         => 'required|integer|min:1',
            'max_reports_per_month' => 'required|integer|min:0',
            'api_access'            => 'nullable|boolean',
            'features'              => 'nullable|array',
            'active'                => 'nullable|boolean',
        ]);

        $plan = Plan::create([
            'name'                  => $validated['name'],
            'slug'                  => strtolower(trim($validated['slug'])),
            'price'                 => $validated['price'],
            'interval'              => $validated['interval'] ?? 'month',
            'max_employees'         => $validated['max_employees'],
            'max_reports_per_month' => $validated['max_reports_per_month'],
            'api_access'            => $validated['api_access'] ?? false,
            'features'              => $validated['features'] ?? [],
            'active'                => $validated['active'] ?? true,
        ]);

        return response()->json($plan, 201);
    }

    public function updatePlan(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name'                  => 'sometimes|required|string|max:255',
            'slug'                  => 'sometimes|required|string|max:255|unique:plans,slug,'.$plan->id,
            'price'                 => 'sometimes|required|numeric|min:0',
            'interval'              => 'nullable|string|max:50',
            'max_employees'         => 'sometimes|required|integer|min:1',
            'max_reports_per_month' => 'sometimes|required|integer|min:0',
            'api_access'            => 'nullable|boolean',
            'features'              => 'nullable|array',
            'active'                => 'nullable|boolean',
        ]);

        if (array_key_exists('slug', $validated)) {
            $validated['slug'] = strtolower(trim($validated['slug']));
        }

        $plan->update($validated);

        return response()->json($plan->fresh());
    }

    // Helpers
    private function serializeCompany(Company $company): array
    {
        $subscription = $company->subscription;
        $plan = $subscription?->plan;

        return [
            'id' => $company->id,
            'name' => $company->name,
            'rfc' => $company->rfc,
            'email' => $company->email,
            'phone' => $company->phone,
            'address' => $company->address,
            'industry' => $company->industry,
            'active' => $company->active,
            'employee_count' => $company->employee_count,
            'status' => $subscription?->status ?? 'inactive',
            'expires_at' => $subscription?->current_period_end?->format('d/m/Y'),
            'subscription' => $subscription ? [
                'id' => $subscription->id,
                'plan_id' => $subscription->plan_id,
                'status' => $subscription->status,
                'trial_ends_at' => optional($subscription->trial_ends_at)?->toISOString(),
                'current_period_start' => optional($subscription->current_period_start)?->toISOString(),
                'current_period_end' => optional($subscription->current_period_end)?->toISOString(),
                'cancelled_at' => optional($subscription->cancelled_at)?->toISOString(),
                'plan' => $plan ? [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => $plan->price,
                    'max_employees' => $plan->max_employees,
                ] : null,
            ] : null,
        ];
    }

    private function getPlanColor($slug)
    {
        return match($slug) {
            'basico'      => '#6366F1',
            'profesional' => '#0EA5E9',
            'empresarial' => '#10B981',
            default       => '#64748B',
        };
    }

    private function getActivityText($subscription)
    {
        if ($subscription->status === 'active') return "se suscribió al plan {$subscription->plan->name}";
        if ($subscription->status === 'suspended') return "fue suspendida";
        return "actualizó su suscripción";
    }
}
