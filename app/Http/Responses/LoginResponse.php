<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as Responsable;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = Filament::auth()->user();
        
        \Log::info('=== CUSTOM LoginResponse CALLED ===', [
            'user_id' => $user?->id,
            'teams_count' => $user?->teams()->count(),
        ]);
        
        // Jika user belum punya team, redirect ke tenant registration
        if ($user && $user->teams()->count() === 0) {
            \Log::info('Redirecting to /new');
            return redirect()->to('/new');
        }
        
        // Jika sudah punya team, redirect ke dashboard team pertama
        $firstTenant = $user?->teams()->first();
        if ($firstTenant) {
            \Log::info('Redirecting to first tenant', ['tenant' => $firstTenant->slug]);
            return redirect()->to(Filament::getUrl($firstTenant));
        }
        
        return redirect()->to(Filament::getUrl());
    }
}
