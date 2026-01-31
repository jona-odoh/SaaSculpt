<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', 
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    \App\Http\Middleware\SuperAdminMiddleware::class,
])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.alias');

    Route::get('/tenants/export', [App\Http\Controllers\Admin\TenantController::class, 'export'])->name('admin.tenants.export');
    Route::resource('tenants', App\Http\Controllers\Admin\TenantController::class)->names('admin.tenants');

    Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class)->names('admin.plans');
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'ban'])->name('admin.users.ban');
    Route::post('/users/{user}/impersonate', [\App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('admin.users.impersonate');
});
