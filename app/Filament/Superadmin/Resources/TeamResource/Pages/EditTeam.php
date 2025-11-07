<?php

namespace App\Filament\Superadmin\Resources\TeamResource\Pages;

use App\Filament\Superadmin\Resources\TeamResource;
use App\Models\Team;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load invite_code untuk ditampilkan
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Jika suffix berubah, regenerate invite code
        if (isset($data['suffix']) && $data['suffix'] !== $record->suffix) {
            $data['invite_code'] = $this->generateInviteCode($data['suffix']);
        }

        $record->update($data);

        return $record;
    }

    private function generateInviteCode(string $suffix): string
    {
        do {
            $code = strtoupper($suffix) . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Team::where('invite_code', $code)->exists());

        return $code;
    }
}
