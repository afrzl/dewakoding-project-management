<?php

namespace App\Filament\Superadmin\Resources\UserResource\Pages;

use App\Filament\Superadmin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Create user
        $record = static::getModel()::create($data);

        // Handle is_superadmin toggle
        if (isset($data['is_superadmin']) && $data['is_superadmin']) {
            // Get or create global super_admin role
            $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => 'super_admin',
                'team_id' => null,
            ]);

            // Assign global super_admin role
            DB::table('model_has_roles')->updateOrInsert([
                'role_id' => $superAdminRole->id,
                'model_type' => get_class($record),
                'model_id' => $record->id,
                'team_id' => null,
            ], []);
        }

        return $record;
    }
}
