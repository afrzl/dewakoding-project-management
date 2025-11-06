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

        // Handle global roles separately
        // if (isset($data['global_roles']) && !empty($data['global_roles'])) {
        //     foreach ($data['global_roles'] as $roleId) {
        //         DB::table('model_has_roles')->insert([
        //             'role_id' => $roleId,
        //             'model_type' => get_class($record),
        //             'model_id' => $record->id,
        //             'team_id' => null,
        //         ]);
        //     }
        // }

        return $record;
    }
}
