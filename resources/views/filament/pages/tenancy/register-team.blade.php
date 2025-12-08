<x-filament-panels::page.simple>
    @if($this->hasLogo())
        <div class="mb-6 flex justify-center">
            <x-filament-panels::logo />
        </div>
    @endif

    {{ $this->content }}

    <div class="mt-6 text-center space-y-3">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Have an invite code?
            <a href="{{ route('join-team') }}" class="text-primary-600 hover:text-primary-500 font-medium">
                Join existing team instead
            </a>
        </p>

        <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 underline">
                Logout
            </button>
        </form>
    </div>
</x-filament-panels::page.simple>