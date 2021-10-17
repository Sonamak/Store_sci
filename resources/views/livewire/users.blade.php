<div
    class="p-2"
    x-data="{
        isUsersModalVisible: false,
        isUserLoading: false
    }"
    x-on:action:close-modal.window="isUsersModalVisible = false"
    x-on:loading:user-loaded.window="isUserLoading = false"
>

    {{-- Top Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-5">
        {{-- Left Side --}}
        <div class="flex flex-col sm:flex-row w-full sm:w-auto">

            {{-- Search --}}
            <input type="search" class="form-input w-full sm:w-40 lg:w-60 border border-gray-400 shadow rounded py-1 h-9" placeholder="{{ translate('dashboard.common.search') }}" wire:model.debounce.500ms="searchTerm">

            {{-- Max Results --}}
            <select
                name="showMax"
                class="form-select w-full mt-4 sm:mt-0 sm:ms-2 w-auto border border-gray-400 shadow rounded py-1 h-9"
                wire:model="showMax"
            >
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>

            @if(!is_user())

                {{-- Account Type --}}
                <select
                    name="accountType"
                    class="form-select w-32 mt-4 sm:mt-0 sm:ms-2 w-auto border border-gray-400 shadow rounded py-1 h-9"
                    wire:model="accountType"
                >
                    <option value="">{{ translate('dashboard.users.active_accounts') }}</option>
                    <option value="deleted">{{ translate('dashboard.users.deleted_accounts') }}</option>
                </select>
            @endif
        </div>

        {{-- Right Side --}}
        <div class="flex flex-col sm:flex-row w-full sm:w-auto">
            @if(!is_user() && empty($accountType))
                <div class="mt-4 sm:mt-0 w-full inline-flex flex-row justify-center items-center rounded shadow h-9">
                    
                    {{-- Create User Button --}}
                    <a
                        href="{{ route('users_create', app()->getLocale()) }}"
                        class="m-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded rou py-2 px-6 border-indigo-400 text-white w-full inline-flex items-center justify-center transition duration-300ms ease-in-out"
                    >
                        {{ translate('dashboard.users.create_user') }}
                    </a>

                    {{-- More Option Button --}}
                    {{-- <div class="relative">
                        <x-jet-dropdown align="{{ lang() == 'ar_AR' ? 'left' : 'right' }}" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="m-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded-e py-2 px-2 border-indigo-400 text-white inline-flex items-center justify-center transition duration-300ms ease-in-out"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-jet-dropdown-link href="#" wire:click.prevent="exportDatabase()">
                                    {{ translate('dashboard.entries.export_database') }}
                                </x-jet-dropdown-link>
                            </x-slot>
                        </x-jet-dropdown>
                    </div> --}}
                </div>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    @if($total_users > $showMax)
    <div>
        <div class="my-5">
            {{ $users->onEachSide(5)->links() }}
        </div>
    </div>
    @endif

    {{-- Users table --}}
    <div class="w-full overflow-auto">
        <table class="w-full table table-auto border rounded">
            <thead>
                <tr class="bg-indigo-400 text-white" wire:ignore>
                    <th class="border sortable-header" data-sortable="id">{{ translate('dashboard.entries.id') }}</th>
                    <th class="border sortable-header" data-sortable="name">{{ translate('dashboard.users.name') }}</th>
                    <th class="border sortable-header" data-sortable="email">{{ translate('dashboard.users.email') }}</th>
                    <th class="border">{{ translate('dashboard.users.phone') }}</th>
                    <th class="border sortable-header" data-sortable="country">{{ translate('dashboard.users.country') }}</th>
                    <th class="border sortable-header" data-sortable="city">{{ translate('dashboard.users.city') }}</th>
                    <th class="border">{{ translate('dashboard.users.educational_attainment') }}</th>
                    <th class="border">{{ translate('dashboard.users.general_specialization') }}</th>
                    <th class="border">{{ translate('dashboard.users.specialization') }}</th>
                    <th class="border sortable-header" data-sortable="role">{{ translate('dashboard.users.role') }}</th>
                    <th class="border sortable-header" data-sortable="download_count">{{ translate('dashboard.entries.download_count') }}</th>
                    <th class="border sortable-header" data-sortable="whatsapp_count">{{ translate('dashboard.entries.whatsapp_count') }}</th>
                    <th class="border sortable-header" data-sortable="created_at">{{ translate('dashboard.users.registered_on') }}</th>
                    <th class="border">{{ translate('dashboard.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($users) && count($users))
                    @foreach($users as $user)
                        <tr class="border hover:bg-indigo-50 transition duration-300ms ease-in-out">
                            <td class="border">{{ $user->id }}</td>
                            <td class="border">
                                {{ $user->name }}
                                @if($user->id == auth()->id())
                                    <span class="bg-red-400 text-white px-2 py-1 text-xs rounded-full">
                                        YOU
                                    </span>
                                @endif
                            </td>
                            <td class="border">{{ $user->email }}</td>
                            <td class="border">{{ $user->phone }}</td>
                            <td class="border capitalize">{{ $user->country_name ?? '-' }}</td>
                            <td class="border">{{ $user->city ?? '-' }}</td>
                            <td class="border">{{ $user->educationalAttainment->label ?? '-' }}</td>
                            <td class="border">{{ $user->generalSpecialization->label ?? '-'}}</td>
                            <td class="border">{{ $user->specialization->label ?? '-' }}</td>
                            <td class="border capitalize font-bold">
                                @if(is_admin($user))
                                    <span class="text-red-500">
                                        {{ $user->role }}
                                    </span>
                                @elseif(is_supervisor($user))
                                    <span class="text-green-500">
                                        {{ $user->role }}
                                    </span>
                                @else
                                    <span class="text-gray-700">
                                        {{ $user->role }}
                                    </span>
                                @endif
                            </td>
                            <td class="border">{{ $user->download_count }}</td>
                            <td class="border">{{ $user->whatsapp_count }}</td>
                            <td class="border">{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                            <td class="border">
                                <div class="inline-flex justify-center items-center">
                                    @if(
                                        !is_user()
                                        && (
                                            (
                                                auth()->user()->role == 'admin'
                                                && ($user->role == 'admin' || $user->role == 'supervisor' || $user->role == 'user')
                                            )
                                            || (
                                                auth()->user()->role == 'supervisor'
                                                && ($user->role == 'supervisor' || $user->role == 'user')
                                            )
                                        )
                                    )
                                        @if($user->id != auth()->id())
                                            
                                            {{-- Edit --}}
                                            <a
                                                href="{{ route('user_edit', ['locale' => app()->getLocale(), 'user_id' => $user->id]) }}" 
                                                class="inline-flex rounded-full p-2 bg-gray-400 hover:bg-gray-500 text-white focus:outline-none"
                                                title="Edit user" >
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if(empty($user->deleted_at))
                                            @if($user->id != auth()->id())

                                                {{-- Block --}}
                                                <button 
                                                    class="inline-flex rounded-full p-2 bg-yellow-500 hover:bg-yellow-600 text-white ms-3 focus:outline-none"
                                                    title="Block user"
                                                    onclick="event.preventDefault(); blockUser('{{ $user->id }}');"
                                                >
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                </button>
                                            @endif
                                        @else

                                            {{-- Restore --}}
                                            <button 
                                                class="inline-flex rounded-full p-2 bg-purple-500 hover:bg-purple-600 text-white ms-3 focus:outline-none"
                                                title="Restore user"
                                                onclick="event.preventDefault(); restoreUser('{{ $user->id }}');"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                            </button>
                                        @endif

                                        @if($user->id != auth()->id())
                                        
                                            {{-- Delete --}}
                                            <button 
                                                class="inline-flex rounded-full p-2 bg-red-500 hover:bg-red-600 text-white ms-3 focus:outline-none"
                                                title="Delete user"
                                                onclick="event.preventDefault(); deleteUser('{{ $user->id }}');"
                                            >
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="20">
                            <h1 class="text-center text-red-500 text-lg sm:text-2xl my-5">{{ translate('messages.no_user_to_show') }}</h1>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($total_users > $showMax)
        <div>
            <div class="mt-5">
                {{ $users->onEachSide(5)->links() }}
            </div>
        </div>
    @endif
</div>

@section('scripts')
    <script type="text/javascript">
        // Listen to events
        window.addEventListener('action:reset-fields', function() {
            resetFields();
        });

        // Functions
        const resetFields = function() {
            const $form = document.getElementById('user-form');

            $form.reset();
        };

        // Block user
        const blockUser = function(user_id = null) {
            const $button = event.target;

            Swal.fire({
                title: "{{ translate('buttons.confirm') }}",
                text: "{{ translate('messages.block_this_user') }}",
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
                    window.livewire.emit('action:block-user', user_id);
                }
            })
        }

        // Block user
        const deleteUser = function(user_id = null) {
            const $button = event.target;

            Swal.fire({
                title: "{{ translate('buttons.confirm') }}",
                text: "{{ translate('messages.delete_this_user') }}",
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
                    window.livewire.emit('action:delete-user', user_id);
                }
            })
        }

        // Restore user
        const restoreUser = function(user_id = null) {
            const $button = event.target;

            Swal.fire({
                title: "{{ translate('buttons.confirm') }}",
                text: "{{ translate('messages.restore_this_user') }}",
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
                    window.livewire.emit('action:restore-user', user_id);
                }
            })
        }
    </script>
@stop
