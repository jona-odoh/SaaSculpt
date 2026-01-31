<?php

namespace App\Contracts;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;

interface BillingService
{
    /**
     * Create a new subscription for the given billable entity.
     */
    public function createSubscription(Model $billable, Plan $plan, string $paymentMethod): void;

    /**
     * Cancel the subscription for the given billable entity.
     */
    public function cancelSubscription(Model $billable): void;

    /**
     * Swap the billable entity to a new plan.
     */
    public function swapPlan(Model $billable, Plan $newPlan): void;
}
