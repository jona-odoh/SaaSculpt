<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Define domain routing logic
            Route::domain('admin.saasculpt.test')
                ->middleware('web')
                ->group(base_path('routes/admin.php'));

            Route::pattern('subdomain', '[a-z0-9-]+');
            
            Route::domain('{subdomain}.saasculpt.test')
                ->middleware('web')
                ->group(base_path('routes/tenant.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\CheckIfBanned::class,
            \App\Http\Middleware\TenantResolver::class,
        ]);
        // $middleware->append(\App\Http\Middleware\TenantResolver::class); // Moving to web group explicitly or keeping global if needed, but CheckIfBanned needs session so mostly web
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
