<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app(\App\Services\CurrentTenant::class)->get();

        if (! $tenant) {
            // Try to resolve if not already resolved (fallback, though TenantResolver should have run)
            // For now, stricly enforce that TenantResolver must have found it.
            abort(404, 'Tenant not found.');
        }

        // Bind to request for convenience
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
