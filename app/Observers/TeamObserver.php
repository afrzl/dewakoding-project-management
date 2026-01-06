<?php

namespace App\Observers;

use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TeamObserver
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        try {
            \Log::info('TeamObserver: Team created', ['team_id' => $team->id, 'team_name' => $team->name]);

            // Setup roles untuk team baru
            $this->setupTeamRoles($team);

            \Log::info('TeamObserver: Roles setup completed', ['team_id' => $team->id]);
        } catch (\Exception $e) {
            \Log::error('TeamObserver: Failed to setup roles', [
                'team_id' => $team->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Jangan throw error agar team tetap terbuat
            // Role bisa di-assign manual nanti
        }
    }

    /**
     * Setup roles dan permissions untuk team baru
     */
    protected function setupTeamRoles(Team $team): void
    {
        \Log::info('TeamObserver: Starting role setup', ['team_id' => $team->id]);

        // Buat role super_admin untuk team ini
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web', 'team_id' => $team->id]
        );
        \Log::info('TeamObserver: super_admin role created', ['role_id' => $superAdmin->id]);

        // Buat role admin untuk team ini
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web', 'team_id' => $team->id]
        );
        \Log::info('TeamObserver: admin role created', ['role_id' => $admin->id]);

        // Buat role member untuk team ini
        $member = Role::firstOrCreate(
            ['name' => 'member', 'guard_name' => 'web', 'team_id' => $team->id]
        );
        \Log::info('TeamObserver: member role created', ['role_id' => $member->id]);

        // Get all permissions count
        $permissionsCount = Permission::count();
        \Log::info('TeamObserver: Total permissions available', ['count' => $permissionsCount]);

        // Super admin mendapat SEMUA permissions
        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions);
        \Log::info('TeamObserver: super_admin permissions synced', ['permissions_count' => $allPermissions->count()]);

        // Admin: semua permission kecuali delete_user
        $adminPermissions = Permission::whereNotIn('name', ['delete_user'])->get();
        $admin->syncPermissions($adminPermissions);
        \Log::info('TeamObserver: admin permissions synced', ['permissions_count' => $adminPermissions->count()]);

        // Member: hanya view permissions dan update ticket
        $memberPermissions = Permission::where(function ($q) {
            $q->whereIn('name', [
                'view_project',
                'view_any_project',
                'view_ticket',
                'view_any_ticket',
                'update_ticket',
                'view_ticket::priority',
                'view_any_ticket::priority',
                'view_ticket::comment',
                'view_any_ticket::comment',
            ]);
        })->get();
        $member->syncPermissions($memberPermissions);

        DB::table('ticket_priorities')->insert([
            [
                'name' => 'Low',
                'color' => '#10B981', // Green
                'created_at' => now(),
                'team_id' => $team->id,
                'updated_at' => now(),
            ],
            [
                'name' => 'Medium',
                'color' => '#F59E0B', // Yellow
                'created_at' => now(),
                'team_id' => $team->id,
                'updated_at' => now(),
            ],
            [
                'name' => 'High',
                'color' => '#EF4444', // Red
                'created_at' => now(),
                'team_id' => $team->id,
                'updated_at' => now(),
            ],
        ]);
        \Log::info('TeamObserver: member permissions synced', ['permissions_count' => $memberPermissions->count()]);
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
