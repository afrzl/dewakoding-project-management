<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\TeamResource\Pages\CreateTeam;
use App\Filament\Superadmin\Resources\TeamResource\Pages\EditTeam;
use App\Filament\Superadmin\Resources\TeamResource\Pages\ListTeams;
use App\Filament\Superadmin\Resources\TeamResource\Schemas\TeamForm;
use App\Filament\Superadmin\Resources\TeamResource\Tables\TeamsTable;
use App\Models\Team;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Teams';

    protected static ?string $pluralLabel = 'Teams';
    
    protected static ?int $navigationSort = 2;
    
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
        return TeamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamsTable::configure($table);
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
            'index' => ListTeams::route('/'),
            'create' => CreateTeam::route('/create'),
            'edit' => EditTeam::route('/{record}/edit'),
        ];
    }
}
