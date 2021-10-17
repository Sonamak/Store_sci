<x-app-layout title="Create User">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ translate('dashboard.users.create_user') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-0 sm:px-4">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                
                {{-- Body --}}
                <div class="p-2">
                    {{-- Errors Container --}}
                    <div>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600" id="error-container"></ul>
                    </div>

                    {{-- Form --}}
                    <form
                        method="POST"
                        action="{{ url('/api/register') }}"
                        id="create-user-form"
                        x-data="{ phoneCode: '+0' }"
                    >
                        @csrf

                        <div>
                            <x-jet-label for="name" value="{{ translate('dashboard.users.name') }}" />
                            <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Country custom dropdown --}}
                            <x-custom-country-select :id="'country'" :name="'country'" :countries="$countries" :required="true" width="w-full" />

                            {{-- State Dropdown --}}
                            <div>
                                <x-jet-label for="state" value="{{ translate('dashboard.users.state') }}" />
                                <select
                                    name="state"
                                    id="state"
                                    class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full"
                                >
                                    <option value="" disabled selected>Select...</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-jet-label for="city" value="{{ translate('dashboard.users.city') }}" />
                                <select
                                    name="city"
                                    id="city"
                                    class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full"
                                >
                                    <option value="" disabled selected>Select...</option>
                                </select>
                            </div>

                            <div>
                                <x-jet-label for="locale" value="{{ translate('dashboard.users.locale') }}" />
                                <select name="locale" id="locale" class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="ar_AR" {{ env('APP_LOCALE') == 'ar_AR' ? 'selected' : '' }}>عربي</option>
                                    <option value="en_US" {{ env('APP_LOCALE') == 'en_US' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                        </div>     

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-jet-label for="email" value="{{ translate('dashboard.users.email') }}" />
                                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            </div>

                            <div>
                                <x-jet-label for="phone" value="{{ translate('dashboard.users.phone') }}" />
                                <x-jet-input id="phone" class="block mt-1 w-full rounded-e-md" type="tel" name="phone" :value="old('phone')" required />
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <x-jet-label for="educational_attainment" value="{{ translate('dashboard.users.educational_attainment') }}" />
                            <select name="educational_attainment_id" id="educational_attainment" class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="" disabled selected>Select...</option>
                                @foreach($educational_attainment as $row)
                                    <option value="{{ $row->id }}" {{ old('educational_attainment_id') == $row->id ? 'selected' : '' }}>{{ $row->label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-jet-label for="general_specialization" value="{{ translate('dashboard.users.general_specialization') }}" />
                            <select name="general_specialization_id" id="general_specialization" class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="" disabled selected>Select...</option>
                                @foreach($general_specialization as $row)
                                    <option value="{{ $row->id }}" {{ old('general_specialization_id') == $row->id ? 'selected' : '' }}>{{ $row->label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-jet-label for="specialization" value="{{ translate('dashboard.users.specialization') }}" />
                            <select name="specialization_id" id="specialization" class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="" disabled selected>Select...</option>
                            </select>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-jet-label for="role" value="{{ translate('dashboard.users.role') }}" />
                                <select name="role" id="role" class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="user">User</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div>
                                <x-jet-label for="password" value="{{ translate('dashboard.users.password') }}" />
                                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ms-4">
                                {{ translate('buttons.create') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script type="text/javascript">
            const country = document.getElementById('country');
            const state = document.getElementById('state');
            const city = document.getElementById('city');
            const $form = document.getElementById('create-user-form');
            const genSpec = document.getElementById('general_specialization');
            const spec = document.getElementById('specialization');

            window.addEventListener('DOMContentLoaded', function() {
                window.addEventListener('change:country', getStates);
                state.addEventListener('change', getCities);
                genSpec.addEventListener('change', getSpecializations);
                $form.addEventListener('submit', createUser);
            });

            // Functions
            const getStates = async function() {
                const countryCode = country.dataset.countryCode;
                const { data:states } = await(await fetch(`/api/countries/${countryCode}/states`)).json();
                
                // Clear state select box
                state.innerHTML = '';
                resetCity();

                // Make state optional
                state.required = false;

                // Create html
                let statesHtml = `<option value="" disabled selected>Select...</option>`;
                if(states) {
                    // Make state required if states available
                    state.required = true;

                    states.forEach(state => {
                        statesHtml += `<option value="${state.iso2}">${state.name}</option>`;
                    });
                }

                // Put html in place
                state.innerHTML = statesHtml;
            }

            const getCities = async function() {
                const countryCode = country.dataset.countryCode;
                const stateCode = state.value;
                const { data:cities } = await(await fetch(`/api/countries/${countryCode}/states/${stateCode}/cities`)).json();
                
                // Clear state select box
                city.innerHTML = '';

                // Make city optional
                city.required = false;

                // Create html
                let citiesHtml = `<option value="" disabled selected>Select...</option>`;
                if(cities) {
                    // Make city required if cities available
                    city.required = true;

                    cities.forEach(city => {
                        citiesHtml += `<option value="${city.name}">${city.name}</option>`;
                    });
                }

                // Put html in place
                city.innerHTML = citiesHtml;
            }

            const getSpecializations = async function() {
                const general_id = genSpec.value;
                const { data:specsData } = await(await fetch(`/api/general-specializations/${general_id}/specializations`)).json();
                
                // Clear state select box
                spec.innerHTML = '';

                // Create html
                let specsHtml = `<option value="" disabled selected>Select...</option>`;
                if(specsData) {
                    specsData.forEach(specD => {
                        specsHtml += `<option value="${specD.id}">${specD.label}</option>`;
                    });
                }

                // Put html in place
                spec.innerHTML = specsHtml;
            }

            const resetCity = function() {
                city.innerHTML = '';
                city.innerHTML = `<option value="" disabled selected>Select...</option>`;
                city.required = false;
            }

            const createUser = function() {
                event.preventDefault();

                const $button = document.querySelector('[type=submit]');
                const data = new FormData($form);
                const $errorContainer = document.getElementById('error-container');
                const url = $form.action;

                // Empty errors
                $errorContainer.innerHTML = '';
                $errorContainer.classList.remove('mb-4');
                $button.disabled = true;

                // Check country selection
                if(country.value === '') {
                    $errorContainer.innerHTML = '';
                    $errorContainer.classList.add('mb-4');
                    $errorContainer.innerHTML += `<li>Please select your country</li>`;

                    $button.disabled = false;
                    return false;
                }

                // Create data array
                data.append('country_code', country.dataset.countryCode);

                // Post data
                axiosIns.post(url, data)
                    .then(response => {
                        const { data } = response;

                        if (!data.success) {
                            $button.disabled = false;
                            notifier.show('Oops', data.message, 'danger', '', 7000);
                            return true;
                        }

                        notifier.show('Success', data.message, 'success', '', 7000);
                        setTimeout(_ => {
                            window.location.href = '{{ route("users", app()->getLocale()) }}';
                        }, 2000);

                    }).catch(error => {
                        const { data } = error.response.data;

                        $errorContainer.innerHTML = '';
                        $errorContainer.classList.add('mb-4');
                        data.forEach(function(err) {
                            $errorContainer.innerHTML += `<li>${err}</li>`;
                        });

                        $button.disabled = false;
                    });
            }
        </script>
    @stop
</x-app-layout>