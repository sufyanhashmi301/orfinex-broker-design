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
    </style>
@endsection
@section('content')
    <div class="card mb-5">
        <div class="card-header noborder">
            <div>
                <h4 class="card-title mb-1">{{ __('Money well funded') }}</h4>
                <p class="card-text">{{ __('Direct Funding') }}</p>
            </div>
            <button type="button" class="btn btn-secondary light inline-flex items-center justify-center">
                {{ $schema->forexSchemaPhase1->funded_type ?? '' }}
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <form action="{{ route('user.forex-account-create-now') }}" method="post" enctype="multipart/form-data" id="payment-form" class="space-y-6">
                        @csrf
                        <div class="input-area relative">
                            <p class="text-slate-900 dark:text-white text-base font-medium leading-none mb-3">
                                {{ __('Allocated Funds') }}
                            </p>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                @foreach($schema->forexSchemaPhase1->forexSchemaPhaseRules as $index => $rule)
                                    <div class="success-radio">
                                        <label
                                            class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                            <input type="radio" class="hidden priceInput" name="rule_id"
                                                   value="{{ $rule->id }}"
                                                   data-price="{{ $rule->amount }}"
                                                   data-discount="{{ $rule->discount }}"
                                                   data-daily-dd="{{ $rule->daily_drawdown_limit }}"
                                                   data-max-dd="{{ $rule->max_drawdown_limit }}"
                                                   data-profit-target="{{ $rule->profit_target }}"
                                                   data-total="{{ $rule->amount - $rule->discount }}"
                                                {{ $index == 0 ? 'checked' : '' }}>
                                            <span
                                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
{{--                                            @if($rule->discount > 0)--}}
{{--                                                <strike>${{ $rule->amount ?? '' }}</strike> /--}}
{{--                                                <span--}}
{{--                                                    class="dark:text-white">${{ $rule->amount - $rule->discount ?? '' }}</span>--}}
{{--                                            @else--}}
                                                <span class="dark:text-white">${{ $rule->allotted_funds ?? '' }}</span>
{{--                                            @endif--}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Added display elements for Daily DD, Max DD, and Profit Target --}}
                        <div class="input-area relative">
                            <p class="text-slate-900 dark:text-white text-base font-medium leading-none mb-3">
                                {{ __('Rules') }}
                            </p>
                            <div class="grid md:grid-cols-3 grid-cols-1 gap-5">
                                <div class="flex justify-between items-center rounded border dark:border-slate-700 px-3 py-4">
                                    <span class="leading-none">
                                        <span class="leading-none dark:text-white text-sm font-medium block mb-1">
                                            {{ __('Daily DD') }}
                                        </span>
                                        <small class="leading-none dark:text-slate-100 text-xs">{{ __('Instead of monthly') }}</small>
                                    </span>
                                    <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize daily-dd">
                                        {{ '' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center rounded border dark:border-slate-700 px-3 py-4">
                                    <span class="leading-none">
                                        <span class="leading-none dark:text-white text-sm font-medium block mb-1">
                                            {{ __('Max DD') }}
                                        </span>
                                        <small class="leading-none dark:text-slate-100 text-xs">{{ __('Instead of monthly') }}</small>
                                    </span>
                                    <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize max-dd">
                                        {{ '' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center rounded border dark:border-slate-700 px-3 py-4">
                                    <span class="leading-none">
                                        <span class="leading-none dark:text-white text-sm font-medium block mb-1">
                                            {{ __('Profit Target') }}
                                        </span>
                                        <small class="leading-none dark:text-slate-100 text-xs">{{ __('Instead of monthly') }}</small>
                                    </span>
                                    <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize profit-target">
                                        {{ '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Your Static Code for Platform --}}
                        <div class="input-area relative">
                            <div class="mb-3">
                                <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">{{ __('Platform') }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">{{ __('Please select your trading platform.') }}</p>
                            </div>
                            <div class="success-radio">
                                <label class="flex items-center cursor-pointer rounded border dark:border-slate-700 px-3 py-4">
                                    <input type="radio" class="hidden priceInput" name="platform" value="" checked>
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="flex-1 inline-flex items-center">
                                        <img src="{{ asset('frontend/images/mt-5-logo.png') }}" class="h-5" alt="">
                                        <span class="text-sm font-normal text-slate-600 dark:text-slate-400 ml-2">{{ __('Best for Web Trading') }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Your Static Code for Addons --}}
                        <div class="input-area relative">
                            <div class="mb-3">
                                <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">{{ __('Addons') }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">{{ __('Tailor your account to suit your trading style and preference.') }}</p>
                            </div>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                <div class="checkbox-area success-checkbox">
                                    <label class="w-full inline-flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="checkbox" class="hidden addon-checkbox" name="weekly_payout" id="biWeeklyPayouts" data-price="5" value="{{the_hash(5)}}" checked="checked">
                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="flex-1 inline-flex justify-between items-center">
                                            <span class="leading-none">
                                                <span class="leading-none dark:text-white text-sm block mb-1">{{ __('Bi-Weekly Payouts') }}</span>
                                                <small class="leading-none dark:text-slate-100 text-xs">{{ __('Instead of Monthly') }}</small>
                                            </span>
                                            <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">{{ __('+5%') }}</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="checkbox-area success-checkbox">
                                    <label class="w-full inline-flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="checkbox" class="hidden addon-checkbox" name="swap_free" id="swap_free" data-price="10" value="{{the_hash(10)}}">
                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="flex-1 inline-flex justify-between items-center">
                                            <span class="leading-none">
                                                <span class="leading-none dark:text-white text-sm block mb-1">{{ __('Swap Free (Islamic)') }}</span>
                                                <small class="leading-none dark:text-slate-100 text-xs">{{ __('Efficient Group') }}</small>
                                            </span>
                                            <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">{{ __('+10%') }}</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- Price, Discount, and Total Section --}}
        <div class="lg:col-span-4 col-span-12">
            <div class="card order-info p-6 rounded-lg">
                <ul class="space-y-3 border-b dark:border-slate-700 pb-5">
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Price') }}</span>
                        <span class="price-display">{{ '' }}</span>
                    </li>
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Discount') }}</span>
                        <span class="discount-display">{{ '' }}</span>
                    </li>
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Addons') }}</span>
                        <span class="addons-display">{{ '' }}</span>
                    </li>
                </ul>
                <div class="pricing-amount py-5 border-b dark:border-slate-700 mb-5">
                    <div class="amount flex items-center justify-between text-slate-900 dark:text-white">
                        <span>{{ __('Total Amount') }}</span>
                        <span class="total-display order-info__total text-xl font-semibold">{{ '' }}</span>
                    </div>
                </div>
                <div class="order__discount-code space-y-4 mb-10">
                    <ul class="space-y-3 mb-5">
                        @if(setting('aml_policy_show','document_links',false))
                            <li>
                                <a href="{{ setting('aml_policy_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('AML Policy') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if(setting('privacy_policy_show','document_links',false))
                            <li>
                                <a href="{{ setting('privacy_policy_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('Privacy Policy') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if(setting('client_agreement_show','document_links',false))
                            <li>
                                <a href="{{ setting('client_agreement_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('Client Agreement') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if(setting('complaints_handling_policy_show','document_links',false))
                            <li>
                                <a href="{{ setting('complaints_handling_policy_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('Complaints Handling Policy') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if(setting('cookies_policy_show','document_links',false))
                            <li>
                                <a href="{{ setting('cookies_policy_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('Cookies Policy') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if(setting('IB_partner_agreement_show','document_links',false))
                            <li>
                                <a href="{{ setting('IB_partner_agreement_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('IB Partner Agreement') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if(setting('order_execution_policy_show','document_links',false))
                            <li>
                                <a href="{{ setting('order_execution_policy_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('Order Execution Policy') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if(setting('risk_disclosure_show','document_links',false))
                            <li>
                                <a href="{{ setting('risk_disclosure_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('Risk Disclosure') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                        @if(setting('US_clients_policy_show','document_links',false))
                            <li>
                                <a href="{{ setting('US_clients_policy_link', 'document_links', 'javascript:void(0);')}}" class="inline-flex items-center justify-between text-sm w-full">
                                    <span class="underline">{{ __('US Clients Policy') }}</span>
                                    <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <div class="form-group text-start">
                        <div class="flex w-full items-start">
                            <input type="checkbox" name="confirmation" class="custom-control-input mt-1"
                                   id="checkbox-terms" required="">
                            <label class="custom-control-label text-xs dark:text-slate-100 ml-2" for="checkbox-terms">
                                {{ __('By confirming the order: I declare that i have read and agree with Terms & Conditions By confirming the order: I declare that i have read and agree with Terms & Conditions.') }}
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
        $(document).ready(function () {
            // Call to set initial values on page load
            updatePriceDisplay();

            // Update values when radio button is changed
            $('input[name="rule_id"]').on('change', updatePriceDisplay);

            // Update values when addon checkboxes are changed
            $('.addon-checkbox').on('change', updatePriceDisplay);

            // Handle form submission with checkbox validation
            $('.proceed-payment').on('click', function (e) {
                e.preventDefault(); // Prevent default behavior
                if ($('#checkbox-terms').is(':checked')) {
                    // Checkbox is checked, submit the form
                    $('#payment-form').submit();
                } else {
                    // Checkbox is not checked, show error message in red
                    $('#term-validation').removeClass('hidden').css('color', 'red');
                }
            });

            function updatePriceDisplay() {
                let checkedInput = $('input[name="rule_id"]:checked');
                let basePrice = parseFloat(checkedInput.data('price')) || 0;
                let discount = parseFloat(checkedInput.data('discount')) || 0;
                let addonPrice =  0;
                let total = basePrice - discount;

                let dailyDD = checkedInput.data('daily-dd') || '';
                let maxDD = checkedInput.data('max-dd') || '';
                let profitTarget = checkedInput.data('profit-target') || '';

                // Calculate percentage addon prices from checked checkboxes
                $('.addon-checkbox:checked').each(function () {
                     addonPrice = parseFloat($(this).data('price')) || 0;
                    total += basePrice * (addonPrice / 100); // Add percentage to total
                });

                // Update values in multiple places using classes
                $('.price-display').text('$' + basePrice.toFixed(2));
                $('.discount-display').text('$' + discount.toFixed(2));
                $('.addons-display').text('$' + addonPrice.toFixed(2));
                $('.total-display').text('$' + total.toFixed(2));
                $('.daily-dd').text(dailyDD);
                $('.max-dd').text(maxDD);
                $('.profit-target').text(profitTarget);
            }
        });


    </script>
@endsection
