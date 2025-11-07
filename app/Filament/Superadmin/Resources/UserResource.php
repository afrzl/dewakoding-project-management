<?php

namespace App\Filament\Superadmin\Resources;

use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Superadmin\Resources\UserResource\Pages\EditUser;
use App\Filament\Superadmin\Resources\UserResource\Pages\ViewUser;
use App\Filament\Superadmin\Resources\UserResource\Pages\ListUsers;
use App\Filament\Superadmin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Superadmin\Resources\UserResource\Schemas\UserForm;
use App\Filament\Superadmin\Resources\UserResource\Tables\UsersTable;
use App\Filament\Superadmin\Resources\UserResource\Schemas\UserInfolist;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $pluralLabel = 'Users';

    protected static ?int $navigationSort = 1;

    // Set policy to null to completely disable policy checks
    protected static ?string $modelPolicy = null;
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    // Override getEloquentQuery untuk bypass tenant scope
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes(); // Bypass semua global scopes termasuk tenant
    }

    // Disable authorization gate
    protected static bool $shouldAuthorize = false;

    // Disable policy authorization for superadmin panel
    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canView($record): bool
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

    public static function canForceDelete($record): bool
    {
        return true;
    }

    public static function canForceDeleteAny(): bool
    {
        return true;
    }

    public static function canRestore($record): bool
    {
        return true;
    }

    public static function canRestoreAny(): bool
    {
        return true;
    }

    public static function canReplicate($record): bool
    {
        return true;
    }

    public static function canReorder(): bool
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
