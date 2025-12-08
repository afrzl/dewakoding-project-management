<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Tenancy\RegisterTeam;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
use App\Models\Setting;
use App\Http\Middleware\FilamentUserSettings;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->spa()
            ->databaseTransactions()
            ->default()
            ->id('admin')
            ->path('/')
            ->login(Login::class)
            ->registration(Register::class)
            ->tenant(\App\Models\Team::class, ownershipRelationship: 'teams', slugAttribute: 'slug')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->topNavigation(fn() => $this->shouldUseTopNavigation())
            ->sidebarCollapsibleOnDesktop(fn() => !$this->shouldUseTopNavigation())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                FilamentUserSettings::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->scopeToTenant(true)
                    ->tenantRelationshipName('teams'),
            ])
            ->tenantRegistration(RegisterTeam::class)
            ->tenantMenuItems([
                'register' => \Filament\Navigation\MenuItem::make()
                    ->label('Join another team')
                    ->icon('heroicon-o-user-plus')
                    ->url(fn() => route('filament.admin.tenant.join')),
            ])
            ->routes(function () {
                \Illuminate\Support\Facades\Route::get('/join', \App\Filament\Pages\Tenancy\JoinTeam::class)
                    ->name('tenant.join');
            })
            ->tenantMiddleware([
                SyncShieldTenant::class,
            ], isPersistent: true)
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->passwordReset()
            ->profile()
            ->viteTheme('resources/css/filament/admin/theme.css');

        return $panel;
    }

    protected function shouldUseTopNavigation(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        try {
            $navigationStyle = Setting::getUserValue('filament_navigation_style', 'sidebar', auth()->id());
            return $navigationStyle === 'top';
        } catch (\Exception $e) {
            return false;
        }
    }
}
