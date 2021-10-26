<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-2 sm:px-4">
        <div class="flex justify-between h-12 sm:h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="w-12 sm:w-16 p-1 inline-flex items-center justify-center rounded-full">
                        <img src="{{ asset('logo.jpg') }}" alt="" class="w-full rounded-full">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-s-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    <x-jet-nav-link href="{{ route('home', app()->getLocale()) }}" :active="request()->routeIs('home')">
                        {{ translate('guests.home.home') }}
                    </x-jet-nav-link>

                    @if(auth()->user() && auth()->user()->role != 'user')
                        <x-jet-nav-link href="{{ route('entries', app()->getLocale()) }}" :active="request()->routeIs('entries')">
                            {{ translate('dashboard.entries.entries') }}
                        </x-jet-nav-link>

                        <x-jet-nav-link href="{{ route('users', app()->getLocale()) }}" :active="request()->routeIs('users')">
                            {{ translate('dashboard.entries.users') }}
                        </x-jet-nav-link>

                        @if(is_admin())
                            <x-jet-nav-link href="{{ route('user_fields', app()->getLocale()) }}" :active="request()->routeIs('user_fields')">
                                {{ translate('dashboard.user_fields.user_fields') }}
                            </x-jet-nav-link>

                            <x-jet-nav-link href="{{ route('settings', app()->getLocale()) }}" :active="request()->routeIs('settings')">
                                {{ translate('dashboard.settings.settings') }}
                            </x-jet-nav-link>

                            <x-jet-nav-link href="{{ route('advertisements.index', app()->getLocale()) }}" :active="request()->routeIs('advertisments.index')">
                                {{ translate('dashboard.advertisements.advertisements') }}
                            </x-jet-nav-link>
                        @endif
                    @endif
                </div>
            </div>

            @if(auth()->user())
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <x-jet-dropdown align="{{ lang() == 'ar_AR' ? 'left' : 'right' }}" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                            {{ Auth::user()->name }}

                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ translate('dashboard.profile.manage_account') }}
                                </div>

                                <x-jet-dropdown-link href="{{ route('profile.show', app()->getLocale()) }}">
                                    {{ translate('dashboard.profile.profile') }}
                                </x-jet-dropdown-link>

                                <div class="border-t border-gray-100"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-jet-dropdown-link href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                        {{ translate('dashboard.auth.logout') }}
                                    </x-jet-dropdown-link>
                                </form>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                </div>
            @else
                <div class="hidden sm:flex flex-row items-center h-full">
                    <x-jet-nav-link href="{{ route('login', app()->getLocale()) }}" class="text-gray-600 h-full">
                        {{ translate('dashboard.auth.login') }}
                    </x-jet-nav-link>

                    <span class="border-e-2 border-gray-300 h-1/3 me-2 ps-2"></span>

                    <x-jet-nav-link href="{{ route('register', app()->getLocale()) }}" class="text-gray-600 h-full">
                        {{ translate('dashboard.auth.register') }}
                    </x-jet-nav-link>
                </div>
            @endif

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('home', app()->getLocale()) }}" :active="request()->routeIs('home')">
                {{ translate('guests.home.home') }}
            </x-jet-responsive-nav-link>

            @if(auth()->user() && auth()->user()->role != 'user')
                <x-jet-responsive-nav-link href="{{ route('entries', app()->getLocale()) }}" :active="request()->routeIs('entries')">
                    {{ translate('dashboard.entries.entries') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('users', app()->getLocale()) }}" :active="request()->routeIs('users')">
                    {{ translate('dashboard.entries.users') }}
                </x-jet-responsive-nav-link>

                @if(is_admin())
                    <x-jet-responsive-nav-link href="{{ route('user_fields', app()->getLocale()) }}" :active="request()->routeIs('user_fields')">
                        {{ translate('dashboard.user_fields.user_fields') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('settings', app()->getLocale()) }}" :active="request()->routeIs('settings')">
                        {{ translate('dashboard.settings.settings') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('advertisements.index', app()->getLocale()) }}" :active="request()->routeIs('advertisements.index')">
                        {{ translate('dashboard.advertisements.advertisements') }}
                    </x-jet-responsive-nav-link>
                @endif
            @endif
        </div>

        <!-- Responsive Settings Options -->
        @if(auth()->user())
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-jet-responsive-nav-link href="{{ route('profile.show', app()->getLocale()) }}" :active="request()->routeIs('profile.show')">
                        {{ translate('dashboard.profile.profile') }}
                    </x-jet-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ translate('dashboard.auth.logout') }}
                        </x-jet-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="py-1 border-t border-gray-200">
                <div class="">
                    <x-jet-responsive-nav-link href="{{ route('login', app()->getLocale()) }}">
                        {{ translate('dashboard.auth.login') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('register', app()->getLocale()) }}">
                        {{ translate('dashboard.auth.register') }}
                    </x-jet-responsive-nav-link>
                </div>
            </div>
        @endif
    </div>
</nav>
