@extends('backend.setting.payment.withdraw.index')
@section('title')
    {{ __('Edit Withdraw Method') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4
            class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('withdraw-content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.withdraw.method.update', $withdrawMethod->id) }}" class="row" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="grid grid-cols-12 items-center gap-5">
                        <div class="col-span-12">
                            <div class="input-area max-w-xs">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Upload an image to visually identify this withdraw method">
                                        {{ __('Add Method Logo') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="wrap-custom-file">
                                    <input type="file" name="icon" id="schema-icon" accept=".gif, .jpg, .png" />
                                    <label for="schema-icon"
                                        @if ($withdrawMethod->icon) class="file-ok" style="background-image: url({{ asset($withdrawMethod->icon) }})" @endif>
                                        <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}"
                                            alt="" />
                                        <span>{{ __('Update Icon') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Enter a user-friendly name for this method">
                                        {{ __('Name') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ $withdrawMethod->name }}" />
                            </div>
                        </div>

                        @if ($type == 'auto')
                            <div class="xl:col-span-6 col-span-12">
                                <div class="input-area">
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
                                            <option value="{{ $currency }}" @selected($currency == $withdrawMethod->currency)>
                                                {{ $currency }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($type == 'manual')
                            <div class="xl:col-span-6 col-span-12">
                                <div class="input-area">
                                    <label class="form-label" for="">
                                        <span class="shift-Away inline-flex items-center gap-1"
                                            data-tippy-content="Choose the currency in which withdrawal method will be accepted">
                                            {{ __('Currency') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                                class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <input type="text" class="form-control" name="currency"
                                        value="{{ $withdrawMethod->currency }}" id="currency" />
                                </div>
                            </div>
                        @endif
                         @if ($autoExchangeRatesEnabled ?? false)
                            <div class="xl:col-span-6 col-span-12">
                                <div class="input-area">
                                    <label class="form-label invisible" for="">{{ __('Manual Override Rate') }}</label>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <label class="form-label !w-auto pt-0">
                                            <span class="shift-Away inline-flex items-center gap-1"
                                                data-tippy-content="Enable to set manually coversion rate">
                                                {{ __('Manual Conversion Rate') }}
                                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                            </span>
                                            
                                        </label>
                                        <div class="form-switch ps-0">
                                            <input type="hidden" value="0" name="is_rate_override_enabled">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="is_rate_override_enabled" value="1" class="sr-only peer"
                                                       @if ($withdrawMethod->is_rate_override_enabled ?? false) checked @endif>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
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
                            </div>
                            @endif
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area row">
                                <div class="col-span-12">
                                    <label class="form-label" for="">
                                        <span class="shift-Away inline-flex items-center gap-1"
                                            data-tippy-content="Define the conversion from 1 {{ $currency }} to the target currency">
                                            {{ __('Convention Rate') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                                class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="joint-input relative">
                                        <span
                                            class="absolute left-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                            {{ '1 ' . ' ' . setting('site_currency', 'global') . ' =' }}
                                        </span>
                                        <input type="text" name="rate" class="form-control !pl-16.5 !pr-12 display-conversion-rate"
                                            oninput="this.value = validateDouble(this.value)"
                                            value="{{ $withdrawMethod->rate }}"
                                            @if (($autoExchangeRatesEnabled ?? false) && !($withdrawMethod->is_rate_override_enabled ?? false)) readonly @endif />
                                        <span
                                            class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1"
                                            id="currency-selected">
                                            {{ $withdrawMethod->currency }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            </div>
                            @if ($type == 'auto')
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The symbol representing the transaction currency (e.g., $, €, ₿)">
                                        {{ __('Currency Symbol') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input type="text" class="form-control currency-symbol"
                                    value="{{ $withdrawMethod->currency_symbol ?? '' }}" name="currency_symbol"
                                    @if ($autoExchangeRatesEnabled ?? false) readonly @endif />
                            </div>
                        </div>
                        @endif
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area position-relative">
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
                                        value="{{ $withdrawMethod->charge }}" />
                                    <div
                                        class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                        <select name="charge_type" class="w-full h-full outline-none">
                                            <option value="percentage" @if ($withdrawMethod->charge_type == 'percentage') selected @endif>
                                                {{ __('%') }}</option>
                                            <option value="fixed" @if ($withdrawMethod->charge_type == 'fixed') selected @endif>
                                                {{ $currencySymbol }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The minimum withdraw allowed using this method">
                                        {{ __('Minimum Withdraw') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="joint-input relative">
                                    <input type="text" name="min_withdraw" class="form-control !pr-12"
                                        oninput="this.value = validateDouble(this.value)"
                                        value="{{ $withdrawMethod->min_withdraw }}" />
                                    <span
                                        class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1"
                                        id="currency-selected">
                                        {{ setting('site_currency', 'global') }}
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The minimum withdraw allowed using this method">
                                        {{ __('Minimum Withdraw') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="joint-input relative">
                                    <input type="text" name="max_withdraw" class="form-control !pr-12"
                                        oninput="this.value = validateDouble(this.value)"
                                        value="{{ $withdrawMethod->max_withdraw }}" />
                                    <span
                                        class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1"
                                        id="currency-selected">
                                        {{ setting('site_currency', 'global') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if ($type == 'manual')
                            <div class="xl:col-span-6 col-span-12">
                                <div class="input-area">
                                    <label class="form-label" for="">
                                        <span class="shift-Away inline-flex items-center gap-1"
                                            data-tippy-content="Specify the expected time to process a withdraw (e.g., 1-2 hours)">
                                            {{ __('Processing Time') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                                class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="required_time"
                                            value="{{ $withdrawMethod->required_time }}" class="form-control mb-0" />
                                        <div
                                            class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                            <select name="required_time_format" class="w-full h-full outline-none">
                                                @foreach (['minute' => 'Minutes', 'hour' => 'Hours', 'day' => 'Days'] as $key => $value)
                                                    <option @if ($withdrawMethod->required_time_format == $key) selected @endif
                                                        value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Select ‘All’ to make this payment method available in all countries">
                                        {{ __('Select Countries Authorized to Use') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="country[]" class="select2 form-control" placeholder="Countries" multiple>
                                    @foreach (getCountries() as $country)
                                        <option value="{{ $country['name'] }}"
                                            @if (
                                                !is_null($withdrawMethod->country) &&
                                                    in_array(
                                                        $country['name'],
                                                        is_array($withdrawMethod->country) ? $withdrawMethod->country : json_decode($withdrawMethod->country, true))) selected @endif>{{ $country['name'] }}
                                        </option>
                                    @endforeach
                                    <option value="All" @if (
                                        !is_null($withdrawMethod->country) &&
                                            in_array(
                                                'All',
                                                is_array($withdrawMethod->country) ? $withdrawMethod->country : json_decode($withdrawMethod->country, true))) selected @endif>
                                        {{ __('All') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Select branches where this withdraw method will be available">
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

                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area relative flex items-center space-x-7">
                                <label class="form-label !w-auto" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Toggle to enable or disable the method for users">
                                        {{ __('Status') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="form-switch ps-0">
                                    <input class="form-check-input" type="hidden" value="0" name="status" />
                                    <label
                                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="status" value="1" class="sr-only peer"
                                            @if ($withdrawMethod->status) checked @endif />
                                        <span
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area relative flex items-center space-x-7">
                                <label class="form-label !w-auto" for="">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Enable this to restrict the method to only users with no branch or specifically assigned branches">
                                        {{ __('Global Access') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="form-switch ps-0">
                                    <input class="form-check-input" type="hidden" value="0" name="is_global" />
                                    <label
                                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_global" value="1" class="sr-only peer"
                                            @if ($withdrawMethod->is_global ?? false) checked @endif />
                                        <span
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if ($type == 'manual')
                            <div class="col-span-12">
                                <a href="javascript:void(0)" id="generate"
                                    class="btn btn-dark btn-sm inline-flex items-center justify-center mb-3">
                                    Add Field option
                                </a>
                            </div>

                            <div class="addOptions col-span-12">
                                @foreach (json_decode($withdrawMethod->fields, true) as $key => $value)
                                    <div class="option-remove-row grid grid-cols-12 items-center gap-5 mb-3">
                                        <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                            <div class="input-area">
                                                <input name="fields[{{ $key }}][name]" class="form-control"
                                                    type="text" value="{{ $value['name'] }}" required
                                                    placeholder="Field Name">
                                            </div>
                                        </div>

                                        <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                            <div class="input-area">
                                                <select name="fields[{{ $key }}][type]"
                                                    class="form-control w-100">
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
                                            </div>
                                        </div>
                                        <div class="xl:col-span-3 md:col-span-6 col-span-12">
                                            <div class="input-area mb-0">
                                                <select name="fields[{{ $key }}][validation]"
                                                    class="form-control w-100">
                                                    <option value="required"
                                                        @if ($value['validation'] == 'required') selected @endif>
                                                        Required
                                                    </option>
                                                    <option value="nullable"
                                                        @if ($value['validation'] == 'nullable') selected @endif>
                                                        Optional
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="xl:col-span-1 md:col-span-6 col-span-12">
                                            <button
                                                class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl  delete-option-row delete_desc"
                                                type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="col-span-12 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8">

            </div>
        </div>
    </div>
@endsection

@section('payment-script')
    <script>
        const autoExchangeRatesEnabled = @json($autoExchangeRatesEnabled ?? false);

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
            $('#currency-selected').text(this.value);
            // Always fetch to update currency symbol, rate only updates if autoExchangeRatesEnabled
            get_rate($(this).val());
        });

        var i = Object.keys(JSON.parse(@json($withdrawMethod->fields))).length;
        $("#generate").on('click', function() {
            ++i;
            var form = `<div class="option-remove-row grid grid-cols-12 items-center gap-5 mb-3">
                    <div class="xl:col-span-4 md:col-span-6 col-span-12">
                      <div class="input-area">
                        <input name="fields[` + i + `][name]" class="form-control" type="text" value="" required placeholder="Field Name">
                      </div>
                    </div>

                    <div class="xl:col-span-4 md:col-span-6 col-span-12">
                      <div class="input-area">
                        <select name="fields[` + i + `][type]" class="form-control w-100">
                            <option value="text">Input Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="file">File upload</option>
                        </select>
                      </div>
                    </div>
                    <div class="xl:col-span-3 md:col-span-6 col-span-12">
                      <div class="input-area mb-0">
                        <select name="fields[` + i + `][validation]" class="form-control w-100">
                            <option value="required">Required</option>
                            <option value="nullable">Optional</option>
                        </select>
                      </div>
                    </div>

                    <div class="xl:col-span-1 md:col-span-6 col-span-12">
                      <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl  delete-option-row delete_desc" type="button">
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
                // Always fetch to update currency symbol, rate only updates if autoExchangeRatesEnabled
                if ($('#currency').val()) {
                    get_rate($('#currency').val());
                }
            })
        })

        // Control readonly state of rate field based on autoExchangeRatesEnabled and manual override
        $(document).ready(function() {
            // Fetch currency symbol on page load if currency is already selected
            const $currencyField = $('#currency');
            if ($currencyField.length) {
                const selectedCurrency = $currencyField.val();
                if (selectedCurrency) {
                    get_rate(selectedCurrency);
                }
            }
            
            if (!autoExchangeRatesEnabled) {
                $('.display-conversion-rate').prop('readonly', false);
                $('.currency-symbol').prop('readonly', false);
            } else {
                const $overrideToggle = $('input[name="is_rate_override_enabled"]');
                if ($overrideToggle.length) {
                    const syncReadonly = () => {
                        const enabled = $overrideToggle.is(':checked');
                        $('.display-conversion-rate').prop('readonly', !enabled);
                    };
                    syncReadonly();
                    $overrideToggle.on('change', syncReadonly);
                }
                $('.currency-symbol').prop('readonly', true);
            }
        });
    </script>
@endsection
