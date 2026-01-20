<x-filament-panels::page.simple>
    @push('styles')
        <style>
            .fi-simple-main {
                max-width: 1200px !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 3rem 2rem !important;
            }

            .fi-simple-main-ctn {
                max-width: 100% !important;
                width: 100% !important;
            }
        </style>
    @endpush

    <!-- Workspace Explanation -->
    <div class="mb-8 text-center">
        <p class="text-base text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            A workspace is a collaborative environment where you and your team can manage projects, tasks, and resources
            together.
            Join an existing workspace with an invite code or create a new one to get started.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full">
        <!-- Left Side: Join Existing Workspace -->
        <div class="flex flex-col">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-10">
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Join Workspace
                    </h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Enter your invite code to join an existing team workspace.
                    </p>
                </div>

                <form wire:submit="join" class="space-y-10">
                    {{ $this->joinForm }}

                    <x-filament::button type="submit" color="info" class="w-full" size="lg">
                        Join Workspace
                    </x-filament::button>
                </form>
            </div>
        </div>

        <!-- Right Side: Create New Workspace -->
        <div class="flex flex-col">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-10">
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Create Workspace
                    </h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Create a new workspace to start managing projects with your team.
                    </p>
                </div>

                <form wire:submit="register" class="space-y-10">
                    {{ $this->form }}

                    <x-filament::button type="submit" color="success" class="w-full" size="lg">
                        Create Workspace
                    </x-filament::button>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Option -->
    <div class="mt-8 text-center">
        <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
            @csrf
            <button type="submit"
                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 underline">
                Logout
            </button>
        </form>
    </div>
</x-filament-panels::page.simple>
