<x-app-layout>

@section('styles')

	<!--plugins-->
    <link href="https://creatantech.com/demos/codervent/rocker/vertical/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
	<link href="https://creatantech.com/demos/codervent/rocker/vertical/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
    <style>
        input[type='search']:focus{
            --tw-ring-color: #fff;
        }
    </style>
@endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ translate('dashboard.advertisements.new_advertisement') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-0 ld:px-4">
            <div class="overflow-hidden sm:rounded-lg">
                
                <div class="w-full">
                    <form enctype='multipart/form-data' method='post' action="{{ route('advertisements.store', app()->getLocale()) }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                            {{ translate('dashboard.advertisements.create_title') }}
                            </label>
                            <input value='{{ old("title") }}' name='title' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text">
                            @error('title')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            {{ translate('dashboard.advertisements.create_description') }}
                            </label>

                            <textarea name='description' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="description">{{ old("description") }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                            {{ translate('dashboard.advertisements.create_image') }}
                            </label>
                            <input name='image' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="image" type="file">
                            
                            @error('image')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror                        
                        </div>

                        <div class="mb-6">
                            <label for="specialization_id" class="form-label">{{ translate('dashboard.advertisements.create_related_specializations') }}</label>
                            <select name='specialization_id[]' class="form-control multiple-select" data-placeholder="Choose anything" multiple="multiple">
                                @foreach( $user_fields as $user_field )

                                    <option class='parent' value='{{ $user_field->id }}'>-- {{ $user_field->label }}</strong></option>

                                    @foreach($user_field->children as $child)
                                        <option value='{{ $child->id }}'>{{ $child->label }}</option>
                                    @endforeach

                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-6 flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start_date">
                                {{ translate('dashboard.advertisements.create_start_date') }}
                                </label>
                                <input value='{{ old("start_date") }}' name='start_date' class="appearance-none block w-full text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="start_date" type="date">
                            
                                @error('start_date')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full md:w-1/2 px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="end_date">
                                {{ translate('dashboard.advertisements.create_end_date') }}
                                </label>
                                <input value='{{ old("end_date") }}' name='end_date' class="appearance-none block w-full text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="end_date" type="date">
                                @error('end_date')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                            
                        <div class="flex items-center justify-between">
                            <button type='submit' class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ translate('dashboard.advertisements.create_add_advertisement') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @section('scripts')


	<!--plugins-->
	<script src="https://creatantech.com/demos/codervent/rocker/vertical/assets/js/jquery.min.js"></script>
    <script src="https://creatantech.com/demos/codervent/rocker/vertical/assets/plugins/select2/js/select2.min.js"></script>
    
    <script>
        $(function(){

            $('.multiple-select').select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });

            $(".selection").on("click", (e) => {
               
                $("li.select2-results__option").each((index, li) => {
                    let arr_length = $(li).text().split('--').length;
                    if(arr_length == 2)
                        $(li).css({"color":"gray", "fontWeight": "bold"})
                    else
                        $(li).css({"paddingLeft":"20px"})
                })
            })

            setTimeout(() => {
                $(".global-message").fadeOut()
            }, 5000);

        });
    </script>
    @endsection

</x-app-layout>
