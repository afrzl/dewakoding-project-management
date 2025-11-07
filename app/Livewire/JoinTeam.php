<?php

namespace App\Livewire;

use App\Models\Team;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

#[Layout('filament-panels::components.layout.simple')]
class JoinTeam extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        if (!auth()->check()) {
            redirect()->route('filament.admin.auth.login');
        }

        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('invite_code')
                ->label('Invite Code')
                ->placeholder('Enter your team invite code (e.g. DK000001)')
                ->required()
                ->autocomplete('off')
                ->autofocus()
                ->extraInputAttributes(['style' => 'text-transform: uppercase']),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
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

    public function render()
    {
        return view('livewire.join-team');
    }
}
