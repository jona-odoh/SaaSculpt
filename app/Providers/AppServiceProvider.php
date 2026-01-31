<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\CurrentTenant::class);
        
        $this->app->bind(\App\Contracts\BillingService::class, function ($app) {
            if (config('services.payment_gateway') === 'paystack') {
                return new \App\Services\PaystackBillingService();
            }
            return new \App\Services\StripeBillingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
