<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::updateOrCreate(['slug' => 'starter'], [
            'name' => 'Starter',
            'slug' => 'starter',
            'stripe_price_id' => 'price_starter_test',
            'paystack_plan_code' => 'PLN_starter_test',
            'price' => 2900,
            'currency' => 'USD',
            'interval' => 'monthly',
            'features' => ['Up to 5 Users', 'Basic Support', '1GB Storage'],
            'is_active' => true,
        ]);

        Plan::updateOrCreate(['slug' => 'pro'], [
            'name' => 'Pro',
            'slug' => 'pro',
            'stripe_price_id' => 'price_pro_test',
            'paystack_plan_code' => 'PLN_pro_test',
            'price' => 7900,
            'currency' => 'USD',
            'interval' => 'monthly',
            'features' => ['Unlimited Users', 'Priority Support', '10GB Storage', 'Advanced Analytics'],
            'is_active' => true,
        ]);
        
        Plan::updateOrCreate(['slug' => 'enterprise'], [
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'stripe_price_id' => 'price_ent_test',
            'paystack_plan_code' => 'PLN_ent_test',
            'price' => 29900,
            'currency' => 'USD',
            'interval' => 'monthly',
            'features' => ['Unlimited Everything', '24/7 Phone Support', 'SSO', 'Custom Integrations'],
            'is_active' => true,
        ]);
    }
}
