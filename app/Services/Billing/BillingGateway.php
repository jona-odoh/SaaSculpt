<?php

namespace App\Services\Billing;

use App\Models\Tenant;
use App\Models\Plan;

interface BillingGateway
{
    public function createCustomer(Tenant $tenant, array $options = []);
    public function subscribe(Tenant $tenant, Plan $plan, array $options = []);
    public function swap(Tenant $tenant, Plan $newPlan);
    public function cancel(Tenant $tenant);
    public function resume(Tenant $tenant);
    public function invoice(Tenant $tenant);
}
