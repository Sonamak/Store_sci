<div
    class="p-2"
    x-data="{
        isModalVisible: false,
        isLoading: false
    }"
    x-on:action:close-modal.window="isModalVisible = false"
    x-on:loading:row-loaded.window="isLoading = false"
>
    {{-- Table --}}
    <h1 class="text-xl mb-3 text-red-500 font-bold">{{ translate('dashboard.users.' . $field) }}</h1>

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
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
            </select>
        </div>

        {{-- Right Side --}}
        <div class="flex flex-col sm:flex-row w-full sm:w-auto">
            @if(is_admin())
                <div class="mt-4 sm:mt-0 w-full inline-flex flex-row justify-center items-center rounded shadow h-9">
                    
                    {{-- Add Row Button --}}
                    <button
                        class="m-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded py-2 px-6 border-indigo-400 text-white w-full inline-flex items-center justify-center transition duration-300ms ease-in-out"
                        wire:click="openRow(null)"
                        x-on:click="isModalVisible = true; isLoading = true;"
                    >
                        {{ translate('dashboard.user_fields.add') . ' ' . translate('dashboard.users.' . $field) }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="w-full overflow-auto">
        <table class="w-full table table-auto border rounded">
            <thead>
                <tr class="bg-indigo-400 text-white" wire:ignore>
                    <th class="border">{{ translate('dashboard.user_fields.label') }}</th>
                    <th class="border {{ $field == 'specialization' ? '' : 'hidden' }}">{{ translate('dashboard.user_fields.general_specialization') }}</th>
                    <th class="border">{{ translate('dashboard.common.created_on') }}</th>
                    <th class="border">{{ translate('dashboard.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($all_rows) && count($all_rows))
                    @foreach($all_rows as $row)
                        <tr class="border hover:bg-indigo-50 transition duration-300ms ease-in-out">
                            <td class="border">{{ $row->label }}</td>
                            @if($field == 'specialization')
                                <td>{{ $row->parent->label ?? '-' }}</td>
                            @endif
                            <td class="border">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                            <td class="border">
                                <div class="inline-flex justify-center items-center">
                                    {{-- Edit --}}
                                    <button 
                                        class="inline-flex rounded-full p-2 bg-gray-400 hover:bg-gray-500 text-white ms-3 focus:outline-none"
                                        title="Edit {{ strtolower($fieldName) }}"
                                        x-on:click="isModalVisible = true; isLoading = true;"
                                        wire:click="openRow({{ $row->id }})"
                                    >
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Delete --}}
                                    <button 
                                        class="inline-flex rounded-full p-2 bg-red-500 hover:bg-red-600 text-white ms-3 focus:outline-none"
                                        title="Delete {{ strtolower($fieldName) }}"
                                        onclick="event.preventDefault(); deleteRow('{{ $row->id }}', '{{ $field }}');"
                                    >
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="20">
                            <h1 class="text-center text-red-500 text-lg sm:text-2xl my-5">{{ translate('messages.no_data_to_show') }}</h1>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($total_rows > $showMax)
        <div>
            <div class="mt-5">
                {{ $all_rows->onEachSide(5)->links() }}
            </div>
        </div>
    @endif

    {{-- Modal --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 z-30" style="display: none" x-show.transition.opacity="isModalVisible">
        <div class="h-full flex flex-col sm:justify-center items-center pt-0" x-show.transition.scale="isModalVisible">
            <div class="h-full sm:h-auto w-full sm:max-w-md p-4 border bg-gray-50 shadow-md overflow-auto sm:rounded-lg">
    
                <!-- Modal Header -->
                <div class="flex flex-row justify-between items-center mb-5">
                    <h4 class="text-lg text-gray-700">{{ empty($row_id) ? translate('buttons.create') : translate('buttons.update') }}</h4>
                    <button class="text-gray-500 hover:text-gray-800" @click.prevent="isModalVisible = false">
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
                        wire:submit.prevent="createOrUpdateRow({{ $row_id ?? null }})"
                        x-show="!isLoading"
                        id="row-form"
                    >
                        @csrf

                        @if($field == 'specialization')
                            <div class="form-group mb-4">
                                <label for="" class="text-gray-700 mb-2">{{ translate('dashboard.user_fields.general_specialization') }}</label>
                                <select name="parent_id" id="parent_id" class="cursor-pointer border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required wire:model.defer="parent_id">
                                    <option value="" selected disabled>Select...</option>
                                    @foreach ($general_specializations as $row)
                                        <option value="{{ $row->id }}">{{ $row->label }}</option>
                                    @endforeach
                                </select>
                                @error('label') <span class="error text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif
    
                        <div class="form-group">
                            <label for="" class="text-gray-700 mb-2">{{ translate('dashboard.user_fields.label') }}</label>
                            <input type="text" class="form-input p-2 rounded border border-gray-300 shadow w-full focus:outline-none focus:ring focus:border-blue-300" name="label" wire:model.defer="label" placeholder="ABCD" autocomplete="off">
                            @error('label') <span class="error text-red-500">{{ $message }}</span> @enderror
                        </div>
    
                        <div class="form-group mt-5 text-end">
                            <button
                                class="mt-4 sm:mt-0 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded py-2 px-6 shadow border-indigo-400 text-white h-9 inline-flex items-center justify-center transition duration-300ms ease-in-out"
                            >
                                {{ empty($row_id) ? translate('buttons.create') : translate('buttons.update') }}
                            </button>
                        </div>
                    </form>

                    <div class="flex justify-center items-center p-4" x-show="isLoading">
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

@section('scripts')
    <script type="text/javascript">
        // Listen to events
        window.addEventListener('action:reset-fields', function() {
            resetFields();
        });

        // Functions
        const resetFields = function() {
            const $form = document.getElementById('row-form');

            $form.reset();
        };

        // Delete entry
        const deleteRow = function(row_id = null, field = null) {
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
                    window.livewire.emit('action:delete-row', row_id, field);
                }
            })
        }
    </script>
@stop