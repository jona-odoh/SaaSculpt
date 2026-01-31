<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::domain(config('app.url_base', 'saasculpt.test'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        // User profile specific routes or central dashboard if needed
        Route::get('/home', function () {
             /** @var \App\Models\User $user */
             $user = Auth::user();
             // Redirect to user's first team or a specific tenant
             return redirect()->route('tenant.dashboard', ['subdomain' => $user->allTeams()->first()->slug ?? 'www']);
        })->name('home');

        Route::get('/dashboard', function () {
             /** @var \App\Models\User $user */
             $user = Auth::user();
             // Redirect to user's first team or a specific tenant
             return redirect()->route('tenant.dashboard', ['subdomain' => $user->allTeams()->first()->slug ?? 'www']);
        })->name('dashboard');
    });
});
