<?php

namespace App\Providers;

use App\Models\Role;
use Livewire\Livewire;
use App\Models\Permission;
use Illuminate\Support\Str;
use Filament\Widgets\Widget;
use Filament\Resources\Resource;
use Filament\Pages\BasePage as Page;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use App\Filament\Resources\TicketResource\Pages\EditCommentModal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);

        Livewire::component('edit-comment-modal', EditCommentModal::class);
        FilamentShield::buildPermissionKeyUsing(
            function (string $entity, string $affix, string $subject, string $case, string $separator) {
                return match (true) {
                    # if `configurePermissionIdentifierUsing()` was used previously, then this needs to be adjusted accordingly
                    is_subclass_of($entity, Resource::class) => Str::of($affix)
                        ->snake()
                        ->append('_')
                        ->append(
                            Str::of($entity)
                                ->afterLast('\\')
                                ->beforeLast('Resource')
                                ->replace('\\', '')
                                ->snake()
                                ->replace('_', '::')
                        )
                        ->toString(),
                    is_subclass_of($entity, Page::class) => Str::of('page_')
                        ->append(class_basename($entity))
                        ->toString(),
                    is_subclass_of($entity, Widget::class) => Str::of('widget_')
                        ->append(class_basename($entity))
                        ->toString()
                };
            }
        );

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch->simple();
        });
    }
}
