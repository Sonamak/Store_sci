<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ translate('dashboard.user_fields.user_fields') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-0 sm:px-4">

            {{--  Educational Attainment --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-5">
                
                {{-- Body --}}
                <livewire:user-fields :field="'educational_attainment'" :id="rand(11111111, 99999999)"/>
            </div>

            {{-- Seperator --}}
            {{-- <div class="border-2 border-green-500 w-full"></div> --}}

            {{--  General Specialization --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg my-5">
                
                {{-- Body --}}
                <livewire:user-fields :field="'general_specialization'" :id="rand(11111111, 99999999)"/>
            </div>

            {{-- Seperator --}}
            {{-- <div class="border-2 border-green-500 w-full"></div> --}}

            {{--  Specialization --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-5">
                
                {{-- Body --}}
                <livewire:user-fields :field="'specialization'" :id="rand(11111111, 99999999)"/>
            </div>
        </div>
    </div>
</x-app-layout>
