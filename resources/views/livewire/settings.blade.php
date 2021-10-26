<x-jet-form-section submit="saveSettings">
    <x-slot name="title">
        {{ translate('dashboard.settings.configure_settings') }}
    </x-slot>

    <x-slot name="description"></x-slot>

    <x-slot name="form">
        <!-- WhatsApp -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="whatsapp" value="{{ translate('dashboard.settings.whatsapp_number') }}" />
            <x-jet-input id="whatsapp" type="text" class="mt-1 block w-full" wire:model.defer="data.whatsapp" autocomplete="off" />
        </div>

        {{-- Registraion --}}
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="registration" value="{{ translate('dashboard.settings.user_registration') }}" class="mb-1" />

            <label for="registration_on" class="text-gray-700 cursor-pointer inline-flex items-center me-5">
                <input type="radio" id="registration_on" name="registration" value="on" wire:model.defer="data.registration">
                <span class="ms-2">{{ translate('dashboard.common.enable') }}</span>
            </label>
            <label for="registration_off" class="text-gray-700 cursor-pointer inline-flex items-center">
                <input type="radio" id="registration_off" name="registration" value="off" wire:model.defer="data.registration">
                <span class="ms-2">{{ translate('dashboard.common.disable') }}</span>
            </label>
        </div>
        
        {{-- Registraion --}}
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="registration" value="{{ translate('dashboard.settings.guest_allowed') }}" class="mb-1" />

            <label for="guest_on" class="text-gray-700 cursor-pointer inline-flex items-center me-5">
                <input type="radio" id="guest_on" name="guest" value="on" wire:model.defer="data.guest_allowed">
                <span class="ms-2">{{ translate('dashboard.common.enable') }}</span>
            </label>
            <label for="guest_off" class="text-gray-700 cursor-pointer inline-flex items-center">
                <input type="radio" id="guest_off" name="guest" value="off" wire:model.defer="data.guest_allowed">
                <span class="ms-2">{{ translate('dashboard.common.disable') }}</span>
            </label>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label for="whatsapp_message">{{ translate('dashboard.settings.configure_whatsapp') }}</label>
            <x-jet-textarea id="whatsapp_message" type='text' class="mt-1 block w-full" wire:model.defer="data.whatsapp_message" autocomplete="off" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <button
            type="submit"
            class="mt-4 sm:mt-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded py-2 px-6 shadow border-indigo-400 text-white h-9 inline-flex items-center justify-center transition duration-300ms ease-in-out"
            wire:loading.attr="disabled"
        >
            {{ translate('buttons.save') }}
        </button>
    </x-slot>

</x-jet-form-section>
