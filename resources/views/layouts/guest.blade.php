<!DOCTYPE html>
<html lang="<?= explode('_', lang())[0] ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="{{ asset('logo.jpg') }}" type="image/jpg">

        <title>{{ translate('dashboard.global.app_name') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased" dir="<?= explode('_', lang())[0] == 'ar' ? 'rtl' : 'ltr' ?>">
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        <!-- Page Scripts -->
        @yield('scripts')
    </body>
</html>
