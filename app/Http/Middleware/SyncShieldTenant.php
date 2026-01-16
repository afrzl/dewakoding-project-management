<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SyncShieldTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Filament::hasTenancy() && $tenant = Filament::getTenant()) {
            setPermissionsTeamId($tenant->getKey()); // ✅ Pass ID

            // Clear permissions cache when tenant context changes
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return $next($request);
    }
}
