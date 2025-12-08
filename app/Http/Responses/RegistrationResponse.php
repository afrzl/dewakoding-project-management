<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as Responsable;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class RegistrationResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = Filament::auth()->user();
        
        // Jika user belum punya team, redirect ke tenant registration
        if ($user && $user->teams()->count() === 0) {
            return redirect()->to(Filament::getTenantRegistrationUrl());
        }
        
        // Jika sudah punya team, redirect ke dashboard team pertama
        $firstTenant = $user?->teams()->first();
        if ($firstTenant) {
            return redirect()->to(Filament::getUrl($firstTenant));
        }
        
        return redirect()->intended(Filament::getUrl());
    }
}
