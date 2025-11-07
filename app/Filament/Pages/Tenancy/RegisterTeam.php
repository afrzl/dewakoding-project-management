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
        return 'Register team';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label('Subdomain')
                    ->required()
                    ->unique(table: Team::class, ignorable: fn($record) => $record)
                    ->alphaDash()
                    ->maxLength(255),
            ]);
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
            $team->members()->attach(auth()->user());

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
