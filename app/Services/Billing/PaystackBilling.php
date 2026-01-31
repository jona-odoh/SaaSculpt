<?php

namespace App\Services\Billing;

use App\Models\Tenant;
use App\Models\Plan;
use Unicodeveloper\Paystack\Facades\Paystack;
use Exception;

class PaystackBilling implements BillingGateway
{
    public function createCustomer(Tenant $tenant, array $options = [])
    {
        // Paystack handles customers automatically via email during transaction, 
        // but we can pre-create if needed.
        return null; 
    }

    public function subscribe(Tenant $tenant, Plan $plan, array $options = [])
    {
        // For Paystack, we typically initialize a transaction then redirect.
        // This method might need to return a redirect URL or transaction reference.
        
        // This is a simplified abstraction.
        // Real implementation requires handling the callback.
        
        return [
            'reference' => Paystack::genTranxRef(),
            'amount' => $plan->price,
            'email' => $tenant->owner->email,
            'plan' => $plan->paystack_plan_code,
            'metadata' => [
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ],
        ];
    }

    public function swap(Tenant $tenant, Plan $newPlan)
    {
        // Paystack subscription modification needed
    }

    public function cancel(Tenant $tenant)
    {
        // Paystack API disable subscription
    }

    public function resume(Tenant $tenant)
    {
        // Paystack API enable subscription
    }

    public function invoice(Tenant $tenant)
    {
        // Paystack Invoices
    }
}
