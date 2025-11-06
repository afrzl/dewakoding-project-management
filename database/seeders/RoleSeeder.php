<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Get default team
        $defaultTeam = Team::find(1);

        // Daftar resource Filament
        $resources = [
            'project',
            'ticket',
            'ticket_priority',
            'ticket_comment',
            'notification',
            'user',
        ];

        $actions = ['view', 'view_any', 'create', 'update', 'delete'];

        // Buat permission granular untuk setiap resource
        $permissions = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissionName = $action . '_' . $resource;
                $permissions[] = $permissionName;
                
                // Create permission with team_id
                Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web'],
                    ['team_id' => $defaultTeam->id]
                );
            }
        }

        // Buat role super_admin, admin, member dengan team_id
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['team_id' => $defaultTeam->id]
        );
        
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['team_id' => $defaultTeam->id]
        );
        
        $member = Role::firstOrCreate(
            ['name' => 'member', 'guard_name' => 'web'],
            ['team_id' => $defaultTeam->id]
        );

        // super_admin: semua permission
        $allPermissions = Permission::where('team_id', $defaultTeam->id)->get();
        $superAdmin->syncPermissions($allPermissions);

        // admin: semua permission kecuali user delete
        $adminPermissions = Permission::where('team_id', $defaultTeam->id)
            ->whereNotIn('name', ['delete_user'])
            ->get();
        $admin->syncPermissions($adminPermissions);

        // member: hanya view/view_any project, ticket, ticket_priority, ticket_comment, notification, dan update ticket (untuk drag & drop)
        $memberPermissions = Permission::where('team_id', $defaultTeam->id)
            ->where(function ($q) {
                $q->whereIn('name', [
                    'view_project',
                    'view_any_project',
                    'view_ticket',
                    'view_any_ticket',
                    'update_ticket',
                    'view_ticket_priority',
                    'view_any_ticket_priority',
                    'view_ticket_comment',
                    'view_any_ticket_comment',
                    'view_notification',
                    'view_any_notification',
                ]);
            })->get();
        $member->syncPermissions($memberPermissions);

        // Get default team
        $defaultTeam = Team::find(1);

        // Buat user untuk masing-masing role
        // Super Admin User
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdminUser->assignRole($superAdmin);
        if ($defaultTeam && !$superAdminUser->teams->contains($defaultTeam->id)) {
            $superAdminUser->teams()->attach($defaultTeam->id);
        }

        // Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->assignRole($admin);
        if ($defaultTeam && !$adminUser->teams->contains($defaultTeam->id)) {
            $adminUser->teams()->attach($defaultTeam->id);
        }

        // Member User
        $memberUser = User::firstOrCreate(
            ['email' => 'member@mail.com'],
            [
                'name' => 'Member User',
                'password' => bcrypt('password'),
            ]
        );
        $memberUser->assignRole($member);
        if ($defaultTeam && !$memberUser->teams->contains($defaultTeam->id)) {
            $memberUser->teams()->attach($defaultTeam->id);
        }
    }
}
