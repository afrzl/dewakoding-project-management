<?php

namespace App\Filament\Superadmin\Resources\TeamResource\Pages;

use App\Filament\Superadmin\Resources\TeamResource;
use App\Models\Team;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Generate invite code dari suffix
        if (isset($data['suffix'])) {
            $data['invite_code'] = $this->generateInviteCode($data['suffix']);
        }

        return static::getModel()::create($data);
    }

    private function generateInviteCode(string $suffix): string
    {
        do {
            $code = strtoupper($suffix) . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Team::where('invite_code', $code)->exists());

        return $code;
    }
}
