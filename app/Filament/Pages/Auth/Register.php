<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        $user = parent::handleRegistration($data);

        // Auto-assign default role jika ada
        // Note: Role akan di-assign per team saat user join team

        return $user;
    }

    protected function getRedirectUrl(): string
    {
        $user = Auth::user();

        if (!$user) {
            return parent::getRedirectUrl();
        }

        // Jika user punya team, redirect ke dashboard team pertama
        $firstTenant = $user->teams()->first();
        
        if ($firstTenant) {
            return route('filament.admin.pages.dashboard', ['tenant' => $firstTenant->slug]);
        }

        // Jika user belum punya team, redirect ke tenant registration
        return filament()->getTenantRegistrationUrl();
    }
}
