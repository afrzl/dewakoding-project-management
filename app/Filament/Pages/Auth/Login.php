<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Facades\Filament;

class Login extends BaseLogin
{
    public function mount(): void
    {
        // Override mount untuk custom redirect jika sudah login
        if (Filament::auth()->check()) {
            $user = Filament::auth()->user();
            
            if ($user->teams()->count() === 0) {
                redirect('/new');
                return;
            }
            
            // Redirect ke tenant path
            $firstTenant = $user->teams()->first();
            redirect("/{$firstTenant->slug}");
            return;
        }

        $this->form->fill();

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn(): string => view('filament.components.google-login-button')->render(),
        );
    }

    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();
        
        if ($response === null) {
            return null;
        }
        
        $user = Filament::auth()->user();
        
        // Jika user belum punya team, redirect ke tenant registration
        if ($user && $user->teams()->count() === 0) {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    return redirect('/new');
                }
            };
        }
        
        // Jika sudah punya team, redirect ke tenant path
        $firstTenant = $user?->teams()->first();
        if ($firstTenant) {
            $slug = $firstTenant->slug;
            return new class($slug) implements LoginResponse {
                public function __construct(private string $slug) {}
                public function toResponse($request)
                {
                    return redirect("/{$this->slug}");
                }
            };
        }
        
        return $response;
    }
}
