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

        // If user doesn't have any teams, redirect to tenant registration
        if ($user && $user->teams()->count() === 0) {
            return filament()->getTenantRegistrationUrl();
        }

        return parent::getRedirectUrl();
    }
}
