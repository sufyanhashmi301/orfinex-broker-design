@extends('backend.setting.payment.deposit.index')
@section('title')
    {{ __(ucwords($type) . ' Method') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4
            class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('deposit-content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.deposit.method.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-7">
                        <div class="md:col-span-2">
                            <div class="input-area relative max-w-xs">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Upload an image to visually identify this deposit method">
                                        {{ __('Add Method Logo') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="wrap-custom-file">
                                    <input type="file" name="logo" id="logo" accept=".gif, .jpg, .png" />
                                    <label for="logo">
                                        <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}"
                                            alt="" />
                                        <span>{{ __('Upload Logo') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if ($type == 'auto')
                            <div class="input-area relative">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Select the payment gateway (e.g., Stripe, PayPal) for automatic processing">
                                        {{ __('Automatic Gateway') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="gateway_id" class="form-control w-100" id="gateway-select">
                                    <option>{{ __('Select Gateway') }}</option>
                                    @foreach ($gateways as $gateway)
                                        <option data-currencies="{{ $gateway->supported_currencies }}"
                                            value="{{ $gateway->id }}"> {{ $gateway->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area relative">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The currency used by the selected gateway (e.g., USD, EUR)">
                                        {{ __('Gateway Supported Currency') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="currency" class="form-control w-100" id="currency">

                                </select>
                            </div>
                        @endif

                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Enter a user-friendly name for this deposit method">
                                    {{ __('Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="name" />
                        </div>
                        @if ($type == 'manual')
                            <div class="input-area relative">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Internal short identifier for this method (e.g., BANK_WIRE)">
                                        {{ __('Code Name') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input type="text" class="form-control" name="method_code" />
                            </div>
                        @endif

                        @if ($type == 'manual')
                            <div class="input-area relative">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Choose the currency in which deposits will be accepted">
                                        {{ __('Currency') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                {{-- <input
                                    type="text"
                                    class="form-control"
                                    name="currency"
                                    id="currency"
                                /> --}}
                                <select name="currency" class="select2 form-control w-full select-manual-currency"
                                    placeholder="Select Currency">
                                    <option value=""></option>
                                    @foreach ($rates_with_countries as $field)
                                        <option value="{{ $field->currency_code }}">
                                            {{ $field->currency_code }} ({{ $field->currency_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="The symbol representing the transaction currency (e.g., $, €, ₿)">
                                    {{ __('Currency Symbol') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control currency-symbol" name="currency_symbol" readonly />
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Define the conversion from 1 {{ $currency }} to the target currency">
                                    {{ __('Conversion Rate') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="joint-input relative">
                                <span
                                    class="absolute left-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                    {{ '1 ' . ' ' . $currency . ' =' }}
                                </span>
                                <input type="text" class="form-control !pl-16.5 !pr-9 display-conversion-rate"
                                    name="rate" readonly />
                                <span
                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1"
                                    id="currency-selected"></span>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Set the transaction fee as a percentage or fixed amount">
                                    {{ __('Charges') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="relative">
                                <input type="text" class="form-control !pr-12"
                                    oninput="this.value = validateDouble(this.value)" name="charge" />
                                <div
                                    class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                    <select name="charge_type" class="w-full h-full outline-none">
                                        <option value="percentage">{{ __('%') }}</option>
                                        <option value="fixed">{{ $currencySymbol }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="The minimum deposit allowed using this method">
                                    {{ __('Minimum Deposit') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="joint-input relative">
                                <input type="text" name="minimum_deposit" class="form-control !pr-12"
                                    oninput="this.value = validateDouble(this.value)" />
                                <span
                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                    {{ $currency }}
                                </span>
                            </div>

                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="The maximum deposit limit for this method">
                                    {{ __('Maximum Deposit') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="joint-input relative">
                                <input type="text" name="maximum_deposit" class="form-control !pr-12"
                                    oninput="this.value = validateDouble(this.value)" />
                                <span
                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                    {{ $currency }}
                                </span>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Specify the expected time to process a deposit (e.g., 1-2 hours)">
                                    {{ __('Processing Time') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="processing_time" class="form-control" />
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Select ‘All’ to make this payment method available in all countries">
                                    {{ __('Select Countries Authorized to Use') }}
                                    <iconify-icon class="text-[16px]"
                                        icon="mdi:information-slab-circle-outline"></iconify-icon>
                                </span>
                            </label>
                            <select name="country[]" class="select2 form-control w-full" placeholder="Manage Country"
                                multiple>
                                @foreach (getCountries() as $country)
                                    <option value="{{ $country['name'] }}">
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                                <option value="All">
                                    {{ __('All') }}
                                </option>
                            </select>
                        </div>
                        @if ($type == 'manual')
                            <div class="md:col-span-2">
                                <a href="javascript:void(0)" id="generate" class="btn btn-dark btn-sm inline-flex">
                                    {{ __('Add Field option') }}
                                </a>
                            </div>
                            <div class="addOptions md:col-span-2">
                            </div>
                            <div class="md:col-span-2">
                                <div class="input-area relative fw-normal">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1"
                                            data-tippy-content="Toggle to enable or disable this ranking level">
                                            {{ __('Payment Details') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                                class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="site-editor">
                                        <textarea class="summernote"></textarea>
                                    </div>
                                    <input type="hidden" name="payment_details">
                                </div>
                            </div>
                        @endif

                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto pt-0">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Toggle to enable or disable the method for users">
                                        {{ __('Status') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="status">
                                    <label
                                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="status" value="1" class="sr-only peer">
                                        <span
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if ($type == 'manual')
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0">
                                        <span class="shift-Away inline-flex items-center gap-1"
                                            data-tippy-content="Enable this to allow users to request custom bank details for this method">
                                            {{ __('Is Custom Requested Bank Details') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                                class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="is_custom_bank_details">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_custom_bank_details" value="1"
                                                class="sr-only peer">
                                            <span
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="md:col-span-2 text-right mt-10">
                            <button type="submit" class="btn btn-dark">
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
        const autoExchangeRatesEnabled = @json($autoExchangeRatesEnabled);

        let get_rate = (code) => {
            $.ajax({
                url: '{{ route('admin.settings.currency.get-rate', ':code') }}'.replace(':code', code),
                type: 'GET',
                success: function(response) {
                    // Handle the success response (you get the rate here)
                    if (response.rate) {
                        // Always load the values, but only make them readonly if auto exchange is enabled
                        $('.display-conversion-rate').val(response.rate.toFixed(6));
                        $('.currency-symbol').val(response.symbol);

                        // If auto exchange rates are disabled, make the fields editable after loading values
                        if (!autoExchangeRatesEnabled) {
                            $('.display-conversion-rate').prop('readonly', false);
                            $('.currency-symbol').prop('readonly', false);
                        }
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

        (function($) {
            $(document).ready(function() {
                $('.select-manual-currency').on('change', function() {
                    get_rate($(this).val());
                });

                $('#currency').on('change', function() {
                    get_rate($(this).val());
                });

                // If auto exchange rates are disabled, show a message or provide manual input guidance
                if (!autoExchangeRatesEnabled) {
                    console.log(
                        'Auto exchange rates are disabled. Currency symbol and conversion rate can be edited manually.'
                    );
                }
            });
        })(jQuery);

        (function($) {
            var i = 0;
            "use strict";

            let currency = null;
            $("#currency").on('change', function() {
                if (currency === null) {
                    $('#currency-selected').text(this.value);
                    $('#currency-selected').text(this.value);
                }
            });

            $("#generate").on('click', function() {
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
                      <div class="input-area">
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

            $(document).on('click', '.delete_desc', function() {
                $(this).closest('.option-remove-row').remove();
            });

            $('#gateway-select').on('change', function() {
                var id = $(this).val();
                var url = '{{ route('admin.gateway.supported.currency', ':id') }}';
                url = url.replace(':id', id);
                $.get(url, function($data) {
                    $('#currency').html($data.view);
                    $('#currency-selected').text($data.pay_currency);
                    currency = $data.pay_currency
                    get_rate($('#currency').val())
                })
            })


        })(jQuery)
    </script>
@endsection
