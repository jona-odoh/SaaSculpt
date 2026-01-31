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
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        // Improve: Check if it's a central domain (e.g. www or root)
        // For now, assume central domain logic is handled by route grouping or another middleware.
        // If subdomain is 'www' or matches APP_URL host, we might skip or abort.
        
        $tenant = Tenant::where('slug', $subdomain)->first();

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        // Bind logic
        // We can put $tenant in container or set on request
        $request->attributes->set('tenant', $tenant);
        
        // Also bind to a service or singleton
        app()->instance('currentTenant', $tenant);
        
        // Set Global Scope?
        // TenantScope::setTenant($tenant); 
        // Or configure db connection if using multi-db (not this project).

        return $next($request);
    }
}
