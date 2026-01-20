<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JoinTeam extends RegisterTenant
{
    protected string $view = 'filament.pages.tenancy.join-team';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $joinData = [];
    public ?array $registerData = [];

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'join';
    }

    public function getTitle(): string
    {
        return 'Workspace';
    }

    public static function getLabel(): string
    {
        return 'Workspace';
    }

    public function mount(): void
    {
        if (!auth()->check()) {
            redirect()->route('filament.admin.auth.login');
            return;
        }

        $this->joinForm->fill();
        $this->form->fill(); // For registerForm (main form)
    }

    public function joinForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invite_code')
                    ->label('Invite Code')
                    ->placeholder('Enter workspace invite code (e.g. DK000001)')
                    ->required()
                    ->autocomplete('off')
                    ->autofocus()
                    ->extraInputAttributes(['style' => 'text-transform: uppercase']),
            ])
            ->statePath('joinData');
    }

    // Main form for registration (inherited from RegisterTenant)
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Workspace Name')
                    ->placeholder('Enter your workspace name')
                    ->required()
                    ->maxLength(255),
            ])
            ->statePath('data'); // Use default 'data' for RegisterTenant
    }

    public function join(): void
    {
        $data = $this->joinForm->getState();
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

    // Override register method from RegisterTenant to use custom logic
    protected function handleRegistration(array $data): Team
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        \Log::info('JoinTeam: Starting team registration', [
            'data' => $data,
            'user_id' => auth()->id()
        ]);

        $team = Team::create($data);

        // Attach user sebagai member
        $team->members()->attach(auth()->id());

        \Log::info('JoinTeam: User attached as member', [
            'team_id' => $team->id,
            'user_id' => auth()->id()
        ]);

        // Assign user sebagai super_admin di team ini
        $superAdminRole = \App\Models\Role::where('name', 'super_admin')
            ->where('team_id', $team->id)
            ->first();

        if ($superAdminRole) {
            \Log::info('JoinTeam: super_admin role found', [
                'role_id' => $superAdminRole->id,
                'team_id' => $team->id
            ]);

            \DB::table('model_has_roles')->insert([
                'role_id' => $superAdminRole->id,
                'model_type' => \App\Models\User::class,
                'model_id' => auth()->id(),
                'team_id' => $team->id,
            ]);

            \Log::info('JoinTeam: super_admin role assigned', [
                'user_id' => auth()->id(),
                'team_id' => $team->id
            ]);
        } else {
            \Log::warning('JoinTeam: super_admin role NOT found', [
                'team_id' => $team->id
            ]);
        }

        return $team;
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']);
        return $data;
    }

    protected function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Team::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $slug;
    }
}
