<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Simple check: Is the user a super admin?
        // In production, check a 'is_super_admin' column or specific email/role.
        
        if (! $request->user() || ! in_array($request->user()->email, config('app.super_admins', []))) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
