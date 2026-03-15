<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\Plan;
use App\Services\StripeService;
use Inertia\Inertia;

class BillingController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    // Página principal de billing
    public function index()
    {
        $company      = Auth::user()->company;
        $subscription = $company->activeSubscription;
        $plan         = $subscription?->plan;

        $invoices = [];
        if ($company->stripe_customer_id) {
            $invoices = $this->stripe->getInvoices($company->stripe_customer_id);
        }

        return Inertia::render('Billing', [
            'company'      => $company,
            'subscription' => $subscription,
            'plan'         => $plan,
            'plans'        => Plan::where('active', true)->get(),
            'invoices'     => $invoices,
        ]);
    }

    // Crear sesión de checkout → redirige a Stripe
    public function checkout(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:plans,id']);

        $user    = Auth::user();
        $company = $user->company;
        $plan    = Plan::findOrFail($request->plan_id);

        // Crear customer en Stripe si no existe
        $customerId = $company->stripe_customer_id
            ?? $this->stripe->createCustomer($company, $user);

        if (!$company->stripe_customer_id) {
            $company->update(['stripe_customer_id' => $customerId]);
        }

        $session = $this->stripe->createCheckoutSession(
            customerId: $customerId,
            priceId:    config("stripe.prices.{$plan->slug}"),
            successUrl: route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
            cancelUrl:  route('billing'),
            metadata:   ['company_id' => $company->id, 'plan_id' => $plan->id],
        );

        return response()->json(['checkout_url' => $session->url]);
    }

    // Stripe redirige aquí después del pago exitoso
    public function success(Request $request)
    {
        $session = $this->stripe->retrieveSession($request->session_id);

        if ($session->payment_status === 'paid') {
            $company = Auth::user()->company;
            $planId  = $session->metadata->plan_id;

            Subscription::updateOrCreate(
                ['company_id' => $company->id],
                [
                    'plan_id'                => $planId,
                    'status'                 => 'active',
                    'stripe_subscription_id' => $session->subscription,
                    'current_period_start'   => now(),
                    'current_period_end'     => now()->addMonth(),
                    'trial_ends_at'          => null,
                ]
            );
        }

        return redirect()->route('dashboard')
            ->with('success', '¡Suscripción activada correctamente!');
    }

    // Abre el portal de Stripe (actualizar tarjeta, ver facturas, cancelar)
    public function portal()
    {
        $company = Auth::user()->company;

        if (!$company->stripe_customer_id) {
            return back()->withErrors(['error' => 'No tienes un método de pago configurado.']);
        }

        $portalSession = $this->stripe->createPortalSession(
            customerId: $company->stripe_customer_id,
            returnUrl:  route('billing'),
        );

        return redirect($portalSession->url);
    }

    // Cambiar de plan (upgrade o downgrade)
    public function changePlan(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:plans,id']);

        $company      = Auth::user()->company;
        $subscription = $company->activeSubscription;
        $newPlan      = Plan::findOrFail($request->plan_id);

        if (!$subscription?->stripe_subscription_id) {
            return $this->checkout($request);
        }

        $this->stripe->updateSubscriptionPlan(
            stripeSubscriptionId: $subscription->stripe_subscription_id,
            newPriceId: config("stripe.prices.{$newPlan->slug}"),
        );

        $subscription->update(['plan_id' => $newPlan->id]);

        return response()->json([
            'success' => true,
            'message' => "Plan actualizado a {$newPlan->name}",
        ]);
    }

    // Cancelar suscripción (al final del período)
    public function cancel()
    {
        $company      = Auth::user()->company;
        $subscription = $company->activeSubscription;

        if ($subscription?->stripe_subscription_id) {
            $this->stripe->cancelSubscription($subscription->stripe_subscription_id);
        }

        $subscription?->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Suscripción cancelada. Tienes acceso hasta el final del período.',
        ]);
    }
}