<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Pages\SimplePage;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;

class JoinTeam extends SimplePage
{
    protected string $view = 'filament.pages.tenancy.join-team';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public static function getSlug(): string
    {
        return 'join';
    }

    public function getTitle(): string
    {
        return 'Join Team';
    }

    public function mount(): void
    {
        if (!auth()->check()) {
            redirect()->route('filament.admin.auth.login');
            return;
        }

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invite_code')
                    ->label('Invite Code')
                    ->placeholder('Enter your team invite code (e.g. DK000001)')
                    ->required()
                    ->autocomplete('off')
                    ->autofocus()
                    ->extraInputAttributes(['style' => 'text-transform: uppercase']),
            ])
            ->statePath('data');
    }

    public function join(): void
    {
        $data = $this->form->getState();
        $team = Team::where('invite_code', strtoupper($data['invite_code']))->first();

        if (!$team) {
            Notification::make()
                ->title('Invalid invite code')
                ->body('The invite code you entered does not exist.')
                ->danger()
                ->send();
            return;
        }

        $user = Auth::user();

        if ($team->members()->where('user_id', $user->id)->exists()) {
            Notification::make()
                ->title('Already a member')
                ->body('You are already a member of this team.')
                ->warning()
                ->send();

            $this->redirect(route('filament.admin.pages.dashboard', ['tenant' => $team->slug]));
            return;
        }

        $team->members()->attach($user);

        // Auto-assign role "member" saat join team
        $memberRole = \App\Models\Role::where('name', 'member')
            ->where('team_id', $team->id)
            ->first();

        if ($memberRole) {
            \DB::table('model_has_roles')->insert([
                'role_id' => $memberRole->id,
                'model_type' => \App\Models\User::class,
                'model_id' => $user->id,
                'team_id' => $team->id,
            ]);
        }

        Notification::make()
            ->title('Success!')
            ->body('You have successfully joined ' . $team->name)
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.dashboard', ['tenant' => $team->slug]));
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('join')
                ->label('Join Team')
                ->submit('join'),
        ];
    }
}
