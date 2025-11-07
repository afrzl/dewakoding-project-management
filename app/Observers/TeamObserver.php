<?php

namespace App\Observers;

use App\Models\Team;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TeamObserver
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        // Setup roles untuk team baru
        $this->setupTeamRoles($team);
    }

    /**
     * Setup roles dan permissions untuk team baru
     */
    protected function setupTeamRoles(Team $team): void
    {
        // Buat role super_admin untuk team ini
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web', 'team_id' => $team->id]
        );

        // Buat role admin untuk team ini
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web', 'team_id' => $team->id]
        );

        // Buat role member untuk team ini
        $member = Role::firstOrCreate(
            ['name' => 'member', 'guard_name' => 'web', 'team_id' => $team->id]
        );

        // Super admin mendapat SEMUA permissions
        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions);

        // Admin: semua permission kecuali delete_user
        $adminPermissions = Permission::whereNotIn('name', ['delete_user'])->get();
        $admin->syncPermissions($adminPermissions);

        // Member: hanya view permissions dan update ticket
        $memberPermissions = Permission::where(function ($q) {
            $q->whereIn('name', [
                'view_project',
                'view_any_project',
                'view_ticket',
                'view_any_ticket',
                'update_ticket',
                'view_ticketPriority',
                'view_any_ticketPriority',
                'view_ticketComment',
                'view_any_ticketComment',
                'view_notification',
                'view_any_notification',
            ]);
        })->get();
        $member->syncPermissions($memberPermissions);
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "restored" event.
     */
    public function restored(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "force deleted" event.
     */
    public function forceDeleted(Team $team): void
    {
        //
    }
}
