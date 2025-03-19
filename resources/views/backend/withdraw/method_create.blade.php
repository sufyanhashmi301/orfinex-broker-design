@extends('backend.setting.payment.withdraw.index')
@section('title')
    {{ __('New Withdraw Method') }}
@endsection

@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('withdraw-content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.withdraw.method.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="input-area relative max-w-xs mb-5">
                        <label class="form-label" for="">{{ __('Add Withdraw Logo:') }}</label>
                        <div class="wrap-custom-file">
                            <input
                                type="file"
                                name="icon"
                                id="logo"
                                accept=".gif, .jpg, .png"
                            />
                            <label for="logo">
                                <img
                                    class="upload-icon"
                                    src="{{ asset('global/materials/upload.svg') }}"
                                    alt=""
                                />
                                <span>{{ __('Upload Logo') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">

                        @if($type == 'auto')
                            <div class="input-area relative">
                                <label class="form-label" for="">{{ __('Automatic Gateway:') }}</label>
                                <select name="gateway_id"
                                        class="form-control w-100"
                                        id="gateway-select">
                                    <option>{{ __('Select Gateway') }}</option>
                                    @foreach($gateways as $gateway)
                                        <option data-currencies="{{ $gateway->supported_currencies }}"
                                                value="{{$gateway->id}}"> {{$gateway->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area relative">
                                <label class="form-label"
                                       for="">{{ __('Gateway Supported Currency:') }}</label>
                                <select name="currency" class="form-control w-100" id="currency">

                                </select>
                            </div>
                        @endif

                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Name:') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="name"
                            />
                        </div>

                        @if($type == 'manual')
                            <div class="input-area relative">
                                <label class="form-label" for="">{{ __('Currency:') }}</label>
                                {{-- <input
                                    type="text"
                                    class="form-control"
                                    name="currency"
                                    id="currency"
                                /> --}}
                                <select name="currency" class="select2 form-control w-full select-manual-currency" placeholder="Select Currency">
                                    <option value=""></option>
                                    @foreach( $rates_with_countries as $field)
                                        <option  value="{{ $field->currency_code }}">
                                            {{ $field->currency_code  }} ({{ $field->currency_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Convention Rate:') }}</label>
                            <div class="joint-input relative">
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                    {{'1 '.' '.$currency. ' ='}}
                                </span>
                                <input type="text" class="form-control !pl-16.5 !pr-9  display-conversion-rate" name="rate" readonly />
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1" id="currency-selected"></span>
                            </div>
                        </div>

                        <div class="input-area relative position-relative">
                            <label class="form-label" for="">{{ __('Charges:') }}</label>
                            <div class="relative">
                                <input type="text" class="form-control !pr-12" oninput="this.value = validateDouble(this.value)" name="charge"/>
                                <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                    <select name="charge_type" class="w-full h-full outline-none">
                                        <option value="percentage">{{ __('%') }}</option>
                                        <option value="fixed">{{ $currencySymbol }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Minimum Withdraw:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control !pr-12" oninput="this.value = validateDouble(this.value)" name="min_withdraw"/>
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                    {{$currency}}
                                </span>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Maximum Withdraw:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control !pr-12" oninput="this.value = validateDouble(this.value)" name="max_withdraw"/>
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                    {{ $currency}}
                                </span>
                            </div>
                        </div>

                        @if($type == 'manual')
                            <div class="input-area relative">
                                <label class="form-label" for="">{{ __('Processing Time:') }}</label>
                                <div class="relative">
                                    <input type="text" name="required_time" class="form-control mb-0"/>
                                    <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                        <select name="required_time_format" class="w-full h-full outline-none">
                                            @foreach(['minute' => 'Mins','hour' => 'Hours','day' => 'Days' ] as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="input-area relative md:col-span-2">
                            <label class="form-label" for="">{{ __('Select countries where you want to show this method(select "All" if you have to show this scheme to whole world):') }}</label>
                            <select name="country[]" class="select2 form-control" placeholder="Manage Country" multiple>
                                @foreach( getCountries() as $country)
                                    <option  value="{{ $country['name'] }}">
                                        {{ $country['name']  }}
                                    </option>
                                @endforeach
                                <option  value="All" >
                                    {{ __('All') }}
                                </option>
                            </select>
                        </div>
{{--                            <div class="input-area relative">--}}
{{--                                <label class="form-label" for="">{{ __('Select countries where you want to show this Payment method (select "All" if you have to show this method to whole world):') }}</label>--}}
{{--                                <select id="multiSelect" class="select2 form-control w-full mt-2 py-2" name="country[]" placeholder="Manage Country" multiple>--}}
{{--                                    @foreach( getCountries() as $country)--}}
{{--                                        <option  value="{{ $country['name'] }}">--}}
{{--                                            {{ $country['name']  }}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                    <option  value="All" >--}}
{{--                                        {{ __('All') }}--}}
{{--                                    </option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
                        <div class="input-area relative flex items-center space-x-7">
                            <label class="form-label !w-auto" for="">{{ __('Status:') }}</label>
                            <div class="form-switch ps-0">
                                <input
                                    class="form-check-input"
                                    type="hidden"
                                    value="0"
                                    name="status"
                                />
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="status"
                                        value="1"
                                        class="sr-only peer"
                                    />
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                        @if($type == 'manual')
                            <div class="md:col-span-2">
                                <a href="javascript:void(0)" id="generate" class="btn btn-dark btn-sm inline-flex items-center justify-center mb-3">
                                    {{ __('Add Field option') }}
                                </a>

                            </div>
                            <div class="addOptions md:col-span-2">

                            </div>
                        @endif
                    </div>
                    <div class="text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('payment-script')
    <script>

        let get_rate = (code) => {

            $.ajax({
                url:  '{{ route("admin.settings.currency.get-rate", ":code") }}'.replace(':code', code),
                type: 'GET',
                success: function(response) {
                    // Handle the success response (you get the rate here)
                    if (response.rate) {
                        // You can also update a field or display the result on the page
                        $('.display-conversion-rate').val(response.rate.toFixed(6));
                    } else {
                        console.log(response.error);
                    }
                },
                error: function(xhr) {
                    // Handle any errors
                    console.log('Error fetching rate');
                }
            });
        }

        // Manual
        $('.select-manual-currency').on('change', function(){
            get_rate($(this).val())
        })

        // Auto
        $('#currency').on('change', function(){
            get_rate($(this).val())
        })

    </script>
    <script>
        $(document).ready(function (e) {
            "use strict";
            $("#currency").on('change', function () {
                $('#currency-selected').text(this.value);
            });

            var i = 0;
            $("#generate").on('click', function () {
                ++i;
                var form = `<div class="option-remove-row grid grid-cols-12 items-center gap-5 mb-3">
                    <div class="xl:col-span-4 md:col-span-6 col-span-12">
                      <div class="input-area">
                        <input name="fields[` + i + `][name]" class="form-control" type="text" value="" required placeholder="Field Name">
                      </div>
                    </div>

                    <div class="xl:col-span-4 md:col-span-6 col-span-12">
                      <div class="input-area">
                        <select name="fields[` + i + `][type]" class="form-control w-100 form-control w-100-lg mb-3">
                            <option value="text">Input Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="file">File upload</option>
                        </select>
                      </div>
                    </div>
                    <div class="xl:col-span-3 md:col-span-6 col-span-12">
                      <div class="input-area mb-0">
                        <select name="fields[` + i + `][validation]" class="form-control w-100 form-control w-100-lg mb-1">
                            <option value="required">Required</option>
                            <option value="nullable">Optional</option>
                        </select>
                      </div>
                    </div>

                    <div class="xl:col-span-1 md:col-span-6 col-span-12">
                      <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row delete_desc" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
                        </svg>
                      </button>
                    </div>
                    </div>`;
                $('.addOptions').append(form)
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.option-remove-row').remove();
            });

            $('#gateway-select').on('change', function () {
                var id = $(this).val();
                var url = '{{ route('admin.gateway.supported.currency',':id') }}';
                url = url.replace(':id', id);
                $.get(url, function ($data) {
                    $('#currency').html($data.view);
                    get_rate($('#currency').val())
                })
            })
        });
    </script>
@endsection
