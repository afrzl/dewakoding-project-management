<?php

namespace App\Filament\Superadmin\Resources\UserResource\Pages;

use App\Filament\Superadmin\Resources\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Check if user has global super_admin role
        $superAdminRole = \Spatie\Permission\Models\Role::where('name', 'super_admin')
            ->whereNull('team_id')
            ->first();

        if ($superAdminRole) {
            $data['is_superadmin'] = DB::table('model_has_roles')
                ->where('model_id', $this->record->id)
                ->where('model_type', get_class($this->record))
                ->where('role_id', $superAdminRole->id)
                ->whereNull('team_id')
                ->exists();
        } else {
            $data['is_superadmin'] = false;
        }

        return $data;
    }
    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Save basic user data
        $record->update($data);
        
        // Handle is_superadmin toggle
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'super_admin',
            'team_id' => null,
        ]);

        $hasGlobalSuperAdmin = DB::table('model_has_roles')
            ->where('model_id', $record->id)
            ->where('model_type', get_class($record))
            ->where('role_id', $superAdminRole->id)
            ->whereNull('team_id')
            ->exists();

        if (isset($data['is_superadmin']) && $data['is_superadmin']) {
            // Add global super_admin role if not exists
            if (!$hasGlobalSuperAdmin) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $superAdminRole->id,
                    'model_type' => get_class($record),
                    'model_id' => $record->id,
                    'team_id' => null,
                ]);
            }
        } else {
            // Remove global super_admin role if exists
            if ($hasGlobalSuperAdmin) {
                DB::table('model_has_roles')
                    ->where('model_id', $record->id)
                    ->where('model_type', get_class($record))
                    ->where('role_id', $superAdminRole->id)
                    ->whereNull('team_id')
                    ->delete();
            }
        }
        
        return $record;
    }
}
