<div
    class="flex flex-col h-full"
    x-data="{
        isTyping: false,
        searchTerm: '',
        guestAllowed: '{{ $guestAllowed }}',
    }"
    x-on:load:search-finish.window="isTyping = false"
>
    
    {{-- Search bar --}}
    <div
        class="w-full flex flex-col justify-center items-center p-3 transition-all duration-300 ease-in-out"
        style="
            background-image: url('{{ asset('images/home-bg.jpg') }}');
            background-position: center 80%;
            background-repeat: no-repeat;
            background-size: cover;
        "
        x-model="searchTerm"
        x-bind:class="{
            'h-32 sm:h-48': guestAllowed && searchTerm.length >= 3,
            'h-60 sm:h-96': !guestAllowed || searchTerm.length < 3
        }"
    >
        <h3 class="text-white text-4xl mb-5 font-comfortaa">{{ env('APP_NAME') }}</h3>

        <input
            type="text"
            id="search"
            name="search"
            class="px-5 py-2 text-lg sm:text-2xl rounded shadow text-gray-800 bg-white bg-opacity-70 bg-blur-5 border border-gray-100 w-full sm:w-2/3 lg:w-1/2 font-comfortaa"
            placeholder="{{ translate('dashboard.common.search') }}"
            autocomplete="off"
            wire:model.debounce.500ms="searchTerm"
            x-on:input="isTyping = (guestAllowed && $el.value.length > 2) ? true : false"
        >
    </div>

    {{-- Entries List --}}
    <div class="rounded-lg mt-10 mb-16 w-full mx-auto p-4" x-show="isTyping">
        <h2 class="text-center text-3xl text-gray-600 my-20 font-comfortaa">
            {{ translate('guests.home.searching') }}
        </h2>
    </div>
    <div class="rounded-lg mt-10 {{ $total_entries > $showMax ? 'mb-16' : '' }} w-full mx-auto p-4" x-show="!isTyping">
        @if(isset($entries) && count($entries))
            <h3 class="text-gray-700 text-xl font-comfortaa mb-3">{{ translate('guests.home.search_results') }}</h3>

            <div class="w-full overflow-auto border">
                <table class="w-full table table-auto border rounded">
                    <thead>
                        <tr class="bg-indigo-400 text-white" wire:ignore>
                            <th class="border">{{ translate('dashboard.entries.name') }}</th>
                            <th class="border">{{ translate('dashboard.entries.file_type') }}</th>
                            <th class="border">{{ translate('dashboard.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $entry)
                            <tr class="border hover:bg-indigo-50 transition duration-300ms ease-in-out">
                                <td class="border">{{ $entry->name }}</td>
                                <td class="border uppercase">{{ $entry->is_private ? '-' : $entry->file_type ?? '-' }}</td>
                                <td class="border">
                                    <div class="inline-flex justify-center items-center">
                                        {{-- Download --}}
                                        @if(empty($entry->deleted_at))
                                            @if(!$entry->is_private && $entry->attachment_url)
                                                @if($guestAllowed)
                                                    <a 
                                                        href="{{ $entry->attachment_url ?? 'javascript:void(0)' }}"
                                                        target="_blank"
                                                        class="inline-flex rounded-full p-2 bg-blue-400 hover:bg-blue-500 text-white focus:outline-none"
                                                        title="Download entry"
                                                        wire:click="downloadEntry({{ $entry->id }})"
                                                    >
                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <a 
                                                        href="javascript:void(0)"
                                                        class="inline-flex rounded-full p-2 bg-blue-400 hover:bg-blue-500 text-white focus:outline-none"
                                                        title="Download entry"
                                                        onclick="event.preventDefault(); guestPopup()"
                                                    >
                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            @elseif($entry->is_private)
                                                @if($guestAllowed)
                                                    <a 
                                                        href="https://api.whatsapp.com/send/?phone={{ $whatsapp }}&text={{ $entry->name }}"
                                                        target="_blank"
                                                        class="inline-flex rounded-full p-2 bg-green-400 hover:bg-green-500 text-white focus:outline-none"
                                                        title="Send Message"
                                                        wire:click="whatsappEntry({{ $entry->id }})"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-whatsapp w-5 h-5" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                                            <path d="M9 10a0.5 .5 0 0 0 1 0v-1a0.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a0.5 .5 0 0 0 0 -1h-1a0.5 .5 0 0 0 0 1" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <a
                                                        href="javascript:void(0)"
                                                        class="inline-flex rounded-full p-2 bg-green-400 hover:bg-green-500 text-white focus:outline-none"
                                                        title="Send Message"
                                                        onclick="event.preventDefault(); guestPopup()"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-whatsapp w-5 h-5" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                                            <path d="M9 10a0.5 .5 0 0 0 1 0v-1a0.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a0.5 .5 0 0 0 0 -1h-1a0.5 .5 0 0 0 0 1" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            @elseif(!$entry->attachment_url)
                                                <span class="cursor-not-allowed" title="Broken Link">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 icon icon-tabler icon-tabler-unlink" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ef4444" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" />
                                                        <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" />
                                                        <line x1="16" y1="21" x2="16" y2="19" />
                                                        <line x1="19" y1="16" x2="21" y2="16" />
                                                        <line x1="3" y1="8" x2="5" y2="8" />
                                                        <line x1="8" y1="3" x2="8" y2="5" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif(strlen($searchTerm) > 2)
            <h2 class="text-center text-3xl text-gray-600 my-20 font-comfortaa">
                {{ translate('guests.home.no_result_found_for') }} <span class="font-bold">{{ $searchTerm }}</span>
            </h2>
        @elseif(empty($searchTerm) || strlen($searchTerm) <= 2)
            <h2 class="text-center text-3xl text-gray-600 my-20 font-comfortaa">
                {{ translate('guests.home.search_something') }}
            </h2>
        @endif
    </div>

    {{-- Pagination --}}
    @if($total_entries > $showMax)
        <div class="p-4 {{ $total_entries > $showMax ? 'mb-16' : '' }}">
            <div class="mt-5">
                {{ $entries->onEachSide(5)->links() }}
            </div>
        </div>
    @endif

    @section('scripts')
        <script type="text/javascript">
            const guestPopup = function() {
                Swal.fire({
                    text: "{{ translate('guests.home.guests_not_allowed') }}",
                    icon: 'info',
                    confirmButtonText: "{{ translate('buttons.got_it') }}",
                });
            }
        </script>
    @stop
</div>