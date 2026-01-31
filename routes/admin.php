<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', 
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    \App\Http\Middleware\SuperAdminMiddleware::class,
])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard'); // Ensure this view exists or return text
    })->name('admin.dashboard');

    Route::get('/tenants', function () {
        return \App\Models\Tenant::all();
    })->name('admin.tenants');
});
