<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Illuminate\Support\Facades\Auth;

class Register extends BaseRegister
{
    protected function getRedirectUrl(): string
    {
        $user = Auth::user();
        
        // If user doesn't have any teams, redirect to tenant registration
        if ($user && $user->teams()->count() === 0) {
            return filament()->getTenantRegistrationUrl();
        }
        
        return parent::getRedirectUrl();
    }
}
