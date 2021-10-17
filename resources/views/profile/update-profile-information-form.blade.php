<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ translate('dashboard.profile.profile_information') }}
    </x-slot>

    <x-slot name="description"></x-slot>

    <x-slot name="form">

        @php
            // If enabled, show register page
            $userFields = \App\Models\UserField::get();
            
            $educational_attainment = $userFields->filter(function($userField) {
                return $userField->field == 'educational_attainment';
            });
            $general_specialization = $userFields->filter(function($userField) {
                return $userField->field == 'general_specialization';
            });
            $specialization = $userFields->filter(function($userField) {
                return $userField->field == 'specialization';
            });
        @endphp

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ translate('dashboard.users.name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autocomplete="name" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ translate('dashboard.users.email') }}" />
            <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-jet-input-error for="email" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ translate('dashboard.users.phone') }}" />
            <x-jet-input id="phone" type="tel" class="mt-1 block w-full" wire:model.defer="state.phone" />
            <x-jet-input-error for="phone" class="mt-2" />
        </div>

        <!-- Educational Attainment -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="educational_attainment" value="{{ translate('dashboard.users.educational_attainment') }}" />
            <select
                name="educational_attainment"
                id="educational_attainment"
                class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full"
                required
                wire:model.defer="state.educational_attainment_id"
            >
                <option value="{{ $state['educational_attainment_id'] }}" disabled selected>Select...</option>
                @foreach($educational_attainment as $row)
                    <option value="{{ $row->id }}">{{ $row->label }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="educational_attainment_id" class="mt-2" />
        </div>

        <!-- General Specialization -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="general_specialization" value="{{ translate('dashboard.users.general_specialization') }}" />
            <select
                name="general_specialization"
                id="general_specialization"
                class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full"
                required
                wire:model.defer="state.general_specialization_id"
            >
                <option value="{{ $state['general_specialization_id'] }}" disabled selected>Select...</option>
                @foreach($general_specialization as $row)
                    <option value="{{ $row->id }}">{{ $row->label }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="general_specialization_id" class="mt-2" />
        </div>

        <!-- Specialization -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="specialization" value="{{ translate('dashboard.users.specialization') }}" />
            <select
                name="specialization"
                id="specialization"
                class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full"
                required
                wire:model.defer="state.specialization_id"
                x-on:change="$wire.set('state.specialization_id', $el.value)"
                data-value="{{ $state['specialization_id'] }}"
            >
                <option value="" disabled selected>Select...</option>
            </select>
            <x-jet-input-error for="specialization_id" class="mt-2" />
        </div>

        <!-- Language -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="locale" value="{{ translate('dashboard.users.locale') }}" />
            <select
                name="locale"
                id="locale"
                class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full"
                required
                wire:model.defer="state.locale"
            >
                <option value="ar_AR">عربي</option>
                <option value="en_US">English</option>
            </select>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ translate('buttons.saved') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ translate('buttons.save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>

@section('scripts')
    <script type="text/javascript">
        const genSpec = document.getElementById('general_specialization');
        const spec = document.getElementById('specialization');

        window.addEventListener('DOMContentLoaded', function() {
            genSpec.addEventListener('change', getSpecializations);

            getSpecializations();
        });

        const getSpecializations = async function() {
            const general_id = genSpec.value;
            const selectedSpec = parseInt(spec.dataset.value);
            const { data:specsData } = await(await fetch(`/api/general-specializations/${general_id}/specializations`)).json();
            
            // Clear state select box
            spec.innerHTML = '';

            // Create html
            let specsHtml = `<option value="" disabled selected>Select...</option>`;
            if(specsData) {
                specsData.forEach(specD => {
                    specsHtml += `<option value="${specD.id}" ${specD.id === selectedSpec ? 'selected' : '' }>${specD.label}</option>`;
                });
            }

            // Put html in place
            spec.innerHTML = specsHtml;
        }
    </script>
@stop