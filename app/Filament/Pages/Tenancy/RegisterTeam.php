<?php

namespace App\Filament\Pages\Tenancy;

use Throwable;
use App\Models\Team;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Support\Facades\FilamentView;

class RegisterTeam extends RegisterTenant
{
    protected string $view = 'filament.pages.tenancy.register-team';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function register(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $this->tenant = $this->handleRegistration($data);

            $this->form->model($this->tenant)->saveRelationships();

            $this->callHook('afterRegister');
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->commitDatabaseTransaction();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode($redirectUrl));
        }
    }

    public static function getLabel(): string
    {
        return 'Register workspace';
    }

    // public function getHeading(): string
    // {
    //     return 'Register Team';
    // }

    public function hasLogo(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Workspace Name')
                    ->required()
                    ->maxLength(255),
            ]);
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

    protected function handleRegistration(array $data): Team
    {
        \Log::info('RegisterTeam: Starting team registration', [
            'data' => $data,
            'user_id' => auth()->id()
        ]);

        try {
            $team = Team::create($data);

            // Attach user sebagai member
            $team->members()->attach(auth()->id());

            \Log::info('RegisterTeam: User attached as member', [
                'team_id' => $team->id,
                'user_id' => auth()->id()
            ]);

            // Assign user sebagai super_admin di team ini
            $superAdminRole = \App\Models\Role::where('name', 'super_admin')
                ->where('team_id', $team->id)
                ->first();

            if ($superAdminRole) {
                \Log::info('RegisterTeam: super_admin role found', [
                    'role_id' => $superAdminRole->id,
                    'team_id' => $team->id
                ]);

                \DB::table('model_has_roles')->insert([
                    'role_id' => $superAdminRole->id,
                    'model_type' => \App\Models\User::class,
                    'model_id' => auth()->id(),
                    'team_id' => $team->id,
                ]);

                \Log::info('RegisterTeam: super_admin role assigned', [
                    'user_id' => auth()->id(),
                    'team_id' => $team->id
                ]);
            } else {
                \Log::warning('RegisterTeam: super_admin role NOT found', [
                    'team_id' => $team->id
                ]);
            }

            return $team;
        } catch (\Exception $e) {
            \Log::error('RegisterTeam: Error during registration', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
