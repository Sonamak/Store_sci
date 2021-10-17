<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <svg class="h-20 w-20 mx-auto text-yellow-500 font-extralight mb-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>

        <h2 class="text-base sm:text-xl text-gray-700 text-center">{{ translate('messages.registration_disabled') }}</h2>

        <span class="block text-center mt-5 text-gray-700">
            {{ translate('dashboard.auth.already_registered') }}
            <a class="underline hover:text-gray-900" href="{{ route('login', app()->getLocale()) }}">
                {{ translate('dashboard.auth.sign_in') }}
            </a>
        </span>
    </x-jet-authentication-card>
</x-guest-layout>