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
            'Project',
            'Ticket',
            'TicketPriority',
            'TicketComment',
            'Notification',
            'User',
            'Role',
        ];

        $actions = ['View', 'ViewAny', 'Create', 'Update', 'Delete', 'Restore', 'ForceDelete', 'ForceDeleteAny', 'RestoreAny', 'Replicate', 'Reorder'];

        // Buat permission granular untuk setiap resource PERTAMA
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissionName = $action . ':' . $resource;
                Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web']
                );
            }
        }

        // Buat permissions untuk pages
        $pages = [
            'EpicsOverview',
            'Leaderboard',
            'ProjectBoard',
            'ProjectTimeline',
            'TicketTimeline',
            'UserContributions',
        ];

        foreach ($pages as $page) {
            Permission::firstOrCreate([
                'name' => 'View:' . $page,
                'guard_name' => 'web'
            ]);
        }

        // Buat permissions untuk widgets (jika ada custom widgets yang perlu authorization)
        $widgets = [
            // Tambahkan widget names di sini jika ada yang perlu permissions
            'StatsOverview',
            'TicketsPerProjectChart',
            'UserStatisticsChart',
            'MonthlyTicketTrendChart',
            'ProjectTimeline',
            'RecentActivityTable',
        ];

        foreach ($widgets as $widget) {
            Permission::firstOrCreate([
                'name' => 'View:' . $widget,
                'guard_name' => 'web'
            ]);
        }

        // Buat role super_admin GLOBAL (tanpa team_id) untuk superadmin panel
        $superAdminGlobal = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web', 'team_id' => null]
        );

        // Buat role super_admin dengan team_id untuk default team
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web', 'team_id' => $defaultTeam->id]
        );

        // Super admin mendapat SEMUA permissions yang ada di database
        $allPermissions = Permission::all();
        $superAdminGlobal->syncPermissions($allPermissions);
        $superAdmin->syncPermissions($allPermissions);

        // Buat role admin dan member dengan team_id untuk default team
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['team_id' => $defaultTeam->id]
        );

        $member = Role::firstOrCreate(
            ['name' => 'member', 'guard_name' => 'web'],
            ['team_id' => $defaultTeam->id]
        );

        // admin: semua permission kecuali user delete
        $adminPermissions = Permission::whereNotIn('name', ['delete_user'])
            ->get();
        $admin->syncPermissions($adminPermissions);

        // member: hanya view/view_any project, ticket, ticket_priority, ticket_comment, notification, dan update ticket (untuk drag & drop)
        $memberPermissions = Permission::where(function ($q) {
            $q->whereIn('name', [
                'View:Project',
                'ViewAny:Project',
                'View:Ticket',
                'ViewAny:Ticket',
                'Update:Ticket',
                'View:TicketPriority',
                'ViewAny:TicketPriority',
                'View:TicketComment',
                'ViewAny:TicketComment',
                'View:Notification',
                'ViewAny:Notification',
            ]);
        })->get();
        $member->syncPermissions($memberPermissions);

        // Buat user untuk masing-masing role
        // Super Admin User
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Attach user to team first
        if ($defaultTeam && !$superAdminUser->teams->contains($defaultTeam->id)) {
            $superAdminUser->teams()->attach($defaultTeam->id);
        }

        // Assign super_admin role with team_id in pivot
        DB::table('model_has_roles')->updateOrInsert(
            [
                'model_id' => $superAdminUser->id,
                'model_type' => User::class,
                'role_id' => $superAdmin->id,
            ],
            ['team_id' => $defaultTeam->id]
        );

        // Assign global super_admin role (team_id = null) for superadmin panel access
        DB::table('model_has_roles')->updateOrInsert(
            [
                'model_id' => $superAdminUser->id,
                'model_type' => User::class,
                'role_id' => $superAdminGlobal->id,
            ],
            ['team_id' => null]
        );

        // Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        if ($defaultTeam && !$adminUser->teams->contains($defaultTeam->id)) {
            $adminUser->teams()->attach($defaultTeam->id);
        }

        DB::table('model_has_roles')->updateOrInsert(
            [
                'model_id' => $adminUser->id,
                'model_type' => User::class,
                'role_id' => $admin->id,
            ],
            ['team_id' => $defaultTeam->id]
        );

        // Member User
        $memberUser = User::firstOrCreate(
            ['email' => 'member@mail.com'],
            [
                'name' => 'Member User',
                'password' => bcrypt('password'),
            ]
        );

        if ($defaultTeam && !$memberUser->teams->contains($defaultTeam->id)) {
            $memberUser->teams()->attach($defaultTeam->id);
        }

        DB::table('model_has_roles')->updateOrInsert(
            [
                'model_id' => $memberUser->id,
                'model_type' => User::class,
                'role_id' => $member->id,
            ],
            ['team_id' => $defaultTeam->id]
        );
    }
}
