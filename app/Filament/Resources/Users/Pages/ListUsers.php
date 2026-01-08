<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('attach')
                ->label('Attach Existing User')
                ->icon('heroicon-o-link')
                ->color('gray')
                ->form([
                    TextInput::make('email')
                        ->label('User Email')
                        ->email()
                        ->required()
                        ->helperText('Enter the email of an existing user to add them to this team')
                        ->placeholder('user@example.com'),
                    Select::make('roles')
                        ->label('Roles')
                        ->options(function () {
                            return \App\Models\Role::where('team_id', \Filament\Facades\Filament::getTenant()->id)
                                ->pluck('name', 'id');
                        })
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->required()
                        ->helperText('Select roles to assign to this user in this team'),
                ])
                ->action(function (array $data) {
                    // Query user tanpa tenant scope
                    $user = User::withoutGlobalScopes()->where('email', $data['email'])->first();
                    
                    if (!$user) {
                        Notification::make()
                            ->danger()
                            ->title('User not found')
                            ->body('No user exists with email: ' . $data['email'])
                            ->send();
                        return;
                    }

                    $tenant = \Filament\Facades\Filament::getTenant();
                    
                    // Check if user is already in this team
                    if ($user->teams()->where('teams.id', $tenant->id)->exists()) {
                        Notification::make()
                            ->warning()
                            ->title('User already exists')
                            ->body($user->name . ' is already a member of this team.')
                            ->send();
                        return;
                    }

                    // Attach user to team
                    $user->teams()->attach($tenant->id);

                    // Assign roles with team_id in pivot
                    foreach ($data['roles'] as $roleId) {
                        DB::table('model_has_roles')->insertOrIgnore([
                            'model_id' => $user->id,
                            'model_type' => User::class,
                            'role_id' => $roleId,
                            'team_id' => $tenant->id,
                        ]);
                    }

                    Notification::make()
                        ->success()
                        ->title('User attached successfully')
                        ->body($user->name . ' has been added to this team.')
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
