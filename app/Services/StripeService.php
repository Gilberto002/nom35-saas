<?php

namespace App\Services;

use Stripe\StripeClient;
use App\Models\Company;
use App\Models\User;

class StripeService
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret'));
    }

    public function createCustomer(Company $company, User $user): string
    {
        $customer = $this->stripe->customers->create([
            'email'    => $user->email,
            'name'     => $company->name,
            'metadata' => ['company_id' => $company->id],
        ]);
        return $customer->id;
    }

    public function createCheckoutSession(
        string $customerId,
        string $priceId,
        string $successUrl,
        string $cancelUrl,
        array  $metadata = [],
    ) {
        return $this->stripe->checkout->sessions->create([
            'customer'              => $customerId,
            'payment_method_types'  => ['card'],
            'line_items'            => [['price' => $priceId, 'quantity' => 1]],
            'mode'                  => 'subscription',
            'success_url'           => $successUrl,
            'cancel_url'            => $cancelUrl,
            'metadata'              => $metadata,
            'subscription_data'     => ['metadata' => $metadata],
            'locale'                => 'es',
            'allow_promotion_codes' => true,
        ]);
    }

    public function retrieveSession(string $sessionId)
    {
        return $this->stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['subscription'],
        ]);
    }

    public function createPortalSession(string $customerId, string $returnUrl)
    {
        return $this->stripe->billingPortal->sessions->create([
            'customer'   => $customerId,
            'return_url' => $returnUrl,
        ]);
    }

    public function updateSubscriptionPlan(string $stripeSubscriptionId, string $newPriceId)
    {
        $subscription = $this->stripe->subscriptions->retrieve($stripeSubscriptionId);

        return $this->stripe->subscriptions->update($stripeSubscriptionId, [
            'items' => [[
                'id'    => $subscription->items->data[0]->id,
                'price' => $newPriceId,
            ]],
            'proration_behavior' => 'create_prorations',
        ]);
    }

    public function cancelSubscription(string $stripeSubscriptionId)
    {
        return $this->stripe->subscriptions->update($stripeSubscriptionId, [
            'cancel_at_period_end' => true,
        ]);
    }

    public function getInvoices(string $customerId): array
    {
        $invoices = $this->stripe->invoices->all([
            'customer' => $customerId,
            'limit'    => 12,
        ]);

        return collect($invoices->data)->map(fn($inv) => [
            'id'      => $inv->id,
            'number'  => $inv->number,
            'amount'  => $inv->amount_paid / 100,
            'status'  => $inv->status,
            'date'    => \Carbon\Carbon::createFromTimestamp($inv->created)->format('d/m/Y'),
            'pdf_url' => $inv->invoice_pdf,
            'period'  => \Carbon\Carbon::createFromTimestamp($inv->period_start)->format('M Y'),
        ])->toArray();
    }
}