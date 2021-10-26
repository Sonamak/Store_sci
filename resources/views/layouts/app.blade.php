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
        <link rel="stylesheet" href="{{ asset('libs/notifier/css/notifier.css') }}">
        <link rel="stylesheet" href="{{ asset('css/my_style.css') }}">
        @livewireStyles

        <!-- Page Styles -->
        @yield('styles')
    </head>
    <body class="font-sans antialiased" dir="<?= explode('_', lang())[0] == 'ar' ? 'rtl' : 'ltr' ?>">

        @if(Session::has('success'))
            <div class='global-message bg-blue-400 border border-blue-400 px-4 py-3 rounded'>{{ Session::get('success') }}</div>
        @endif

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

<!-- Ad -->
@if(auth()->user()->role !== 'admin')
<div class="hidden min-w-screen h-screen animated fadeIn faster fixed left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover"  style="background-color: rgba(0,0,0,.5);" id="advertisement_modal">
   	<div class="absolute bg-black opacity-80 inset-0 z-0"></div>
<div class="relative w-2/4	min-h-screen flex flex-col items-center justify-center "> 
    <div class="grid mt-8 w-full gap-8 grid-cols-1 md:grid-cols-1 xl:grid-cols-1">
        
        <div class="flex flex-col ">
            <div class="bg-white shadow-md" style='border-radius: 0; height:400px;'>
                <div class="flex-none lg:flex" style='height:100%;'>
                    
                    <div class="w-1/2 h-full lg:mb-0 mb-3">
                        <img style='height: 100%;' src="https://images.unsplash.com/photo-1585399000684-d2f72660f092?ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1951&amp;q=80"
                            alt="Just a flower" class="vertise-image w-full  object-scale-down lg:object-cover  lg:h-48">
                    </div>

                    <div class="w-1/2 flex-auto ml-3 justify-evenly p-4" style='position: relative;'>
                        <div class="flex flex-wrap ">
                            <h2 class="flex-auto text-lg font-medium title">Umbrella Corporation</h2>
                        </div>
                        <p class="mt-3"></p>
                        <div class="flex py-4  text-sm text-gray-500">
                            <div class="flex-1 inline-flex items-center description break-all">
                                Lorem, ipsum dolor. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit, earum.
                            </div>
                        </div>
                        <div class="flex p-4 pb-2 border-t border-gray-200 "></div>
                        <div class="flex space-x-3 text-sm font-medium" style='position: absolute;
    bottom: 3%;
    right: 5%;'>

                            <div class="flex-auto flex space-x-3"></div>

                            <button
                                class="close-ad-btn mb-2 md:mb-0 bg-gray-900 px-5 py-2 shadow-sm tracking-wider text-white rounded-full inline-flex hover:bg-gray-800"
                                type="button" aria-label="like">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- End Ad -->
@endif

        <!-- Page Scripts -->
        @yield('scripts')
    </body>


</html>
