<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    \App\Http\Middleware\IdentifyTenant::class, // Apply tenant check
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
