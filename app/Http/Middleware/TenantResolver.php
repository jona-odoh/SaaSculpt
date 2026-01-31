<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\CurrentTenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantResolver
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        
        // Skip for central domains or IP addresses
        if ($subdomain === 'www' || $subdomain === 'admin' || filter_var($host, FILTER_VALIDATE_IP)) {
            return $next($request);
        }

        // Find tenant
        $tenant = Tenant::where('slug', $subdomain)->first();

        if ($tenant) {
            // Bind to container
            app(CurrentTenant::class)->set($tenant);
            
            // Optional: Bind to Jetstream/request if needed
            // $request->merge(['tenant' => $tenant]);
        }

        return $next($request);
    }
}
