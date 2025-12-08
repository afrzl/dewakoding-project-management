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
            
            // Redirect ke subdomain tenant
            $firstTenant = $user->teams()->first();
            $url = $this->getTenantUrl($firstTenant->slug);
            redirect($url);
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
        
        // Jika sudah punya team, redirect ke subdomain tenant
        $firstTenant = $user?->teams()->first();
        if ($firstTenant) {
            $url = $this->getTenantUrl($firstTenant->slug);
            return new class($url) implements LoginResponse {
                public function __construct(private string $url) {}
                public function toResponse($request)
                {
                    return redirect($this->url);
                }
            };
        }
        
        return $response;
    }

    protected function getTenantUrl(string $slug): string
    {
        $appUrl = config('app.url');
        $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?? 'https';
        $host = parse_url($appUrl, PHP_URL_HOST) ?? $appUrl;
        
        return "{$scheme}://{$slug}.{$host}";
    }
}
