<?php

namespace App\Services;

use App\Contracts\BillingService;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;

class StripeBillingService implements BillingService
{
    /**
     * Create a new subscription for the given billable entity.
     */
    public function createSubscription(Model $billable, Plan $plan, string $paymentMethod): void
    {
        // Assumes $billable uses Cashier Billable trait
        $billable->newSubscription('default', $plan->stripe_price_id)
            ->create($paymentMethod);
            
        // Update local plan reference
        $billable->update(['plan_id' => $plan->id]);
    }

    /**
     * Cancel the subscription for the given billable entity.
     */
    public function cancelSubscription(Model $billable): void
    {
        $billable->subscription('default')->cancel();
    }

    /**
     * Swap the billable entity to a new plan.
     */
    public function swapPlan(Model $billable, Plan $newPlan): void
    {
        $billable->subscription('default')->swap($newPlan->stripe_price_id);
        $billable->update(['plan_id' => $newPlan->id]);
    }
}
