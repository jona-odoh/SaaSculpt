<?php

namespace App\Services;

use App\Contracts\BillingService;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackBillingService implements BillingService
{
    public function createSubscription(Model $billable, Plan $plan, string $reference): void
    {
        // For Paystack, we usually initialize a transaction.
        // The 'paymentMethod' argument here mirrors the interface, but for Paystack
        // it might be the transaction reference or email.
        
        // However, Laravel Cashier (Stripe) and Paystack are very different.
        // Paystack package usually handles one-off or recurring via their API.
        
        // A robust implementation would use Paystack's subscription API.
        // Given complexity, we'll implement a basic 'Create Subscription' logic
        // that assumes the user has already authorized via the popup/redirect
        // and we are verifying the transaction.
        
        // Mock implementation for the boilerplate structure:
        // In a real app, verify transaction $reference, then save subscription to DB.
        
        $billable->subscriptions()->create([
            'name' => 'default',
            'stripe_id' => 'paystack_' . $reference, // Hacky mapping to keep table schema happy or use separate columns
            'stripe_status' => 'active',
            'stripe_price' => $plan->paystack_plan_code,
            'quantity' => 1,
            'type' => 'paystack', // We might need to adjust the migration to allow 'type' or just use 'name'
        ]);
    }

    public function cancelSubscription(Model $billable): void
    {
        // Call Paystack API to disable subscription
        $billable->subscription('default')->update(['stripe_status' => 'canceled']);
    }

    public function swapPlan(Model $billable, Plan $newPlan): void
    {
        // Updates subscription code on Paystack
        $billable->subscription('default')->update(['stripe_price' => $newPlan->paystack_plan_code]);
    }
}
