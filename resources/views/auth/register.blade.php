<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form
            method="POST"
            action="{{ route('register', app()->getLocale()) }}"
            x-data="{ phoneCode: '+0' }"
        >
            @csrf

            <div>
                <x-jet-label for="name" value="{{ translate('dashboard.users.name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Country custom dropdown --}}
                <x-custom-country-select :id="'country'" :name="'country'" :countries="$countries" :required="true" />

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
                    <x-jet-label for="password" value="{{ translate('dashboard.users.password') }}" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div>
                    <x-jet-label for="password_confirmation" value="{{ translate('dashboard.users.confirm_password') }}" />
                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login', app()->getLocale()) }}">
                    {{ translate('dashboard.auth.already_registered') }}
                </a>

                <x-jet-button class="ms-4">
                    {{ translate('dashboard.auth.register') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>

    @section('scripts')
        <script type="text/javascript">
            const country = document.getElementById('country');
            const state = document.getElementById('state');
            const city = document.getElementById('city');
            const genSpec = document.getElementById('general_specialization');
            const spec = document.getElementById('specialization');

            window.addEventListener('DOMContentLoaded', function() {
                window.addEventListener('change:country', getStates);
                state.addEventListener('change', getCities);
                genSpec.addEventListener('change', getSpecializations);
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
        </script>
    @stop
</x-guest-layout>