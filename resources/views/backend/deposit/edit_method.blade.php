@extends('backend.setting.payment.deposit.index')
@section('title')
    {{ __(ucwords($type).' Method') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>

@endsection

@section('deposit-content')

    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                <form id="myForm" action="{{ route('admin.deposit.method.update',$method->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                        <div class="md:col-span-2">
                            <div class="input-area max-w-xs">
                                @php
                                    $icon = $method->logo;
                                    if (null != $method->gateway_id && $method->icon == ''){
                                        $icon = $method->gateway->logo;
                                    }
                                @endphp
                                <label class="form-label" for="">{{ __('Upload Logo:') }}</label>
                                <div class="wrap-custom-file">
                                    <input
                                        type="file"
                                        name="logo"
                                        id="schema-icon"
                                        accept=".gif, .jpg, .png"
                                    />
                                    <label for="schema-icon" class="file-ok"
                                           style="background-image: url({{ isset($method->gateway_id) ? $method->gateway->logo : asset($icon) }})">
                                        <img
                                            class="upload-icon"
                                            src="{{ asset('global/materials/upload.svg') }}"
                                            alt=""
                                        />
                                        <span>{{ __('Update Logo') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if($type == 'auto')
                            <div class="input-area relative">
                                <label class="form-label" for="">{{ __('Automatic Gateway:') }}</label>
                                <select name="gateway_id"
                                        class="form-control w-100"
                                        id="gateway-select">
                                    @foreach($gateways as $gateway)
                                        <option data-currencies="{{ $gateway->supported_currencies }}"
                                                data-gatewayCode="{{ $gateway->gateway_code }}"
                                                value="{{$gateway->id}}" @selected($method->gateway_id == $gateway->id)> {{$gateway->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area relative">
                                <label class="form-label"
                                       for="">{{ __('Gateway Supported Currency:') }}</label>
                                <select name="currency" class="form-control w-100" id="currency">
                                    @foreach(json_decode($supported_currencies) as $currency)
                                        <option
                                            value="{{ $currency }}" @selected($currency == $method->currency )>{{ $currency }} </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Name:') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                value="{{ $method->name }}"
                            />
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Code Name') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                disabled
                                value="{{ $method->gateway_code }}"
                            />
                        </div>
                        @if($type == 'manual')
                            <div class="input-area relative">
                                <label class="form-label" for="">{{ __('Currency:') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="currency"
                                    value="{{$method->currency}}"
                                    id="currency"
                                />
                            </div>
                        @endif
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Currency Symbol:') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $method->currency_symbol}}"
                                name="currency_symbol"
                            />
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Conversion Rate:') }}</label>
                            <div class="joint-input relative">
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                    {{'1 '.' '.setting('site_currency', 'global'). ' ='}}
                                </span>
                                <input type="text" name="rate" class="form-control !pl-16.5" value="{{$method->rate}}"/>
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1" id="currency-selected">
                                    {{  is_custom_rate($method->gateway?->gateway_code) ?? $method->currency }}
                                </span>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Charges:') }}</label>
                            <div class="relative">
                                <input type="text" class="form-control"
                                       oninput="this.value = validateDouble(this.value)" name="charge"
                                       value="{{ $method->charge }}"/>
                                <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                    <select name="charge_type" class="w-full h-full outline-none">
                                        <option value="percentage"
                                                @if($method->charge_type == 'percentage') selected @endif>{{ __('%') }}</option>
                                        <option value="fixed"
                                                @if($method->charge_type == 'fixed') selected @endif>{{ $currencySymbol }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Minimum Deposit:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" name="minimum_deposit" class="form-control" value="{{ $method->minimum_deposit }}"/>
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">
                                    {{ setting('site_currency', 'global') }}
                                </span>
                            </div>

                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Maximum Deposit:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" name="maximum_deposit" class="form-control" value="{{ $method->maximum_deposit }}"/>
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">
                                    {{setting('site_currency', 'global')}}
                                </span>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">{{ __('Processing Time:') }}</label>
                            <input type="text" name="processing_time" class="form-control" value="{{ $method->processing_time }}"/>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="flex items-center">
                                    {{ __('Select Countries Authorized to Use:') }}
                                    <iconify-icon class="toolTip onTop text-base ml-1" icon="lucide:info" data-tippy-content="Select ‘All’ to make this payment method available in all countries."></iconify-icon>
                                </span>
                            </label>
                            <select name="country[]" class="select2 form-control w-full" placeholder="Countries" multiple>
                                @php
                                    $countries = getCountries();
                                    $selectedCountries = is_string($method->country) ? json_decode($method->country, true) : (array) $method->country;
                                @endphp
                                @foreach($countries as $country)
                                    <option value="{{ $country['name'] }}" @selected(in_array($country['name'], $selectedCountries))>{{ $country['name'] }}</option>
                                @endforeach
                                <option value="All" @selected(in_array('All', $selectedCountries))>{{ __('All') }}</option>
                            </select>
                        </div>

                        @if($type == 'manual')
                            <div class="col-span-2">
                                <a href="javascript:void(0)" id="generate" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                                    {{ __('Add Field option') }}
                                </a>
                            </div>

                            <div class="addOptions col-span-2">
                                @foreach(json_decode($method->field_options,true) as $key => $value)
                                    <div class="option-remove-row grid grid-cols-12 items-center gap-5 mb-3">
                                        <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                            <div class="input-area">
                                                <input name="field_options[{{$key}}][name]" class="form-control"
                                                       type="text" value="{{$value['name']}}" required
                                                       placeholder="Field Name">
                                            </div>
                                        </div>

                                        <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                            <div class="input-area">
                                                <select name="field_options[{{$key}}][type]" class="form-control w-100">
                                                    <option value="text"
                                                            @if($value['type'] == 'text') selected @endif>Input
                                                        Text
                                                    </option>
                                                    <option value="textarea"
                                                            @if($value['type'] == 'textarea') selected @endif>
                                                        Textarea
                                                    </option>
                                                    <option value="file"
                                                            @if($value['type'] == 'file') selected @endif>File
                                                        upload
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="xl:col-span-3 md:col-span-6 col-span-12">
                                            <div class="input-area mb-0">
                                                <select name="field_options[{{ $key }}][validation]" class="form-control w-100">
                                                    <option value="required"
                                                            @if($value['validation'] == 'required') selected @endif>
                                                        Required
                                                    </option>
                                                    <option value="nullable"
                                                            @if($value['validation'] == 'nullable') selected @endif>
                                                        Optional
                                                    </option>
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
                                    </div>
                                @endforeach
                            </div>

                            <div class="md:col-span-2">
                                <div class="input-area fw-normal relative">
                                    <label for="" class="form-label">{{ __('Payment Details:') }}</label>
                                    <div class="site-editor">
                                <textarea class="basicTinymce" name="payment_details">{!! $method->payment_details !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto pt-0">
                                    {{ __('Status:') }}
                                </label>
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="status">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="status" value="1" class="sr-only peer" @if($method->status) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 text-right mt-10">
                            <button type="submit" id="submitForm" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('payment-script')
    <script>
        var currency = @json(is_custom_rate($method->gateway?->gateway_code));

        $("#currency").on('change', function () {
            if (currency === null) {
                $('#currency-selected').text(this.value);
            }
        });

        if (null != @json($method->field_options)) {
            var i = Object.keys(JSON.parse(@json($method->field_options))).length;
            $("#generate").on('click', function () {
                ++i;
                var form = `<div class="option-remove-row grid grid-cols-12 items-center gap-5 mb-3">
                <div class="xl:col-span-4 md:col-span-6 col-span-12">
                  <div class="input-area">
                    <input name="field_options[` + i + `][name]" class="form-control" type="text" value="" required placeholder="Field Name">
                  </div>
                </div>

                <div class="xl:col-span-4 md:col-span-6 col-span-12">
                  <div class="input-area">
                    <select name="field_options[` + i + `][type]" class="form-control w-100">
                        <option value="text">Input Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="file">File upload</option>
                    </select>
                  </div>
                </div>
                <div class="xl:col-span-3 md:col-span-6 col-span-12">
                  <div class="input-area mb-0">
                    <select name="field_options[` + i + `][validation]" class="form-control w-100">
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
        }

        $('#gateway-select').on('change', function () {
            var id = $(this).val();
            var url = '{{ route('admin.gateway.supported.currency',':id') }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {
                $('#currency').html(data.view);
                $('#currency-selected').text(data.pay_currency);
                currency = data.pay_currency
            })
        })

        if (currency !== null) {
            $('#currency-selected').text(currency);
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#myForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default action
            tinyMCE.triggerSave();
            var form = $(this);
            var submitButton = $('#submitForm');
            console.log(form.serialize());

            // Disable the button and show loading text
            submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            // Create FormData object to handle file input
            var formData = new FormData(this);


            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData, // Use FormData for the request
                contentType: false, // Required for FormData
                processData: false, // Prevent jQuery from processing FormData
                success: function(response) {
                    console.log(response);
                    // Check if the response contains a redirect URL
                    if (response.redirect) {
                        // Perform the redirection
                        window.location.href = response.redirect;
                    } else {
                        // Handle other success responses
                        console.log(response);
                        tNotify('success', 'Form submitted successfully');
                    }
                },
                error: function(xhr, status, error) {
                    // Capture validation errors
                    if (xhr.status === 422) { // Laravel validation error
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';

                        // Loop through the errors and construct the message
                        $.each(errors, function(key, value) {
                            errorMessage += value + '<br>';
                        });

                        // Display error messages in the notification
                        tNotify('warning', errorMessage);
                    } else {
                        // Handle generic errors
                        tNotify('error', 'Something went wrong. Please try again.');
                        console.error('Error:', error);
                    }
                },
                complete: function() {
                    // Re-enable the submit button and reset its text
                    submitButton.prop('disabled', false).html('Save Changes');
                }
            });
        });



    </script>

@endsection
