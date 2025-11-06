<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Skip if not authenticated
        if (!$user) {
            return $next($request);
        }
        
        // Skip for superadmin panel
        if ($request->is('superadmin*')) {
            return $next($request);
        }
        
        // Skip if already on tenant registration or join page
        if (
            $request->routeIs('filament.*.tenant.registration') ||
            $request->routeIs('join-team') ||
            $request->is('admin/new') ||
            $request->is('admin/register') ||
            $request->is('admin/*/join-team') ||
            $request->is('admin/join-team') ||
            $request->is('join-team')
        ) {
            return $next($request);
        }
        
        // Check if user has at least one team
        if ($user->teams()->count() === 0) {
            // Redirect to tenant registration page
            return redirect()->to(filament()->getTenantRegistrationUrl());
        }
        
        return $next($request);
    }
}
