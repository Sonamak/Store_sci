<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ translate('dashboard.settings.application_settings') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-0 sm:px-4">
            <div class="overflow-hidden sm:rounded-lg">
                
                {{-- Settings --}}
                <livewire:settings />

                <x-jet-section-border />

                {{-- Backup --}}
                <x-jet-form-section
                    submit="saveSettings"
                    action="{{ route('restore', app()->getLocale()) }}"
                >
                    <x-slot name="title">
                        {{ translate('dashboard.settings.backup_restore') }}
                    </x-slot>
                
                    <x-slot name="description"></x-slot>
                
                    <x-slot name="form">
                        {{-- Backup --}}
                        <div class="col-span-6 sm:col-span-4">
                            <a
                                href="{{ route('backup', app()->getLocale()) }}"
                                class="m-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded py-2 px-6 border-indigo-400 text-white w-full inline-flex items-center justify-center transition duration-300ms ease-in-out"
                            >
                                {{ translate('dashboard.settings.backup_all') }}
                            </a>
                        </div>

                        {{-- Restore --}}
                        <div class="col-span-6 sm:col-span-4 flex flez-row hidden">
                            <input type="file" class="form-input p-2 rounded border border-gray-300 shadow w-full focus:outline-none focus:ring focus:border-blue-300" name="database" accept=".zip">
                            <button type="submit"
                                href="{{ route('backup', app()->getLocale()) }}"
                                class="m-0 ms-4 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded py-2 px-6 border-indigo-400 text-white w-full inline-flex items-center justify-center transition duration-300ms ease-in-out"
                            >
                                {{ translate('dashboard.settings.restore_all') }}
                            </button>
                        </div>
                    </x-slot>
                </x-jet-form-section>

                <x-jet-section-border />
            </div>
        </div>
    </div>
</x-app-layout>
