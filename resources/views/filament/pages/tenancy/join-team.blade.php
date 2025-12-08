<x-filament-panels::page.simple>
    <form wire:submit="join" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit" class="w-full">
            Join Team
        </x-filament::button>
    </form>

    <div class="mt-6 text-center space-y-3">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Don't have an invite code?
            <a href="{{ route('filament.admin.tenant.registration') }}"
                class="text-primary-600 hover:text-primary-500 font-medium">
                Register a new team instead
            </a>
        </p>

        <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
            @csrf
            <button type="submit"
                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 underline">
                Logout
            </button>
        </form>
    </div>
</x-filament-panels::page.simple>