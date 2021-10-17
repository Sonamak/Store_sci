<div
    class="relative"
    x-data="{
        listVisible: false,
        selectedCountry: '{{ !empty($selected) ? $selected->name . ' (' . $selected->phonecode . ')' : null }}',
        selectedCountryValue: '{{ !empty($selected) ? $selected->iso2 : null }}',
        selectedCountryCode: '{{ !empty($selected) ? $selected->iso2 : null }}',
        selectedCountryPhoneCode: '{{ !empty($selected) ? $selected->phonecode : null }}',
    }"
>
    <x-jet-label for="country" value="{{ translate('dashboard.users.country') }}" />

    {{-- Select Box --}}
    <div
        class="cursor-pointer inline-flex justify-between items-center border border-gray-300 focus:border-indigo-300 rounded-md shadow-sm block mt-1 w-full py-2 px-3"
        x-on:click.prevent="listVisible = true"
        x-on:click.away="listVisible = false"
        x-bind:class="{ 'ring ring-indigo-200 ring-opacity-50': listVisible }"
    >
        <span
            class="whitespace-nowrap overflow-hidden flex-1"
            x-text="selectedCountry === '' ? 'Select...' : selectedCountry"
        >
            Select...
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 font-bold ms-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" x-model="selectedCountryValue" x-bind:data-country-code="selectedCountryCode" {{ $required ? 'required' : ''}}>
    <input type="hidden" name="phone_code" id="phone_code" x-model="selectedCountryPhoneCode">

    {{-- List --}}
    <div
        class="absolute z-50 {{ empty($width) ? 'w-72 xs:w-max' : $width }} max-h-72 bg-white overflow-auto border shadow-lg"
        style="display: none;"
        x-show="listVisible"
    >
        @foreach($countries as $country)
            <div
                class="p-2 flex items-center w-full hover:bg-blue-500 hover:text-white cursor-default whitespace-nowrap text-xs xs:text-sm"
                x-bind:class="{ 'bg-blue-500 text-white': selectedCountryCode === '{{ $country->iso2 }}' }"
                data-country-code="{{ $country->iso2 }}"
                data-phone-code="{{ $country->phonecode }}"
                data-value="{{ $country->iso2 }}"
                x-on:click.prevent="
                    selectedCountry = $el.innerText;
                    selectedCountryValue = $el.dataset.value;
                    selectedCountryCode = $el.dataset.countryCode;
                    selectedCountryPhoneCode = $el.dataset.phoneCode;
                    listVisible = false;
                    $nextTick(() => {
                        $dispatch('change:country');
                    });
                "
                x-on:load="alert('H')"
            >
                <img src="https://www.countryflags.io/{{ $country->iso2 }}/flat/24.png" alt="" class="w-5 me-2">
                {{ $country->name }} ({{ $country->phonecode }})
            </div>
        @endforeach
                        
    </div>
</div>