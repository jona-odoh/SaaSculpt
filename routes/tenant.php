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
    
    // Add other tenant-specific routes here
});
