<?php

namespace App\Filament\Superadmin\Resources\TeamResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invite_code')
                    ->label('Invite Code')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Invite code copied!')
                    ->icon('heroicon-o-clipboard-document')
                    ->tooltip('Click to copy')
                    ->sortable(),

                TextColumn::make('members_count')
                    ->counts('members')
                    ->label('Members')
                    ->sortable(),

                TextColumn::make('projects_count')
                    ->counts('projects')
                    ->label('Projects')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('access_tenant')
                    ->label('Access Tenant')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn($record) => route('filament.admin.pages.dashboard', ['tenant' => $record->slug]))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
