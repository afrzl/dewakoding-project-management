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
    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Save basic user data
        $record->update($data);
        
        // Handle global roles separately
        if (isset($data['global_roles'])) {
            // Remove old global roles
            DB::table('model_has_roles')
                ->where('model_id', $record->id)
                ->where('model_type', get_class($record))
                ->whereNull('team_id')
                ->delete();
            
            // Add new global roles
            if (!empty($data['global_roles'])) {
                foreach ($data['global_roles'] as $roleId) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $roleId,
                        'model_type' => get_class($record),
                        'model_id' => $record->id,
                        'team_id' => null,
                    ]);
                }
            }
        }
        
        return $record;
    }
}
