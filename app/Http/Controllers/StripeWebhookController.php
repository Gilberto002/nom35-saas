<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Company;
use App\Models\Subscription;
use App\Jobs\SendPaymentFailedEmail;
use App\Jobs\SendSubscriptionRenewedEmail;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('stripe.webhook_secret');

        // Verificar que el evento viene realmente de Stripe
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook firma inválida', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info("Stripe webhook: {$event->type}");

        match ($event->type) {
            'invoice.payment_succeeded'            => $this->handlePaymentSucceeded($event->data->object),
            'invoice.payment_failed'               => $this->handlePaymentFailed($event->data->object),
            'customer.subscription.updated'        => $this->handleSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted'        => $this->handleSubscriptionDeleted($event->data->object),
            'customer.subscription.trial_will_end' => $this->handleTrialWillEnd($event->data->object),
            default                                => null,
        };

        return response()->json(['received' => true]);
    }

    // Pago exitoso → renovar período
    private function handlePaymentSucceeded($invoice): void
    {
        if (!$invoice->subscription) return;

        $company = Company::where('stripe_customer_id', $invoice->customer)->first();
        if (!$company) return;

        $company->activeSubscription?->update([
            'status'               => 'active',
            'current_period_start' => now(),
            'current_period_end'   => now()->addMonth(),
        ]);

        SendSubscriptionRenewedEmail::dispatch($company, $invoice->amount_paid / 100);

        Log::info("Renovación exitosa empresa ID: {$company->id}");
    }

    // Pago fallido → marcar past_due + email
    private function handlePaymentFailed($invoice): void
    {
        $company = Company::where('stripe_customer_id', $invoice->customer)->first();
        if (!$company) return;

        $company->activeSubscription?->update(['status' => 'past_due']);

        SendPaymentFailedEmail::dispatch(
            company:       $company,
            amount:        $invoice->amount_due / 100,
            nextRetryAt:   $invoice->next_payment_attempt,
            updateCardUrl: route('billing.portal'),
        );

        Log::warning("Pago fallido empresa ID: {$company->id}");
    }

    // Suscripción actualizada (upgrade/downgrade/pausa)
    private function handleSubscriptionUpdated($stripeSub): void
    {
        $company = Company::where('stripe_customer_id', $stripeSub->customer)->first();
        if (!$company) return;

        $status = match($stripeSub->status) {
            'active'   => 'active',
            'trialing' => 'trial',
            'past_due' => 'past_due',
            'canceled' => 'cancelled',
            default    => 'active',
        };

        $company->activeSubscription?->update([
            'status'               => $status,
            'current_period_end'   => \Carbon\Carbon::createFromTimestamp($stripeSub->current_period_end),
            'current_period_start' => \Carbon\Carbon::createFromTimestamp($stripeSub->current_period_start),
        ]);
    }

    // Suscripción cancelada → suspender acceso
    private function handleSubscriptionDeleted($stripeSub): void
    {
        $company = Company::where('stripe_customer_id', $stripeSub->customer)->first();
        if (!$company) return;

        $company->activeSubscription?->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        $company->update(['active' => false]);

        Log::info("Suscripción cancelada empresa ID: {$company->id}");
    }

    // Trial por vencer (3 días antes)
    private function handleTrialWillEnd($stripeSub): void
    {
        $company = Company::where('stripe_customer_id', $stripeSub->customer)->first();
        if (!$company) return;

        // Aquí puedes disparar un email de "tu prueba vence pronto"
        Log::info("Trial por vencer empresa ID: {$company->id}");
    }
}