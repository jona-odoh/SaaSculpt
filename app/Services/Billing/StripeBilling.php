<?php

namespace App\Services\Billing;

use App\Models\Tenant;
use App\Models\Plan;
use Exception;

class StripeBilling implements BillingGateway
{
    public function createCustomer(Tenant $tenant, array $options = [])
    {
        if (!$tenant->stripe_id) {
            $tenant->createAsStripeCustomer($options);
        }
        return $tenant->stripe_id;
    }

    public function subscribe(Tenant $tenant, Plan $plan, array $options = [])
    {
        $paymentMethod = $options['payment_method'] ?? null;
        
        if (!$paymentMethod) {
            throw new Exception('Payment method required for Stripe.');
        }

        return $tenant->newSubscription('default', $plan->stripe_price_id)
            ->create($paymentMethod, [
                'email' => $tenant->owner->email,
            ]);
    }

    public function swap(Tenant $tenant, Plan $newPlan)
    {
        $subscription = $tenant->subscription('default');
        if ($subscription) {
            return $subscription->swap($newPlan->stripe_price_id);
        }
        throw new Exception('No active subscription to swap.');
    }

    public function cancel(Tenant $tenant)
    {
        $tenant->subscription('default')->cancel();
    }

    public function resume(Tenant $tenant)
    {
        $tenant->subscription('default')->resume();
    }

    public function invoice(Tenant $tenant)
    {
       return $tenant->invoices();
    }
}
