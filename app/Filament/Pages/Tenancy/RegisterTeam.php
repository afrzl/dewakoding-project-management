<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RegisterTeam extends RegisterTenant
{
    protected string $view = 'filament.pages.tenancy.register-team';

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label('Subdomain')
                    ->required()
                    ->unique(table: Team::class, ignorable: fn($record) => $record)
                    ->alphaDash()
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);

        // Attach user sebagai member
        $team->members()->attach(auth()->user());

        // Assign user sebagai super_admin di team ini
        $superAdminRole = \App\Models\Role::where('name', 'super_admin')
            ->where('team_id', $team->id)
            ->first();

        if ($superAdminRole) {
            \DB::table('model_has_roles')->insert([
                'role_id' => $superAdminRole->id,
                'model_type' => \App\Models\User::class,
                'model_id' => auth()->id(),
                'team_id' => $team->id,
            ]);
        }

        return $team;
    }
}
