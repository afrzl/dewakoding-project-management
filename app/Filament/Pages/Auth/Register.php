<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
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

    public function getRedirectUrl(): ?string
    {
        // User baru pasti belum punya team, redirect ke tenant registration
        return filament()->getTenantRegistrationUrl();
    }
}
