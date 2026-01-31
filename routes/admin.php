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

    Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');
});
