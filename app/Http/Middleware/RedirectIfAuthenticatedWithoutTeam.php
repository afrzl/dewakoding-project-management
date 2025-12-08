<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedWithoutTeam
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Jika user sudah login tapi belum punya team, redirect ke tenant registration
            if ($user->teams()->count() === 0) {
                return redirect('/new');
            }
            
            // Jika user sudah punya team, redirect ke dashboard team pertama
            $firstTenant = $user->teams()->first();
            return redirect("/{$firstTenant->slug}");
        }
        
        return $next($request);
    }
}
