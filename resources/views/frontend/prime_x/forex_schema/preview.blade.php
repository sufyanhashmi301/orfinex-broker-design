@extends('frontend::layouts.user')
@section('title')
    {{ __('Fund Plan') }}
@endsection
@section('style')
    <style>
        .select2-container .select2-selection--single {
            height: 48px !important;
        }

        .dark .select2-container--default .select2-selection--single {
            border-color: rgb(51 65 85)
        }

        .input-group {
            display: flex;
            align-items: stretch;
        }

        .input-group .form-control,
        .input-group .btn {
            height: auto;
            /* Ensures same height */
            flex: 1;
            /* Ensures both take the same space */
        }

        .input-group .btn {
            margin-left: 10px;
            /* Add some spacing between the input and the button */
        }
    </style>
@endsection
@section('content')
    <div class="card mb-5">
        <div class="card-header noborder">
            <div>
                <h4 class="card-title mb-1">{{ $account_type->title }}</h4>
                <p class="card-text" style="text-transform: capitalize">{{ $account_type->type }}</p>
            </div>
            <button type="button" class="btn btn-primary cursor-default inline-flex items-center justify-center">
                Evaluation Step
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <form action="{{ route('user.investment.store') }}" method="post" enctype="multipart/form-data"
                        id="payment-form" class="space-y-6">
                        @csrf
                        <input type="hidden" id="scheme_type" value="{{ the_hash($account_type->forexSchemaPhase1->type) }}">
                        <input type="hidden" id="discount-id" name="discount_id" >
                        <input type="hidden" id="addon-ids" name="addons" value="">
                        <div class="input-area relative">
                            <p class="text-slate-900 dark:text-white text-base font-medium leading-none mb-3">
                                {{ __('Allocated Funds') }}
                            </p>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                @foreach ($account_type->forexSchemaPhase1->accountTypePhaseRules as $index => $rule)
                                    <div class="success-radio">
                                        <label
                                            class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                            <input type="radio" class="hidden priceInput" name="rule_id"
                                                value="{{ $rule->id }}" data-price="{{ $rule->amount }}"
                                                data-discount="{{ $rule->discount }}"
                                                data-daily-dd="{{ $rule->daily_drawdown_limit }}"
                                                data-max-dd="{{ $rule->max_drawdown_limit }}"
                                                data-profit-target="{{ $rule->profit_target }}"
                                                data-total="{{ $rule->amount - $rule->discount }}"
                                                {{ $index == 0 ? 'checked' : '' }}>
                                            <span
                                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            {{--                                            @if ($rule->discount > 0) --}}
                                            {{--                                                <strike>${{ $rule->amount ?? '' }}</strike> / --}}
                                            {{--                                                <span --}}
                                            {{--                                                    class="dark:text-white">${{ $rule->amount - $rule->discount ?? '' }}</span> --}}
                                            {{--                                            @else --}}
                                            <span class="dark:text-white">${{ $rule->allotted_funds ?? '' }}</span>
                                            {{--                                            @endif --}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Added display elements for Daily DD, Max DD, and Profit Target --}}
                        <div class="input-area relative">
                            <p class="text-slate-900 dark:text-white text-base font-medium leading-none mb-3">
                                {{ __('Trading Rules') }}
                            </p>
                            <div class="grid md:grid-cols-3 grid-cols-1 gap-5">
                                <div
                                    class="flex justify-between items-center rounded border dark:border-slate-700 px-3 py-4">
                                    <span class="leading-none">
                                        <span class="leading-none dark:text-white text-sm font-medium block mb-1">
                                            {{ __('Daily Drawdown') }}
                                        </span>
                                        <small class="leading-none dark:text-slate-100 text-xs">
                                            {{ __('Max loss per day') }}
                                        </small>
                                    </span>
                                    <span
                                        class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize daily-dd">
                                        {{ '' }}
                                    </span>
                                </div>
                                <div
                                    class="flex justify-between items-center rounded border dark:border-slate-700 px-3 py-4">
                                    <span class="leading-none">
                                        <span class="leading-none dark:text-white text-sm font-medium block mb-1">
                                            {{ __('Max Drawdown') }}
                                        </span>
                                        <small class="leading-none dark:text-slate-100 text-xs">
                                            {{ __('Maximum allowable loss on the account') }}
                                        </small>
                                    </span>
                                    <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize max-dd">
                                        {{ '' }}
                                    </span>
                                </div>
                                <div
                                    class="flex justify-between items-center rounded border dark:border-slate-700 px-3 py-4">
                                    <span class="leading-none">
                                        <span class="leading-none dark:text-white text-sm font-medium block mb-1">
                                            {{ __('Profit Target') }}
                                        </span>
                                        <small class="leading-none dark:text-slate-100 text-xs">
                                            {{ __('Required profit to achieve') }}
                                        </small>
                                    </span>
                                    <span
                                        class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize profit-target">
                                        {{ '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Your Static Code for Platform --}}
                        <div class="input-area relative">
                            <div class="mb-3">
                                <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">
                                    {{ __('Platform') }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                    {{ __('Please select your trading platform.') }}</p>
                            </div>
                            <div class="success-radio">
                                <label
                                    class="flex items-center cursor-pointer rounded border dark:border-slate-700 px-3 py-4">
                                    {{-- <input type="radio" class="hidden priceInput" name="platform" value="" checked> --}}
                                    <span
                                        class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="flex-1 inline-flex items-center">
                                        <img src="{{ asset('frontend/images/mt-5-logo.png') }}" class="h-5"
                                            alt="">
                                        <span
                                            class="text-sm font-normal text-slate-600 dark:text-slate-400 ml-2">{{ __('Best for Web Trading') }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Your Static Code for Addons --}}
                        <div class="input-area relative">
                            <div class="mb-3">
                                <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">
                                    {{ __('Addons') }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                    {{ __('Tailor your account to suit your trading style and preference.') }}</p>
                            </div>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                
                                @forelse ($addons as $addon)
                                    <div class="checkbox-area success-checkbox">
                                        <label class="w-full inline-flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                            <input type="checkbox" class="hidden addon-checkbox" data-id="{{ $addon->id }}" data-amount="{{ $addon->amount }}" data-amount-type="{{ $addon->amount_type }}"  >
                                            <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                            </span>
                                            <span class="flex-1 inline-flex justify-between items-center">
                                                <span class="leading-none">
                                                    <span class="leading-none dark:text-white text-sm block mb-1">{{ $addon->name }}</span>
                                                    <small class="leading-none dark:text-slate-100 text-xs">{{ $addon->description }}</small>
                                                </span>
                                                <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">+{{ $addon->amount }}{{ $addon->amount_type == 'fixed' ? ' ' . $currency : '%' }}</span>
                                            </span>
                                        </label>
                                    </div>
                                @empty
                                    <div class="checkbox-area success-checkbox">
                                        <label
                                            class="w-full inline-flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                            <input type="checkbox" class="hidden addon-checkbox" id="no-addons"
                                                data-amount="0" checked="checked">
                                            <span
                                                class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('images/icon/ck-white.svg') }}" alt=""
                                                    class="h-[10px] w-[10px] block m-auto opacity-0">
                                            </span>
                                            <span class="flex-1 inline-flex justify-between items-center">
                                                <span class="leading-none">
                                                    <span
                                                        class="leading-none dark:text-white text-sm block mb-1">{{ __('No addons') }}</span>
                                                    <small
                                                        class="leading-none dark:text-slate-100 text-xs">{{ __('No available') }}</small>
                                                </span>
                                                <span
                                                    class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">{{ __('N/A') }}</span>
                                            </span>
                                        </label>
                                    </div>
                                @endforelse
                                
                                                              
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- Price, Discount, and Total Section --}}
        <div class="lg:col-span-4 col-span-12">
            <div class="card order-info p-6 rounded-lg">
                <div class="input-area">
                    <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">
                        {{ __('Discount Code') }}</p>
                    <div class="input-group">
                        <input type="text" id="discount-code" class="form-control"
                            placeholder="{{ __('Enter discount code') }}">
                        <button type="button" class="btn btn-primary" id="verify-discount">{{ __('Verify') }}</button>
                    </div>
                    <p class="text-sm mt-2" id="discount-message"></p>
                </div>



                <ul class="space-y-3 border-b dark:border-slate-700 pb-5">
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Price') }}</span>
                        <span class="price-display">{{ '' }}</span>
                    </li>
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Addons') }}</span>
                        <span class="addons-display">{{ '' }}</span>
                    </li>
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Discount') }}</span>
                        <span class="discount-display">{{ '' }}</span>
                    </li>
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Coupon Discount') }}</span>
                        <span class="coupon-discount-display">{{ '' }}</span>
                    </li>

                </ul>
                <div class="pricing-amount py-5 border-b dark:border-slate-700 mb-5">
                    <div class="amount flex items-center justify-between text-slate-900 dark:text-white">
                        <span>{{ __('Total Amount') }}</span>
                        <span class="total-display order-info__total text-xl font-semibold">{{ '' }}</span>
                    </div>
                </div>
                <div class="order__discount-code space-y-4 mb-10">
                    <p class="text-sm font-medium dark:text-slate-100 mb-1">
                        {{ __('Please review the following policies before proceeding:') }}
                    </p>
                    <ul class="space-y-3 mb-5">

                        @foreach ($legal_links as $link)

                            @php
                                $is_enabled = false;
                                $settings_name = '';
                                if(str_contains($link->name, '_purchase') ) {
                                    if(setting($link->name) == 1) {
                                        $is_enabled = true;
                                        $settings_name = str_replace('_on_purchase', '', $link->name);
                                        $label = str_replace( '_', ' ', str_replace( 'legal_', '', $settings_name) );
                                    }
                                    // dd($settings_name);
                                }
                            @endphp
                            @if ($is_enabled)
                                <li>
                                    <a href="{{ setting($settings_name . '_link', 'legal_links', 'javascript:void(0);') }}"
                                        class="inline-flex items-center justify-between text-sm w-full">
                                        <div class="flex items-center">
                                            <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                            <span class="underline" style="text-transform: capitalize">{{ $label }}</span>
                                        </div>
                                        <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                    </a>
                                </li> 
                            @endif
                            
                        @endforeach

                        {{-- @if (setting('terms_and_conditions_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('terms_and_conditions_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">Terms and Conditions</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif --}}
                        {{-- @if (setting('privacy_policy_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('privacy_policy_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">{{ __('Privacy Policy') }}</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if (setting('client_agreement_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('client_agreement_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">{{ __('Client Agreement') }}</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if (setting('complaints_handling_policy_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('complaints_handling_policy_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">{{ __('Complaints Handling Policy') }}</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if (setting('cookies_policy_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('cookies_policy_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">{{ __('Cookies Policy') }}</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if (setting('IB_partner_agreement_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('IB_partner_agreement_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">{{ __('IB Partner Agreement') }}</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if (setting('order_execution_policy_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('order_execution_policy_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">{{ __('Order Execution Policy') }}</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if (setting('risk_disclosure_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('risk_disclosure_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">{{ __('Risk Disclosure') }}</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if (setting('US_clients_policy_show', 'document_links', false))
                            <li>
                                <a href="{{ setting('US_clients_policy_link', 'document_links', 'javascript:void(0);') }}"
                                    class="inline-flex items-center justify-between text-sm w-full">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-base mr-1" icon="lucide:file-text"></iconify-icon>
                                        <span class="underline">{{ __('US Clients Policy') }}</span>
                                    </div>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif --}}
                    </ul>
                    <div class="form-group text-start">
                        <div class="flex w-full items-start">
                            <input type="checkbox" name="confirmation" class="custom-control-input mt-1"
                                id="checkbox-terms" required="">
                            <label class="custom-control-label text-xs dark:text-slate-100 ml-2" for="checkbox-terms">
                                {{ __('By confirming this order, I declare that I have read and agree to the Terms & Conditions. By proceeding, I acknowledge understanding and acceptance of all the policies listed.') }}
                            </label>
                        </div>
                        <span class="text-sm text-primary hidden" id="term-validation">
                            {{ __('kindly accept the terms &amp; conditions for proceeding') }}
                        </span>
                    </div>
                </div>
                <a href="javascript:;"
                    class="btn btn-primary inline-flex items-center justify-center w-full proceed-payment">
                    {{ __('Proceed With Payment') }}
                </a>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let discountAmount = 0;
            let discountType = null; // This will store the type of discount ('percentage' or 'fixed')

            // Call to set initial values on page load
            updatePriceDisplay();

            // Update values when radio button is changed
            $('input[name="rule_id"]').on('change', updatePriceDisplay);

            // Update values when addon checkboxes are changed
            $('.addon-checkbox').on('change', updatePriceDisplay);

            // Handle form submission with checkbox validation
            $('.proceed-payment').on('click', function(e) {
                e.preventDefault(); // Prevent default behavior
                if ($('#checkbox-terms').is(':checked')) {
                    // Checkbox is checked, submit the form
                    $('#payment-form').submit();
                } else {
                    // Checkbox is not checked, show error message in red
                    $('#term-validation').removeClass('hidden').css('color', 'red');
                }
            });
            $('#verify-discount').on('click', function() {
                let discountCode = $('#discount-code').val().trim();
                let schemeType = $('#scheme_type').val().trim();

                if (!discountCode) {
                    $('#discount-message').text('{{ __('Please enter a discount code.') }}').css('color',
                        'red');
                    return;
                }
                // AJAX call to verify the discount code
                $.ajax({
                    url: "{{ route('user.verify-discount') }}", // Ensure this route exists
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        code: discountCode,
                        scheme_type: schemeType
                    },
                    success: function(response) {
                        if (response.valid) {
                            discountAmount = response
                            .discount_amount; // Store the discount amount
                            discountType = response
                            .discount_type; // Store the discount type ('percentage' or 'fixed')
                            $('#discount-id').val(response.discount_id);
                            $('#discount-message').text(
                                '{{ __('Discount applied successfully.') }}').css('color',
                                'green');
                            updatePriceDisplay(); // Recalculate and update the total price
                        } else {
                            discountAmount = 0; // Reset discount if invalid
                            discountType = null; // Reset discount type
                            $('#discount-id').val('');
                            $('#discount-message').text(response.message).css('color', 'red');
                            updatePriceDisplay(); // Recalculate without discount
                        }
                    },
                    error: function() {
                        $('#discount-message').text(
                                '{{ __('Error verifying discount code. Please try again.') }}')
                            .css('color', 'red');
                    }
                });
            });

            function updatePriceDisplay() {
                let checkedInput = $('input[name="rule_id"]:checked');
                let basePrice = parseFloat(checkedInput.data('price')) || 0;
                let ruleDiscount = parseFloat(checkedInput.data('discount')) ||
                0; // Discount directly from the rule
                let addon = 0;
                let total = basePrice - ruleDiscount;

                let dailyDD = checkedInput.data('daily-dd') || '';
                let maxDD = checkedInput.data('max-dd') || '';
                let profitTarget = checkedInput.data('profit-target') || '';

                // Calculate percentage addon prices from checked checkboxes
                let addon_ids = ''
                $('.addon-checkbox:checked').each(function() {
                    let addonPrice = parseFloat($(this).data('amount')) || 0;
                    let addonType = $(this).data('amount-type');

                    if (addonType === 'percentage') {
                        addon += basePrice * (addonPrice / 100); 
                    } else if (addonType === 'fixed') {
                        addon += addonPrice; 
                    }

                    addon_ids += $(this).data('id') + ','
                });
                $('#addon-ids').val(addon_ids.slice(0, -1))

                total += addon;

                // Apply the discount if a valid discount is present
                if (discountType === 'percentage') {
                    total -= (basePrice * (discountAmount / 100)); // Apply percentage discount
                } else if (discountType === 'fixed') {
                    total -= discountAmount; // Apply fixed discount amount
                }

                // Ensure the total is not negative
                total = total < 0 ? 0 : total;

                // Update values in multiple places using classes
                $('.price-display').text('$' + basePrice.toFixed(2));
                $('.discount-display').text('$' + ruleDiscount.toFixed(2));
                $('.coupon-discount-display').text(discountType === 'percentage' ? discountAmount + '%' : '$' +
                    discountAmount.toFixed(2));
                $('.addons-display').text('$' + addon.toFixed(2));
                $('.total-display').text('$' + total.toFixed(2));
                $('.daily-dd').text(dailyDD);
                $('.max-dd').text(maxDD);
                $('.profit-target').text(profitTarget);
            }

        });
    </script>
@endsection
