<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class EditTeam extends EditTenantProfile
{
    protected string $view = 'filament.pages.tenancy.edit-team';

    public static function getLabel(): string
    {
        return 'Workspace settings';
    }

    public static function canView(\Illuminate\Database\Eloquent\Model $tenant): bool
    {
        $user = auth()->user();
        
        // Allow global super_admin OR workspace super_admin
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Check if user has super_admin role in this specific team
        setPermissionsTeamId($tenant->id);
        $hasRole = $user->hasRole('super_admin');
        setPermissionsTeamId(null);
        
        return $hasRole;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Workspace Name')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('invite_code')
                    ->label('Invite Code')
                    ->disabled()
                    ->helperText('Share this code with team members to invite them to this workspace'),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('delete')
                ->label('Delete Workspace')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('Delete Workspace')
                ->modalDescription('Are you sure you want to delete this workspace? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete workspace')
                ->form([
                    TextInput::make('confirmation')
                        ->label('Type workspace name to confirm')
                        ->placeholder($this->tenant->name)
                        ->required()
                        ->rule('in:' . $this->tenant->name)
                        ->validationMessages([
                            'in' => 'Workspace name does not match.',
                        ])
                        ->helperText('Type "' . $this->tenant->name . '" to confirm deletion'),
                ])
                ->action(function (array $data) {
                    $this->deleteWorkspace();
                })
                ->visible(function () {
                    $user = auth()->user();
                    $team = $this->tenant;
                    
                    // Check if user is global super_admin OR workspace super_admin
                    if ($user->isSuperAdmin()) {
                        return true;
                    }
                    
                    setPermissionsTeamId($team->id);
                    $hasRole = $user->hasRole('super_admin');
                    setPermissionsTeamId(null);
                    
                    return $hasRole;
                }),
        ];
    }

    protected function deleteWorkspace(): void
    {
        $team = $this->tenant;
        $user = auth()->user();

        // Verify user is global super_admin OR workspace super_admin
        if (!$user->isSuperAdmin()) {
            setPermissionsTeamId($team->id);
            $hasRole = $user->hasRole('super_admin');
            setPermissionsTeamId(null);
            
            if (!$hasRole) {
                Notification::make()
                    ->title('Unauthorized')
                    ->body('Only workspace super admins can delete the workspace.')
                    ->danger()
                    ->send();
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Delete related data
            DB::table('model_has_roles')->where('team_id', $team->id)->delete();
            DB::table('model_has_permissions')->where('team_id', $team->id)->delete();
            
            // Delete team roles
            \App\Models\Role::where('team_id', $team->id)->delete();
            
            // Detach all members
            $team->members()->detach();
            
            // Delete the team
            $team->delete();

            DB::commit();

            Notification::make()
                ->title('Workspace deleted')
                ->body('The workspace has been successfully deleted.')
                ->success()
                ->send();

            // Redirect to join/register page
            $this->redirect(route('filament.admin.tenant.registration'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error deleting workspace', [
                'error' => $e->getMessage(),
                'team_id' => $team->id,
            ]);

            Notification::make()
                ->title('Error')
                ->body('Failed to delete workspace: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
