<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    \App\Http\Middleware\IdentifyTenant::class,
])->group(function () {
    // Public Tenant Routes
    Route::get('/', function () {
        $tenant = app(\App\Services\CurrentTenant::class)->get();
        return view('tenant.welcome', compact('tenant'));
    })->name('tenant.welcome');

    // Protected Tenant Routes
    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('tenant.dashboard');

        Route::controller(\App\Http\Controllers\SubscriptionController::class)->group(function () {
            Route::get('/subscription', 'index')->name('subscription.index');
            Route::post('/subscription', 'store')->name('subscription.store');
            Route::delete('/subscription', 'destroy')->name('subscription.destroy');
        });
    });
});
