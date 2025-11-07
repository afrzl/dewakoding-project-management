<?php

namespace App\Filament\Superadmin\Resources\UserResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255),

                Toggle::make('is_superadmin')
                    ->label('Is Superadmin')
                    ->helperText('Grant global superadmin access (bypasses all team restrictions)')
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $record) {
                        // This will be handled in the page's save logic
                    })
                    ->dehydrated(false), // Don't save to users table

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                Select::make('teams')
                    ->relationship('teams', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }
}
