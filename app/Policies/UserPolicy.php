<?php

namespace App\Policies;

use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(AuthUser $authUser, string $ability): bool|null
    {
        // Jika user punya role super_admin dengan team_id null
        if ($authUser->isSuperAdmin()) {
            return true;
        }

        $tenant = Filament::getTenant();
        setPermissionsTeamId($tenant?->id);

        return null;
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_user');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('view_user');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_user');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('update_user');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('delete_user');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('restore_user');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_user');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_user');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_user');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('replicate_user');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_user');
    }

}
