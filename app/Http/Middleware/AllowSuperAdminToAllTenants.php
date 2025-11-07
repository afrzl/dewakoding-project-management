<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowSuperAdminToAllTenants
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Jika user adalah superadmin global, skip tenant ownership check
        if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            // Superadmin bisa akses semua tenant, jadi continue
            return $next($request);
        }
        
        return $next($request);
    }
}
