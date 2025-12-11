<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-user-group class="h-5 w-5 text-primary-500" />
                <span>Workspace Information</span>
            </div>
        </x-slot>

        <x-slot name="description">
            Invite others to join your workspace
        </x-slot>

        <div class="space-y-4">
            {{-- Team Stats --}}
            <div class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Workspace Name</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $this->getTeamName() }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-300">Members</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $this->getMembersCount() }}</p>
                </div>
            </div>

            {{-- Invite Code Section --}}
            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Invite Code</p>
                        <p class="text-2xl font-mono font-bold text-primary-600 dark:text-primary-400 tracking-wider">
                            {{ $this->getInviteCode() }}
                        </p>
                    </div>
                    <button type="button" x-data="{ copied: false }" x-on:click="
                            navigator.clipboard.writeText('{{ $this->getInviteCode() }}');
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                        "
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <template x-if="!copied">
                            <x-heroicon-o-clipboard-document class="h-4 w-4" />
                        </template>
                        <template x-if="copied">
                            <x-heroicon-o-check class="h-4 w-4 text-green-500" />
                        </template>
                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>
            </div>

            {{-- How to Join Tutorial --}}
            <div class="p-4 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <x-heroicon-o-information-circle class="h-4 w-4 text-primary-500 dark:text-primary-400" />
                    How to invite others
                </h4>
                <ol class="space-y-2 text-sm text-gray-600 dark:text-gray-200">
                    <li class="flex items-start gap-2">
                        <span
                            class="shrink-0 w-5 h-5 flex items-center justify-center bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-full text-xs font-medium">1</span>
                        <span>Share the <strong class="text-gray-900 dark:text-white">Invite Code</strong> above with
                            your workspace members</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span
                            class="shrink-0 w-5 h-5 flex items-center justify-center bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-full text-xs font-medium">2</span>
                        <span>They need to register or login at <a href="{{ url('/register') }}"
                                class="text-primary-600 dark:text-primary-400 hover:underline">{{ url('/register') }}</a></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span
                            class="shrink-0 w-5 h-5 flex items-center justify-center bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-full text-xs font-medium">3</span>
                        <span>After login, go to <a href="{{ $this->getJoinUrl() }}"
                                class="text-primary-600 dark:text-primary-400 hover:underline">{{ $this->getJoinUrl() }}</a>
                            and enter the code</span>
                    </li>
                </ol>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>