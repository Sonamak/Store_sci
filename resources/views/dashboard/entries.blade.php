<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ translate('dashboard.entries.entries') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-0 sm:px-4">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                
                {{-- Body --}}
                <livewire:entries />
            </div>
        </div>
    </div>
</x-app-layout>
