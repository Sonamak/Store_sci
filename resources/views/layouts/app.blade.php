<!DOCTYPE html>
<html lang="<?= explode('_', lang())[0] ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="{{ asset('logo.jpg') }}" type="image/jpg">

        <title>{{ config('app.name', 'Laravel') }} - {{ $title }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('libs/notifier/css/notifier.css') }}">

        @livewireStyles

        <!-- Page Styles -->
        @yield('styles')
    </head>
    <body class="font-sans antialiased" dir="<?= explode('_', lang())[0] == 'ar' ? 'rtl' : 'ltr' ?>">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            <livewire:navigation-menu />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-3 sm:py-6 px-2 sm:px-4">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="{{ asset('js/main.js') }}"></script>
        <script src="{{ asset('libs/notifier/js/notifier.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script type="text/javascript">
            // Axios Config
            const axiosIns = axios.create({
                baseURL: '{{ env('APP_URL') }}/api/',
                headers: {
                    Authorization: 'Bearer {{ auth()->user()->api_token }}',
                    Accept: 'application/json',
                }
            });

            // Global livewire event listener
            window.addEventListener('livewire-event', response => {
                response = response.detail;

                if (!response.success) {
                    notifier.show('Oops', response.message, 'danger', '', 7000);
                    return true;
                }

                notifier.show('Success', response.message, 'success', '', 7000);
                if (typeof response.redirect !== 'undefined') {
                    setTimeout(_ => {
                        window.location.href = response.redirect;
                    }, 2000);
                }
            });
        </script>

        <!-- Page Scripts -->
        @yield('scripts')
    </body>
</html>
