<x-filament-panels::page.simple>
    @if($this->hasLogo())
        <div class="mb-6 flex justify-center">
            <x-filament-panels::logo />
        </div>
    @endif

    {{ $this->content }}

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Have an invite code?
            <a href="{{ route('join-team') }}" class="text-primary-600 hover:text-primary-500 font-medium">
                Join existing team instead
            </a>
        </p>
    </div>
</x-filament-panels::page.simple>