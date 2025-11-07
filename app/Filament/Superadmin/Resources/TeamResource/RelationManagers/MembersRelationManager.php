<?php

namespace App\Filament\Superadmin\Resources\TeamResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Spatie\Permission\Models\Role;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\RelationManagers\RelationManager;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->options(\App\Models\User::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn(string $operation) => $operation === 'edit'),

                Select::make('roles')
                    ->label('Roles')
                    ->options(function () {
                        $team = $this->getOwnerRecord();
                        return Role::where('team_id', $team->id)
                            ->pluck('name', 'id');
                    })
                    ->multiple()
                    ->preload()
                    ->required()
                    ->afterStateHydrated(function (Select $component, $record) {
                        if ($record) {
                            $team = $this->getOwnerRecord();
                            // Get user's roles for this team
                            $roleIds = \DB::table('model_has_roles')
                                ->where('model_id', $record->id)
                                ->where('model_type', get_class($record))
                                ->where('team_id', $team->id)
                                ->pluck('role_id')
                                ->toArray();
                            $component->state($roleIds);
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles')
                    ->label('Roles')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $team = $this->getOwnerRecord();
                        return \DB::table('model_has_roles')
                            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('model_has_roles.model_id', $record->id)
                            ->where('model_has_roles.model_type', get_class($record))
                            ->where('model_has_roles.team_id', $team->id)
                            ->pluck('roles.name')
                            ->toArray();
                    })
                    ->separator(','),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function ($query) {
                        // Exclude users with global super_admin role (team_id = null)
                        return $query->whereNotExists(function ($subQuery) {
                            $subQuery->select(\DB::raw(1))
                                ->from('model_has_roles')
                                ->whereColumn('model_has_roles.model_id', 'users.id')
                                ->where('model_has_roles.model_type', \App\Models\User::class)
                                ->whereNull('model_has_roles.team_id');
                        });
                    })
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('roles')
                            ->label('Roles')
                            ->options(function () {
                                $team = $this->getOwnerRecord();
                                return Role::where('team_id', $team->id)
                                    ->pluck('name', 'id');
                            })
                            ->multiple()
                            ->required(),
                    ])
                    ->after(function (array $data, Model $record) {
                        $team = $this->getOwnerRecord();

                        // Assign roles to user for this team
                        if (isset($data['roles'])) {
                            foreach ($data['roles'] as $roleId) {
                                \DB::table('model_has_roles')->updateOrInsert([
                                    'role_id' => $roleId,
                                    'model_type' => get_class($record),
                                    'model_id' => $record->id,
                                    'team_id' => $team->id,
                                ], []);
                            }
                        }
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Remove user_id from update data
                        unset($data['user_id']);
                        return $data;
                    })
                    ->after(function (array $data, Model $record) {
                        $team = $this->getOwnerRecord();

                        // Update user's roles for this team
                        if (isset($data['roles'])) {
                            // Remove old roles for this team
                            \DB::table('model_has_roles')
                                ->where('model_id', $record->id)
                                ->where('model_type', get_class($record))
                                ->where('team_id', $team->id)
                                ->delete();

                            // Add new roles
                            foreach ($data['roles'] as $roleId) {
                                \DB::table('model_has_roles')->insert([
                                    'role_id' => $roleId,
                                    'model_type' => get_class($record),
                                    'model_id' => $record->id,
                                    'team_id' => $team->id,
                                ]);
                            }
                        }
                    }),
                DetachAction::make()
                    ->before(function (Model $record) {
                        $team = $this->getOwnerRecord();

                        // Remove user's roles for this team
                        \DB::table('model_has_roles')
                            ->where('model_id', $record->id)
                            ->where('model_type', get_class($record))
                            ->where('team_id', $team->id)
                            ->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->before(function ($records) {
                            $team = $this->getOwnerRecord();

                            foreach ($records as $record) {
                                \DB::table('model_has_roles')
                                    ->where('model_id', $record->id)
                                    ->where('model_type', get_class($record))
                                    ->where('team_id', $team->id)
                                    ->delete();
                            }
                        }),
                ]),
            ]);
    }
}
