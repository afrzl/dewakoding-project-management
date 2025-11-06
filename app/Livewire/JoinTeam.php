<?php

namespace App\Livewire;

use App\Models\Team;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class JoinTeam extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('invite_code')
                ->label('Invite Code')
                ->placeholder('Enter your team invite code (e.g. DK000001)')
                ->required()
                ->maxLength(8)
                ->rules(['regex:/^[A-Z0-9]{8}$/'])
                ->helperText('Enter the 8-character team invite code')
                ->autocomplete('off')
                ->autofocus(),
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

            $this->redirect(filament()->getUrl($team));
            return;
        }

        $team->members()->attach($user);

        Notification::make()
            ->title('Success!')
            ->body('You have successfully joined ' . $team->name)
            ->success()
            ->send();

        $this->redirect(filament()->getUrl($team));
    }

    public function render()
    {
        return view('livewire.join-team')
            ->layout('filament-panels::components.layout.simple');
    }
}
