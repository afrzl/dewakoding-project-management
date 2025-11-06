<div>
    <div class="mb-6 text-center">
        <h1 class="fi-simple-header-heading">
            Join Team
        </h1>
        <p class="fi-simple-header-subheading">
            Enter your invite code to join an existing team
        </p>
    </div>

    <form wire:submit="join">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" class="w-full">
                Join Team
            </x-filament::button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Don't have an invite code?
            <a href="{{ route('filament.admin.tenant.registration') }}"
                class="text-primary-600 hover:text-primary-500 font-medium">
                Create a new team
            </a>
        </p>
    </div>
</div>