<div
    class="p-2"
    x-data="{
        isEntriesModalVisible: false,
        isEntryLoading: false,
        changingPrivacyTo: null,
        isUploading: false,
        progress: 10,
    }"
    x-on:action:close-modal.window="isEntriesModalVisible = false"
    x-on:loading:entry-loaded.window="isEntryLoading = false"
    x-on:status:privacy-changed.window="changingPrivacyTo = null"
    x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
>
    <div class="flex flex-col xl:flex-row justify-between items-center mb-5">
        {{-- Left Side --}}
        <div class="flex flex-col xl:flex-row w-full xl:w-auto">

            {{-- Search --}}
            <input type="search" class="form-input w-full xl:w-40 xl:w-60 border border-gray-400 shadow rounded py-1 h-9" placeholder="{{ translate('dashboard.common.search') }}" wire:model.debounce.500ms="searchTerm">

            {{-- Max Results --}}
            <select
                name="showMax"
                class="form-select w-full mt-4 xl:mt-0 xl:ms-2 w-auto border border-gray-400 shadow rounded py-1 h-9"
                wire:model="showMax"
            >
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>

            @if(!is_user())
                {{-- Entry Type --}}
                <select
                    name="entryType"
                    class="form-select w-32 mt-4 xl:mt-0 xl:ms-2 w-auto border border-gray-400 shadow rounded py-1 h-9"
                    wire:model="entryType"
                >
                    <option value="">{{ translate('dashboard.entries.active_entries') }}</option>
                    <option value="deleted">{{ translate('dashboard.entries.deleted_entries') }}</option>
                </select>

                {{-- All Private --}}
                <button
                    class="mt-4 xl:mt-0 xl:ms-2 bg-red-500 hover:bg-red-600 focus:outline-none rounded py-2 px-6 border-red-500 text-white w-full inline-flex items-center justify-center whitespace-nowrap h-9 transition duration-300 ease-in-out"
                    wire:click="makeAllPrivate"
                    x-on:click="changingPrivacyTo = 'private'"
                    x-show="changingPrivacyTo === null || changingPrivacyTo === 'private'"
                >
                    <span x-show="changingPrivacyTo === null">{{ translate('buttons.make_all_private') }}</span>

                    <svg x-show="changingPrivacyTo === 'private'" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-loader w-7 h-7 animate-spin" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="12" y1="6" x2="12" y2="3" />
                        <line x1="16.25" y1="7.75" x2="18.4" y2="5.6" />
                        <line x1="18" y1="12" x2="21" y2="12" />
                        <line x1="16.25" y1="16.25" x2="18.4" y2="18.4" />
                        <line x1="12" y1="18" x2="12" y2="21" />
                        <line x1="7.75" y1="16.25" x2="5.6" y2="18.4" />
                        <line x1="6" y1="12" x2="3" y2="12" />
                        <line x1="7.75" y1="7.75" x2="5.6" y2="5.6" />
                    </svg>
                </button>

                {{-- All Public --}}
                <button
                    class="mt-4 xl:mt-0 xl:ms-2 bg-green-500 hover:bg-green-600 focus:outline-none rounded py-2 px-6 border-green-500 text-white w-full inline-flex items-center justify-center whitespace-nowrap h-9 transition duration-300 ease-in-out"
                    wire:click="makeAllPublic"
                    x-on:click="changingPrivacyTo = 'public'"
                    x-show="changingPrivacyTo === null || changingPrivacyTo === 'public'"
                >
                    <span x-show="changingPrivacyTo === null">{{ translate('buttons.make_all_public') }}</span>

                    <svg x-show="changingPrivacyTo === 'public'" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-loader w-7 h-7 animate-spin" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="12" y1="6" x2="12" y2="3" />
                        <line x1="16.25" y1="7.75" x2="18.4" y2="5.6" />
                        <line x1="18" y1="12" x2="21" y2="12" />
                        <line x1="16.25" y1="16.25" x2="18.4" y2="18.4" />
                        <line x1="12" y1="18" x2="12" y2="21" />
                        <line x1="7.75" y1="16.25" x2="5.6" y2="18.4" />
                        <line x1="6" y1="12" x2="3" y2="12" />
                        <line x1="7.75" y1="7.75" x2="5.6" y2="5.6" />
                    </svg>
                </button>
            @endif
        </div>
        {{-- Right Side --}}
        <div class="flex flex-col xl:flex-row w-full xl:w-auto">
            @if(!is_user() && empty($entryType))
                <div class="mt-4 xl:mt-0 w-full inline-flex flex-row justify-center items-center rounded shadow h-9">
                    
                    {{-- Upload Button --}}
                    <button
                        class="m-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded-s py-2 px-6 border-indigo-400 text-white w-full inline-flex items-center justify-center transition duration-300 ease-in-out"
                        wire:click="openEntry(null)"
                        x-on:click="isEntriesModalVisible = true; isEntryLoading = true;"
                    >
                        {{ translate('dashboard.entries.upload_file') }}
                    </button>

                    {{-- More Option Button --}}
                    <div class="relative">
                        <x-jet-dropdown align="{{ lang() == 'ar_AR' ? 'left' : 'right' }}" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="m-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded-e py-2 px-2 border-indigo-400 text-white inline-flex items-center justify-center transition duration-300 ease-in-out"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-jet-dropdown-link 
                                href="{{ route('export.entries', app()->getLocale()) }}">
                                    {{ translate('dashboard.common.export_database') }}
                                </x-jet-dropdown-link>

                                <a 
                                id='import-btn'
                                class='block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition'
                                href="#">Import</a>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    {{-- Pagination --}}
    @if($total_entries > $showMax)
    <div>
        <div class="my-5">
            {{ $entries->onEachSide(5)->links() }}
        </div>
    </div>
    @endif

    {{-- Entries table --}}
    <div class="w-full overflow-auto">
        <table class="w-full table table-auto border rounded">
            <thead>
                <tr class="bg-indigo-400 text-white" wire:ignore>
                    <th class="border sortable-header" data-sortable="id">{{ translate('dashboard.entries.id') }}</th>
                    <th class="border sortable-header" data-sortable="name">{{ translate('dashboard.entries.name') }}</th>
                    <th class="border">{{ translate('dashboard.entries.file_type') }}</th>
                    <th class="border sortable-header" data-sortable="is_private">{{ translate('dashboard.entries.privacy') }}</th>
                    <th class="border sortable-header" data-sortable="download_count">{{ translate('dashboard.entries.download_count') }}</th>
                    <th class="border sortable-header" data-sortable="whatsapp_count">{{ translate('dashboard.entries.whatsapp_count') }}</th>
                    <th class="border sortable-header" data-sortable="created_at">{{ translate('dashboard.entries.uploaded_at') }}</th>
                    <th class="border">{{ translate('dashboard.entries.uploaded_by') }}</th>
                    <th class="border">{{ translate('dashboard.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($entries) && count($entries))
                    @foreach($entries as $entry)
                        <tr class="border hover:bg-indigo-50 transition duration-300 ease-in-out">
                            <td class="border">{{ $entry->id }}</td>
                            <td class="border" style="white-space: unset;">
                                <p class="w-60">{{ $entry->name }}</p>
                            </td>
                            <td class="border uppercase">{{ $entry->is_private ? '-' : $entry->file_type ?? '-' }}</td>
                            <td class="border font-bold {{ $entry->is_private ? 'text-red-500' : 'text-green-500' }}">
                                {{ $entry->is_private ? 'Private' : 'Public' }}
                            </td>
                            <td class="border">{{ $entry->download_count }}</td>
                            <td class="border">{{ $entry->whatsapp_count }}</td>
                            <td class="border">{{ date('d/m/Y h:i A', strtotime($entry->created_at)) }}</td>
                            <td class="border">{{ $entry->uploader->name ?? '-' }}</td>
                            <td class="border">
                                <div class="inline-flex justify-center items-center">
                                    {{-- Download --}}
                                    @if(empty($entry->deleted_at))
                                        @if(!$entry->is_private && $entry->attachment_url)
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
                                        @elseif($entry->is_private)
                                            <a 
                                                href="https://api.whatsapp.com/send/?phone={{ $whatsapp }}&text={{ $entry->whatsapp_message }}"
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

                                    @if(!is_user())
                                        {{-- Edit --}}
                                        <button 
                                            class="inline-flex rounded-full p-2 bg-gray-400 hover:bg-gray-500 text-white ms-3 focus:outline-none"
                                            title="Edit entry"
                                            x-on:click="isEntriesModalVisible = true; isEntryLoading = true;"
                                            wire:click="openEntry({{ $entry->id }})"
                                        >
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        @if(empty($entry->deleted_at))

                                            {{-- Delete --}}
                                            <button 
                                                class="inline-flex rounded-full p-2 bg-red-500 hover:bg-red-600 text-white ms-3 focus:outline-none"
                                                title="Delete entry"
                                                onclick="event.preventDefault(); deleteEntry('{{ $entry->id }}');"
                                            >
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @else

                                            {{-- Restore --}}
                                            <button 
                                                class="inline-flex rounded-full p-2 bg-purple-500 hover:bg-purple-600 text-white ms-3 focus:outline-none"
                                                title="Restore entry"
                                                onclick="event.preventDefault(); restoreEntry('{{ $entry->id }}');"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Permanently Delete --}}
                                        <button 
                                            class="inline-flex rounded-full p-2 bg-red-500 hover:bg-red-600 text-white ms-3 focus:outline-none"
                                            title="Permanently delete entry"
                                            onclick="event.preventDefault(); forceDeleteEntry('{{ $entry->id }}');"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-shredder h-5 w-5" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                                <line x1="3" y1="12" x2="21" y2="12" />
                                                <line x1="6" y1="16" x2="6" y2="18" />
                                                <line x1="10" y1="16" x2="10" y2="22" />
                                                <line x1="14" y1="16" x2="14" y2="18" />
                                                <line x1="18" y1="16" x2="18" y2="20" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">
                            <h1 class="text-center text-red-500 text-lg sm:text-2xl my-5">{{ translate('messages.no_entry_to_show') }}</h1>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($total_entries > $showMax)
        <div>
            <div class="mt-5">
                {{ $entries->onEachSide(5)->links() }}
            </div>
        </div>
    @endif

    {{-- Upload Modal --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 z-30" x-cloak x-show.transition.opacity="isEntriesModalVisible">
        <div class="h-full flex flex-col sm:justify-center items-center pt-0" x-show.transition.scale="isEntriesModalVisible">
            <div class="h-full sm:h-auto w-full sm:max-w-md p-4 border bg-gray-50 shadow-md overflow-auto sm:rounded-lg">
    
                <!-- Modal Header -->
                <div class="flex flex-row justify-between items-center mb-5">
                    <h4 class="text-lg text-gray-700">{{ empty($entry_id) ? translate('dashboard.entries.create_entry') : translate('dashboard.entries.update_entry') }}</h4>
                    <button class="text-gray-500 hover:text-gray-800" @click.prevent="isEntriesModalVisible = false">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
    
                <!-- Modal Body -->
                <div class="flex flex-col">
                    <form
                        action="javascript:void(0)"
                        method="post"
                        wire:submit.prevent="createOrUpdateEntry({{ $entry_id ?? null }})"
                        x-show="!isEntryLoading"
                        id="entry-form"
                    >
                        @csrf
    
                        <div class="form-group">
                            <label for="" class="text-gray-700 mb-2">{{ translate('dashboard.entries.name') }}</label>
                            <input type="text" class="form-input p-2 rounded border border-gray-300 shadow w-full focus:outline-none focus:ring focus:border-blue-300" name="name" wire:model.defer="name" placeholder="Daniel's Passport">
                            @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mt-4">
                            <label for="" class="text-gray-700 mb-2">{{ translate('dashboard.entries.file') }}</label>
                            <input type="file" class="form-input p-2 rounded border border-gray-300 shadow w-full focus:outline-none focus:ring focus:border-blue-300" name="attachment_path" wire:model.defer="attachment_path">
                            @error('attachment_path') <span class="error text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mt-4">
                            <label for="private_checkbox" class="text-gray-700 mb-2 cursor-pointer">
                                <input type="checkbox" class="" id="private_checkbox" name="is_private" wire:model.defer="is_private">
                                {{ translate('dashboard.entries.make_file_private') }}
                            </label>
                        </div>
    
                        <div class="form-group mt-5 text-end">
                            <span class="text-green-500" x-show="isUploading" x-text="progress + '%'"></span>

                            <button
                                class="mt-4 sm:mt-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded py-2 px-6 shadow border-indigo-400 text-white h-9 inline-flex items-center justify-center transition duration-300 ease-in-out"
                                x-bind:disabled="isUploading"
                            >
                                {{ empty($entry_id) ? translate('buttons.upload') : translate('buttons.update') }}
                            </button>
                        </div>
                    </form>

                    <div class="flex justify-center items-center p-4" x-show="isEntryLoading">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 animate-spin icon icon-tabler icon-tabler-loader" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="12" y1="6" x2="12" y2="3" />
                            <line x1="16.25" y1="7.75" x2="18.4" y2="5.6" />
                            <line x1="18" y1="12" x2="21" y2="12" />
                            <line x1="16.25" y1="16.25" x2="18.4" y2="18.4" />
                            <line x1="12" y1="18" x2="12" y2="21" />
                            <line x1="7.75" y1="16.25" x2="5.6" y2="18.4" />
                            <line x1="6" y1="12" x2="3" y2="12" />
                            <line x1="7.75" y1="7.75" x2="5.6" y2="5.6" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<div id='import-modal' class="hidden z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all  sm:align-middle sm:max-w-lg w-full">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          

          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
              Import Data
            </h3>
            <div class="mt-2">
              
                <form 
                class='w-full'
                id='upload-form'
                method='POST' 
                action="{{ route('testing', app()->getLocale()) }}" 
                enctype='multipart/form-data'>
                @csrf 
                    <input class='border py-2 px-3 text-grey-darkest w-full' type="file" name='file'>
                </form>

            </div>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button 
        onclick="event.preventDefault();document.querySelector('#upload-form').submit();"
        type="button" 
        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
          Upload
        </button>
        <button id='cancel-modal' type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>


@section('scripts')
@if(auth()->user()->role == 'admin')
    <script type="text/javascript">

        document.getElementById("import-btn").addEventListener("click", function(e){
            e.preventDefault();
            document.getElementById("import-modal").classList.remove("hidden");
            document.getElementById("import-modal").classList.add("fixed");
        });

        document.getElementById("cancel-modal").addEventListener("click", function(e){
            e.preventDefault();
            document.getElementById("import-modal").classList.remove("fixed");
            document.getElementById("import-modal").classList.add("hidden");
        });

    </script>
@endif

    <script type="text/javascript">
        // Listen to events
        window.addEventListener('action:reset-fields', function() {
            resetFields();
        });

        // Functions
        const resetFields = function() {
            const $form = document.getElementById('entry-form');

            $form.reset();
        };

        // Delete entry
        const deleteEntry = function(entry_id = null) {
            const $button = event.target;

            Swal.fire({
                title: "{{ translate('buttons.confirm') }}",
                text: "{{ translate('messages.delete_this_entry') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ translate('buttons.yes_continue') }}",
                cancelButtonText: "{{ translate('buttons.cancel')}}",
                customClass: {
                    confirmButton: 'swal-danger',
                }
            }).then((confirm) => {
                if(confirm.isConfirmed) {
                    $button.setAttribute('disabled', true);

                    // Emit livewire event
                    window.livewire.emit('action:delete-entry', entry_id);
                }
            })
        }

        // Restore entry
        const restoreEntry = function(entry_id = null) {
            const $button = event.target;

            Swal.fire({
                title: "{{ translate('buttons.confirm') }}",
                text: "{{ translate('messages.restore_this_entry') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ translate('buttons.yes_continue') }}",
                cancelButtonText: "{{ translate('buttons.cancel')}}",
                customClass: {
                    confirmButton: 'swal-danger',
                }
            }).then((confirm) => {
                if(confirm.isConfirmed) {
                    $button.setAttribute('disabled', true);

                    // Emit livewire event
                    window.livewire.emit('action:restore-entry', entry_id);
                }
            })
        }

        // Force delete entry
        const forceDeleteEntry = function(entry_id = null) {
            const $button = event.target;

            Swal.fire({
                title: "{{ translate('buttons.confirm') }}",
                text: "{{ translate('messages.permanently_delete_this_entry') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ translate('buttons.yes_continue') }}",
                cancelButtonText: "{{ translate('buttons.cancel')}}",
                customClass: {
                    confirmButton: 'swal-danger',
                }
            }).then((confirm) => {
                if(confirm.isConfirmed) {
                    $button.setAttribute('disabled', true);

                    // Emit livewire event
                    window.livewire.emit('action:force-delete-entry', entry_id);
                }
            });
        }
    </script>

<script src="https://creatantech.com/demos/codervent/rocker/vertical/assets/js/jquery.min.js"></script>
<script>
    $(function(){

        $(".close-ad-btn").on("click", (e) => {
            $(e.target).parents("#advertisement_modal").fadeOut();
        });

        let formData = new FormData();
        let _token = $("[name='csrf-token']").attr('content');
        formData.append('_token', _token);
        let ad_id;

        $.ajax({
            url: "{{ route('check_user_ads') }}",
            method: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response){
                
                if(response.ad !== null)
                {
                    ad_id = response.ad.id;
                    let title = response.ad.title;
                    let description = response.ad.description;
                    let image = response.ad.image;

                    $("#advertisement_modal .title").text(title);
                    $("#advertisement_modal .description").text(description);
                    $("#advertisement_modal .vertise-image").attr('src', '/storage/' + image);
            
                    $("#advertisement_modal").removeClass("hidden");
                    
                    formData.append('ad_id', ad_id);
                    $.ajax({
                        url: "{{ route('check_ad_seen') }}",
                        method: 'post',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response){
                            console.log(response);
                        }
                    });
                }
            }
        })


    });
</script>
@stop