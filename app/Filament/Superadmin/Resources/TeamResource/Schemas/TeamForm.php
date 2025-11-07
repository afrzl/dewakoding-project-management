<?php

namespace App\Filament\Superadmin\Resources\TeamResource\Schemas;

use App\Models\Team;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->alphaDash(),
                TextInput::make('invite_code')
                    ->label('Invite Code')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpan(1)
                    ->visible(fn($record) => $record !== null)
                    ->suffixAction(
                        Action::make('regenerate')
                            ->icon('heroicon-o-arrow-path')
                            ->action(function ($record, $set) {
                                if ($record && $record->suffix) {
                                    $newCode = self::generateInviteCode($record->suffix);
                                    $record->update(['invite_code' => $newCode]);
                                    $set('invite_code', $newCode);

                                    \Filament\Notifications\Notification::make()
                                        ->title('Invite code regenerated')
                                        ->success()
                                        ->send();
                                }
                            })
                    )
                    ->helperText('Click refresh icon to regenerate invite code'),
            ]);
    }

    public static function generateInviteCode(string $suffix): string
    {
        do {
            $code = strtoupper($suffix) . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Team::where('invite_code', $code)->exists());

        return $code;
    }
}
