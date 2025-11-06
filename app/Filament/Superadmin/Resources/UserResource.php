<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Superadmin\Resources\UserResource\Pages\EditUser;
use App\Filament\Superadmin\Resources\UserResource\Pages\ListUsers;
use App\Filament\Superadmin\Resources\UserResource\Pages\ViewUser;
use App\Filament\Superadmin\Resources\UserResource\Schemas\UserForm;
use App\Filament\Superadmin\Resources\UserResource\Schemas\UserInfolist;
use App\Filament\Superadmin\Resources\UserResource\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $pluralLabel = 'Users';
    
    protected static ?int $navigationSort = 1;
    
    // Disable policy authorization for superadmin panel
    public static function canViewAny(): bool
    {
        return true;
    }
    
    public static function canCreate(): bool
    {
        return true;
    }
    
    public static function canEdit($record): bool
    {
        return true;
    }
    
    public static function canDelete($record): bool
    {
        return true;
    }
    
    public static function canDeleteAny(): bool
    {
        return true;
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
