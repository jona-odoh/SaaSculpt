<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/subscription', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription', [App\Http\Controllers\SubscriptionController::class, 'store'])->name('subscription.store');
    Route::delete('/subscription', [App\Http\Controllers\SubscriptionController::class, 'destroy'])->name('subscription.destroy');
});
