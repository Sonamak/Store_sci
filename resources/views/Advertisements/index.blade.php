
<x-app-layout>

@section('styles')

<style>
    .badge
    {
        padding: 2px 10px;
        background: #205791;
        border-radius: 50px;
        color: #fff;
        font-weight: bold;
        display: block;
        text-align: center;
        margin-bottom: 5px;
    }
</style>

@endsection
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ translate('dashboard.advertisements.advertisements') }}
            <a href="{{ route('advertisements.create', ['locale' => app()->getLocale()]) }}" 
                class='rounded-lg px-4 py-2 border-1 border-blue-500 text-blue-500 hover:bg-blue-600 hover:text-blue-100 duration-300 {{ app()->getLocale() == "en" ? "float-right" : "float-left" }}'>{{ translate('dashboard.advertisements.new_advertisement') }}</a>
        </h2>
    </x-slot>


    <div class="py-4">
        <div class="max-w-7xl mx-auto px-0 ld:px-4">
            <div class="overflow-hidden sm:rounded-lg">
                
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
                    <div class="block w-full overflow-x-auto">
                        <table class="items-center bg-transparent w-full border-collapse ">
                            <thead>
                                <tr>
                                    <th class='text-center bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left'>{{ translate('dashboard.advertisements.table_id') }}</th>
                                    <th class='text-center bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left'>{{ translate('dashboard.advertisements.table_title') }}</th>
                                    <th class='text-center bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left'>{{ translate('dashboard.advertisements.table_image') }}</th>
                                    <th class='text-center bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left'>{{ translate('dashboard.advertisements.table_related_specializations') }}</th>
                                    <th class='text-center bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left'>{{ translate('dashboard.advertisements.table_start_date') }}</th>
                                    <th class='text-center bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left'>{{ translate('dashboard.advertisements.table_end_date') }}</th>
                                    <th class='text-center bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left'>{{ translate('dashboard.advertisements.table_created_at') }}</th>
                                    <th class='text-center bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left'>{{ translate('dashboard.advertisements.table_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($advertisements as $advertisement) 
                                
                                <tr>
                                    <td class='text-center border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>{{ $advertisement->id }}</td>
                                    <td class='text-center border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>{{ $advertisement->title }}</td>
                                    <td class='text-center border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>
                                        <img class='m-auto' width='100' height="100" src='{{ asset("storage/" . $advertisement->image) }}' alt=''>
                                    </td>

                                    <td class='text-center border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>
                                        @foreach($advertisement->fields as $field)
                                            <span class='badge'>{{ $field->label }}</span>
                                        @endforeach
                                    </td>

                                    <td class='text-center border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>{{ $advertisement->start_date }}</td>
                                    <td class='text-center border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>{{ $advertisement->end_date }}</td>
                                    <td class='text-center border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>{{ $advertisement->created_at->diffForHumans() }}</td>

                                    <td class='text-center border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>
                                        <a href='{{ route("advertisements.edit", ["locale" => app()->getLocale(), "advertisement" => $advertisement]) }}' class="rounded-lg px-2 py-2 border-2 border-blue-500 text-blue-500 hover:bg-blue-600 hover:text-blue-100 duration-300">
                                        {{ translate('dashboard.advertisements.table_edit') }}
                                        </a>

                                        <a 
                                        href='#' 
                                        onclick="event.preventDefault(); document.getElementById('delete_form_{{ $advertisement->id }}').submit()" 
                                        class="m-1 rounded-lg px-2 py-2 border-2 border-red-500 text-red-500 hover:bg-red-600 hover:text-red-100 duration-300">
                                        {{ translate('dashboard.advertisements.table_delete') }}
                                        </a>

                                        <form id='delete_form_{{ $advertisement->id }}'  method='post' action="{{ route('advertisements.destroy', ['locale' => app()->getLocale(), 'advertisement' => $advertisement]) }}">@csrf @method('DELETE')</form>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    
                </div>
                {{ $advertisements->links() }}
            </div>
        </div>
    </div>

    @section('scripts')
	<script src="https://creatantech.com/demos/codervent/rocker/vertical/assets/js/jquery.min.js"></script>

    <script>
        $(function(){

            setTimeout(() => {
                $(".global-message").fadeOut()
            }, 5000);

        });
    </script>
    @endsection
    


</x-app-layout>