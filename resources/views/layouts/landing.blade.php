<!DOCTYPE html>
<html lang="<?= explode('_', lang())[0] ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="{{ asset('logo.jpg') }}" type="image/jpg">

        <title>{{ translate('dashboard.global.app_name') }} - {{ $title }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        @livewireStyles

        <!-- Page Styles -->
        @yield('styles')
    </head>
    <body class="font-sans antialiased" dir="<?= explode('_', lang())[0] == 'ar' ? 'rtl' : 'ltr' ?>">
        <div class="font-sans text-gray-900 antialiased h-full">

            <livewire:navigation-menu />
            
            {{-- Main Body --}}
            <main class="flex flex-col h-full max-w-7xl mx-auto">
                {{ $slot }}
            </main>

            {{-- Bottom --}}
            <div class="fixed z-50 bottom-0 w-full">
                <div class="max-w-7xl mx-auto bg-gray-100 flex flex-col lg:flex-row justify-between lg:h-10 w-full items-center border border-gray-300 p-4">
                    <span class="text-gray-600 text-sm text-center lg:text-left">
                        &copy; Copyright {{ date('Y', time()) }}.
                        All Rights Reserved
                        <a href="{{ env('APP_URL') }}" class="font-bold hover:underline">{{ env('APP_NAME') }}</a>,
                        Proudly powered by
                        <a href="https://roidnet.com/" class="font-bold hover:underline">RoidNet.</a>

                        <span class="hidden lg:inline ps-2 me-2 border-e border-gray-500"></span>

                        <a href="#" class="block lg:inline w-full lg:w-auto hover:underline mt-2 lg:mt-0">Terms &amp; Conditions</a>
                        <span class="hidden lg:inline ps-2 me-2 border-e border-gray-500"></span>

                        <a href="{{ route('privacy_policy', app()->getLocale()) }}" class="block lg:inline w-full lg:w-auto hover:underline mt-2 lg:mt-0">Privacy policy</a>

                    </span>

                    @if(lang() == 'ar_AR')
                        <a href="{{ route('set_locale', 'en_US') . '?redirect=home' }}" class="text-gray-600 text-sm">English</a>
                    @else
                        <a href="{{ route('set_locale', 'ar_AR') . '?redirect=home' }}" class="text-gray-600 text-sm">عربي</a>
                    @endif
                </div>
            </div>
        </div>

        @stack('modals')

        @livewireScripts

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <!-- Page Scripts -->
        @yield('scripts')
    </body>
</html>
