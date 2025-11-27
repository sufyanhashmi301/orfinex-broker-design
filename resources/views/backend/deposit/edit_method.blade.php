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
                <form id="myForm" action="{{ route('admin.deposit.method.update', $method->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-7">
                        <div class="md:col-span-2">
                            <div class="input-area relative max-w-xs">
                                @php
                                    $icon = $method->logo;
                                    if (null != $method->gateway_id && $method->icon == '') {
                                        $icon = $method->gateway->logo;
                                    }
                                @endphp
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Upload an image to visually identify this deposit method">
                                        {{ __('Add Method Logo') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="wrap-custom-file">
                                    <input type="file" name="logo" id="schema-icon" accept=".gif, .jpg, .png" />
                                    <label for="schema-icon" class="file-ok"
                                        style="background-image: url({{ isset($method->gateway_id) ? $method->gateway->logo : asset($icon) }})">
                                        <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}"
                                            alt="" />
                                        <span>{{ __('Update Logo') }}</span>
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
                                    @foreach ($gateways as $gateway)
                                        <option data-currencies="{{ $gateway->supported_currencies }}"
                                            data-gatewayCode="{{ $gateway->gateway_code }}" value="{{ $gateway->id }}"
                                            @selected($method->gateway_id == $gateway->id)> {{ $gateway->name }}
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
                                    @foreach (json_decode($supported_currencies) as $currency)
                                        <option value="{{ $currency }}" @selected($currency == $method->currency)>
                                            {{ $currency }} </option>
                                    @endforeach
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
                            <input type="text" class="form-control" name="name" value="{{ $method->name }}" />
                        </div>
                        <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Internal short identifier for this method (e.g., BANK_WIRE)">
                                    {{ __('Code Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" disabled value="{{ $method->gateway_code }}" />
                        </div>
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
                                <input type="text" class="form-control" name="currency" value="{{ $method->currency }}"
                                    id="currency" />
                            </div>
                        @endif
                        
                         @if ($autoExchangeRatesEnabled)
                        <div class="input-area relative">
                            <label for="" class="form-label invisible">
                                {{ __('Manual Override Rate') }}
                            </label>
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto pt-0">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Enable to set manually coversion rate">
                                        {{ __('Manual Conversion Rate') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="is_rate_override_enabled">
                                    <label
                                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_rate_override_enabled" value="1"
                                            class="sr-only peer" @if ($method->is_rate_override_enabled ?? false) checked @endif>
                                        <span
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                            {{ __('Manage Auto Exchange Rate main setting') }}
                                            <a href="{{ route('admin.settings.company.permissions') }}"
                                                class="text-primary hover:underline ml-1"
                                               target="_blank" rel="noopener noreferrer">
                                                {{ __('click here') }}
                                            </a>
                                        </div>
                        </div>
                        @endif
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
                                    {{ '1 ' . ' ' . setting('site_currency', 'global') . ' =' }}
                                </span>
                                <input type="text" name="rate"
                                    class="form-control !pl-16.5 !pr-12 display-conversion-rate"
                                    oninput="this.value = validateDouble(this.value)" value="{{ $method->rate }}"
                                    @if ($autoExchangeRatesEnabled && !($method->is_rate_override_enabled ?? false)) readonly @endif />
                                <span
                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1"
                                    id="currency-selected">
                                    {{ is_custom_rate($method->gateway?->gateway_code) ?? $method->currency }}
                                </span>
                            </div>
                        </div>
                       <div class="input-area relative">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="The symbol representing the transaction currency (e.g., $, €, ₿)">
                                    {{ __('Currency Symbol') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control currency-symbol"
                                value="{{ $method->currency_symbol }}" name="currency_symbol"
                                @if ($autoExchangeRatesEnabled) readonly @endif />
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
                                    oninput="this.value = validateDouble(this.value)" name="charge"
                                    value="{{ $method->charge }}" />
                                <div
                                    class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                    <select name="charge_type" class="w-full h-full outline-none">
                                        <option value="percentage" @if ($method->charge_type == 'percentage') selected @endif>
                                            {{ __('%') }}</option>
                                        <option value="fixed" @if ($method->charge_type == 'fixed') selected @endif>
                                            {{ $currencySymbol }}</option>
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
                                    oninput="this.value = validateDouble(this.value)"
                                    value="{{ $method->minimum_deposit }}" />
                                <span
                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">
                                    {{ setting('site_currency', 'global') }}
                                </span>
                            </div>

                        </div>
                        <div class="input-area">
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
                                    oninput="this.value = validateDouble(this.value)"
                                    value="{{ $method->maximum_deposit }}" />
                                <span
                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">
                                    {{ setting('site_currency', 'global') }}
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
                            <input type="text" name="processing_time" class="form-control"
                                value="{{ $method->processing_time }}" />
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
                            <select name="country[]" class="select2 form-control w-full" placeholder="Countries"
                                multiple>
                                @php
                                    $countries = getCountries();
                                    $selectedCountries = is_string($method->country)
                                        ? json_decode($method->country, true)
                                        : (array) $method->country;
                                @endphp
                                @foreach ($countries as $country)
                                    <option value="{{ $country['name'] }}" @selected(in_array($country['name'], $selectedCountries))>
                                        {{ $country['name'] }}</option>
                                @endforeach
                                <option value="All" @selected(in_array('All', $selectedCountries))>{{ __('All') }}</option>
                            </select>
                        </div>

                        @if ($type == 'manual')
                            <div class="md:col-span-2">
                                <a href="javascript:void(0)" id="generate"
                                    class="btn btn-dark btn-sm inline-flex items-center justify-center">
                                    {{ __('Add Field option') }}
                                </a>
                            </div>

                            <div class="addOptions md:col-span-2">
                                @foreach (json_decode($method->field_options, true) as $key => $value)
                                    @php
                                        $isVoucherCode =
                                            $method->gateway_code === 'voucher' &&
                                            strtolower($value['name']) === 'voucher code';
                                    @endphp

                                    <div class="option-remove-row grid grid-cols-12 items-center gap-5 mb-3">
                                        <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                            <div class="input-area">
                                                <input name="field_options[{{ $key }}][name]"
                                                    class="form-control" type="text" value="{{ $value['name'] }}"
                                                    required {{ $isVoucherCode ? 'readonly' : '' }}
                                                    placeholder="Field Name">
                                            </div>
                                        </div>

                                        <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                            <div class="input-area">
                                                <select name="field_options[{{ $key }}][type]"
                                                    class="form-control w-100" {{ $isVoucherCode ? 'disabled' : '' }}>
                                                    <option value="text"
                                                        @if ($value['type'] == 'text') selected @endif>Input
                                                        Text
                                                    </option>
                                                    <option value="textarea"
                                                        @if ($value['type'] == 'textarea') selected @endif>
                                                        Textarea
                                                    </option>
                                                    <option value="file"
                                                        @if ($value['type'] == 'file') selected @endif>File
                                                        upload
                                                    </option>
                                                </select>
                                                @if ($isVoucherCode)
                                                    <input type="hidden" name="field_options[{{ $key }}][type]"
                                                        value="{{ $value['type'] }}">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="xl:col-span-3 md:col-span-6 col-span-12">
                                            <div class="input-area mb-0">
                                                <select name="field_options[{{ $key }}][validation]"
                                                    class="form-control w-100" {{ $isVoucherCode ? 'disabled' : '' }}>
                                                    <option value="required"
                                                        @if ($value['validation'] == 'required') selected @endif>
                                                        Required
                                                    </option>
                                                    <option value="nullable"
                                                        @if ($value['validation'] == 'nullable') selected @endif>
                                                        Optional
                                                    </option>
                                                </select>
                                                @if ($isVoucherCode)
                                                    <input type="hidden"
                                                        name="field_options[{{ $key }}][validation]"
                                                        value="{{ $value['validation'] }}">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="xl:col-span-1 md:col-span-6 col-span-12">
                                            @unless ($isVoucherCode)
                                                <button
                                                    class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row delete_desc"
                                                    type="button">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z" />
                                                    </svg>
                                                </button>
                                            @endunless
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="md:col-span-2">
                                <div class="input-area fw-normal relative">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1"
                                            data-tippy-content="Toggle to enable or disable this ranking level">
                                            {{ __('Payment Details') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                                class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="site-editor">
                                        <textarea class="summernote">{!! $method->payment_details !!}</textarea>
                                    </div>
                                    <input type="hidden" name="payment_details"
                                        value="{{ str_replace(['<', '>'], ['{', '}'], $method->payment_details) }}">
                                </div>
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Select branches where this deposit method will be available">
                                        {{ __('Assign Branches') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="branches[]" id="branchSelect" class="select2 form-control w-full h-9"
                                    placeholder="Select Branches" multiple>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ in_array($branch->id, old('branches', $attachedBranches ?? [])) ? 'selected' : '' }}
                                            class="inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $branch->name }} ({{ $branch->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-xs font-Inter font-normal text-slate-600 block mt-1">
                                    {{ __('Leave empty to make available for all branches.') }}
                                </span>
                            </div>
                        </div>

                        <div class="input-area">
                            <label for="" class="form-label invisible">
                                {{ __('Status') }}
                            </label>
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
                                        <input type="checkbox" name="status" value="1" class="sr-only peer"
                                            @if ($method->status) checked @endif>
                                        <span
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="input-area">
                            <label for="" class="form-label invisible">
                                {{ __('Global Access') }}
                            </label>
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto pt-0">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Enable this to restrict the method to only users with no branch or specifically assigned branches">
                                        {{ __('Global Access') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="is_global">
                                    <label
                                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_global" value="1" class="sr-only peer"
                                            @if ($method->is_global ?? false) checked @endif>
                                        <span
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if ($type == 'manual')
                            <div class="input-area">
                                <label for="" class="form-label invisible">
                                    {{ __('Custom Bank Details') }}
                                </label>
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
                                                class="sr-only peer" @if ($method->is_custom_bank_details ?? false) checked @endif>
                                            <span
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="md:col-span-2 text-right mt-10">
                            <button type="submit" id="submitForm"
                                class="btn btn-dark inline-flex items-center justify-center">
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
        var currency = @json(is_custom_rate($method->gateway?->gateway_code));

        let get_rate = (code) => {
            $.ajax({
                url: '{{ route('admin.settings.currency.get-rate', ':code') }}'.replace(':code', code),
                type: 'GET',
                success: function(response) {
                    // Handle the success response (you get the rate here)
                    if (response.rate) {
                        // Always update currency symbol
                        $('.currency-symbol').val(response.symbol);
                        
                        // Only update rate when auto exchange rates are enabled
                        if (autoExchangeRatesEnabled) {
                            $('.display-conversion-rate').val(response.rate.toFixed(6));
                            
                            // When auto updates are enabled, respect the manual override toggle
                            const $overrideToggle = $('input[name="is_rate_override_enabled"]');
                            if ($overrideToggle.length) {
                                const enabled = $overrideToggle.is(':checked');
                                $('.display-conversion-rate').prop('readonly', !enabled);
                            } else {
                                $('.display-conversion-rate').prop('readonly', true);
                            }
                            $('.currency-symbol').prop('readonly', true);
                        } else {
                            // If auto exchange rates are disabled, make the fields editable
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

        $("#currency").on('change', function() {
            if (currency === null) {
                $('#currency-selected').text(this.value);
            } else {
                // For custom rate gateways (nowpayments, coinremitter, blockchain)
                // Update the currency display to show the selected currency
                $('#currency-selected').text(this.value);
            }
            // Always fetch to update currency symbol, rate only updates if autoExchangeRatesEnabled
            get_rate($(this).val());
        });

        // If auto exchange rates are disabled, make the fields editable on page load
        $(document).ready(function() {
            if (!autoExchangeRatesEnabled) {
                $('.display-conversion-rate').prop('readonly', false);
                $('.currency-symbol').prop('readonly', false);
                console.log('Auto exchange rates are disabled. Currency symbol and conversion rate are editable.');
            } else {
                // When auto updates are enabled, allow manual override toggle to control the rate field
                const $overrideToggle = $('input[name="is_rate_override_enabled"]');
                if ($overrideToggle.length) {
                    const syncReadonly = () => {
                        const enabled = $overrideToggle.is(':checked');
                        $('.display-conversion-rate').prop('readonly', !enabled);
                    };
                    // Initial sync
                    syncReadonly();
                    // On change
                    $overrideToggle.on('change', syncReadonly);
                }
            }
        });

        if (null != @json($method->field_options)) {
            var i = Object.keys(JSON.parse(@json($method->field_options))).length;
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

            $(document).on('click', '.delete_desc', function() {
                $(this).closest('.option-remove-row').remove();
            });
        }

        $('#gateway-select').on('change', function() {
            var id = $(this).val();
            var url = '{{ route('admin.gateway.supported.currency', ':id') }}';
            url = url.replace(':id', id);
            $.get(url, function(data) {
                $('#currency').html(data.view);
                $('#currency-selected').text(data.pay_currency);
                currency = data.pay_currency
                // Always fetch to update currency symbol, rate only updates if autoExchangeRatesEnabled
                if ($('#currency').val()) {
                    get_rate($('#currency').val());
                }
            })
        })

        if (currency !== null) {
            $('#currency-selected').text(currency);
        }
        $('#myForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default action
            // tinyMCE.triggerSave();
            var form = $(this);
            var submitButton = $('#submitForm');

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
