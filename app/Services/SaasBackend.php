<?php
// ══════════════════════════════════════════════
// database/seeders/PlanSeeder.php
// ══════════════════════════════════════════════
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'                  => 'Básico',
                'slug'                  => 'basico',
                'price'                 => 499.00,
                'interval'              => 'month',
                'max_employees'         => 25,
                'max_reports_per_month' => 3,
                'api_access'            => false,
                'features'              => [
                    'Hasta 25 empleados',
                    'Guías I, II y III',
                    'Evaluaciones ilimitadas',
                    '3 reportes PDF por mes',
                    'Dashboard básico',
                    'Soporte por email',
                ],
            ],
            [
                'name'                  => 'Profesional',
                'slug'                  => 'profesional',
                'price'                 => 1299.00,
                'interval'              => 'month',
                'max_employees'         => 100,
                'max_reports_per_month' => -1,
                'api_access'            => false,
                'features'              => [
                    'Hasta 100 empleados',
                    'Guías I, II y III',
                    'Evaluaciones ilimitadas',
                    'Reportes PDF ilimitados',
                    'Dashboard avanzado',
                    'Resultados por área',
                    'Exportación de datos',
                    'Soporte prioritario',
                ],
            ],
            [
                'name'                  => 'Empresarial',
                'slug'                  => 'empresarial',
                'price'                 => 2999.00,
                'interval'              => 'month',
                'max_employees'         => 500,
                'max_reports_per_month' => -1,
                'api_access'            => true,
                'features'              => [
                    'Hasta 500 empleados',
                    'Guías I, II y III',
                    'Evaluaciones ilimitadas',
                    'Reportes PDF ilimitados',
                    'Dashboard ejecutivo',
                    'Acceso a API REST',
                    'Integración con HRIS',
                    'Administrador de cuenta dedicado',
                    'SLA 99.9% uptime',
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}


// ══════════════════════════════════════════════
// app/Models/Plan.php
// ══════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'price', 'interval',
        'max_employees', 'max_reports_per_month',
        'api_access', 'features', 'active',
    ];

    protected $casts = [
        'features'   => 'array',
        'api_access' => 'boolean',
        'active'     => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 0) . ' MXN/' . ($this->interval === 'month' ? 'mes' : 'año');
    }
}


// ══════════════════════════════════════════════
// app/Models/Subscription.php
// ══════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'company_id', 'plan_id', 'status',
        'trial_ends_at', 'current_period_start', 'current_period_end',
        'cancelled_at', 'payment_method', 'external_id', 'notes',
    ];

    protected $casts = [
        'trial_ends_at'         => 'datetime',
        'current_period_start'  => 'datetime',
        'current_period_end'    => 'datetime',
        'cancelled_at'          => 'datetime',
    ];

    public function company()    { return $this->belongsTo(Company::class); }
    public function plan()       { return $this->belongsTo(Plan::class); }

    public function isActive(): bool   { return $this->status === 'active'; }
    public function isOnTrial(): bool  { return $this->status === 'trial' && $this->trial_ends_at?->isFuture(); }
    public function isExpired(): bool  { return $this->current_period_end?->isPast() && $this->status === 'active'; }

    public function daysUntilExpiry(): int
    {
        return max(0, (int) now()->diffInDays($this->current_period_end, false));
    }
}


// ══════════════════════════════════════════════
// app/Http/Middleware/EnsureCompanyIsActive.php
// Middleware multi-tenant: verifica que la empresa
// tenga suscripción activa antes de cada request
// ══════════════════════════════════════════════
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCompanyIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $company = $user->company;

        if (!$company) {
            return response()->json(['message' => 'Empresa no encontrada'], 403);
        }

        if (!$company->active) {
            return response()->json([
                'message' => 'Tu cuenta está suspendida. Contacta al administrador.',
                'code'    => 'COMPANY_SUSPENDED',
            ], 403);
        }

        // Verificar suscripción activa
        $subscription = $company->activeSubscription;

        if (!$subscription) {
            return response()->json([
                'message' => 'No tienes una suscripción activa.',
                'code'    => 'NO_SUBSCRIPTION',
            ], 402);
        }

        if ($subscription->isExpired()) {
            return response()->json([
                'message' => 'Tu suscripción ha expirado. Renuévala para continuar.',
                'code'    => 'SUBSCRIPTION_EXPIRED',
            ], 402);
        }

        // Inyectar company en el request para acceso fácil en controllers
        $request->merge(['_company' => $company]);

        return $next($request);
    }
}


// ══════════════════════════════════════════════
// app/Http/Middleware/CheckPlanLimits.php
// Verifica límites del plan (empleados, reportes)
// Úsalo en rutas específicas: middleware('plan.limits:employees')
// ══════════════════════════════════════════════
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPlanLimits
{
    public function handle(Request $request, Closure $next, string $resource = '')
    {
        $company      = Auth::user()->company;
        $subscription = $company?->activeSubscription;
        $plan         = $subscription?->plan;

        if (!$plan) return $next($request);

        switch ($resource) {
            case 'employees':
                if ($plan->max_employees !== -1) {
                    $count = $company->employees()->count();
                    if ($count >= $plan->max_employees) {
                        return response()->json([
                            'message' => "Tu plan {$plan->name} permite máximo {$plan->max_employees} empleados. Actualiza tu plan para agregar más.",
                            'code'    => 'EMPLOYEE_LIMIT_REACHED',
                            'limit'   => $plan->max_employees,
                            'current' => $count,
                        ], 402);
                    }
                }
                break;

            case 'reports':
                if ($plan->max_reports_per_month !== -1) {
                    $reportsThisMonth = $company->reportsThisMonth();
                    if ($reportsThisMonth >= $plan->max_reports_per_month) {
                        return response()->json([
                            'message' => "Has alcanzado el límite de {$plan->max_reports_per_month} reportes este mes. Actualiza tu plan.",
                            'code'    => 'REPORT_LIMIT_REACHED',
                            'limit'   => $plan->max_reports_per_month,
                            'current' => $reportsThisMonth,
                        ], 402);
                    }
                }
                break;
        }

        return $next($request);
    }
}


// ══════════════════════════════════════════════
// Registrar middlewares en bootstrap/app.php
// (Laravel 12 — agrega esto en withMiddleware)
// ══════════════════════════════════════════════

/*
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'company.active' => \App\Http\Middleware\EnsureCompanyIsActive::class,
        'plan.limits'    => \App\Http\Middleware\CheckPlanLimits::class,
    ]);
})
*/

// ══════════════════════════════════════════════
// Uso en routes/api.php:
// ══════════════════════════════════════════════
/*
Route::middleware(['auth:sanctum', 'company.active'])->group(function () {
    Route::get('/employees', ...);
    Route::post('/employees', [EmployeeController::class, 'store'])
         ->middleware('plan.limits:employees');
    Route::get('/reports/generate', [ReportController::class, 'generate'])
         ->middleware('plan.limits:reports');
});
*/