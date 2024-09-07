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
                {{ __('2 Step Challenge') }}
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <form action="" class="space-y-8">
                        <div class="input-area relative">
                            <p class="text-slate-900 dark:text-white text-base font-medium leading-none mb-3">
                                {{ __('Allocated Funds') }}
                            </p>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="scheme" value="" data-price=" 139" checked="">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="dark:text-white">{{ __('$10000.00') }}</span>
                                    </label>
                                </div>
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="scheme" value="" data-price=" 139">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="dark:text-white">{{ __('$10000.00') }}</span>
                                    </label>
                                </div>
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="scheme" value="" data-price=" 139">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="dark:text-white">{{ __('$10000.00') }}</span>
                                    </label>
                                </div>
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="scheme" value="" data-price=" 139">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="dark:text-white">{{ __('$10000.00') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
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
                                    <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                                        {{ __('500') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center rounded border dark:border-slate-700 px-3 py-4">
                                    <span class="leading-none">
                                        <span class="leading-none dark:text-white text-sm font-medium block mb-1">
                                            {{ __('Max DD') }}
                                        </span>
                                        <small class="leading-none dark:text-slate-100 text-xs">{{ __('Instead of monthly') }}</small>
                                    </span>
                                    <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                                        {{ __('1000') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center rounded border dark:border-slate-700 px-3 py-4">
                                    <span class="leading-none">
                                        <span class="leading-none dark:text-white text-sm font-medium block mb-1">
                                            {{ __('Profit Target') }}
                                        </span>
                                        <small class="leading-none dark:text-slate-100 text-xs">{{ __('Instead of monthly') }}</small>
                                    </span>
                                    <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                                        {{ __('500') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <div class="mb-3">
                                <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">{{ __('Platform') }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                    {{ __('Please select your trading platform.') }}
                                </p>
                            </div>
                            <div class="warning-radio">
                                <label class="flex items-center cursor-pointer rounded border dark:border-slate-700 px-3 py-4">
                                    <input type="radio" class="hidden priceInput" name="platform" value="" checked>
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="flex-1 inline-flex items-center">
                                            <img src="{{ asset('frontend/images/mt-5-logo.png') }}" class="h-5" alt="">
                                            <span class="text-sm font-normal text-slate-600 dark:text-slate-400 ml-2">
                                                {{ __('Best for Web Trading') }}
                                            </span>
                                        </span>
                                </label>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <div class="mb-3">
                                <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">{{ __('Addons') }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                    {{ __('Tailor your account to suit your trading style and preference.') }}
                                </p>
                            </div>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                <div class="checkbox-area warning-checkbox">
                                    <label class="w-full inline-flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="checkbox" class="hidden" name="weekly_payout" id="biWeeklyPayouts" data-price="5" value="" checked="checked">
                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="flex-1 inline-flex justify-between items-center">
                                            <span class="leading-none">
                                                <span class="leading-none dark:text-white text-sm block mb-1">
                                                    {{ __('Bi-Weekly Payouts') }}
                                                </span>
                                                <small class="leading-none dark:text-slate-100 text-xs">{{ __('Instead of Monthly') }}</small>
                                            </span>
                                            <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                                                {{ __('+5%') }}
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="checkbox-area warning-checkbox">
                                    <label class="w-full inline-flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="checkbox" class="hidden" name="swap_free" id="swap_free" data-price="10" value="">
                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="flex-1 inline-flex justify-between items-center">
                                            <span class="leading-none">
                                                <span class="leading-none dark:text-white text-sm block mb-1">
                                                    {{ __('Swap Free (Islamic)') }}
                                                </span>
                                                <small class="leading-none dark:text-slate-100 text-xs">{{ __('Efficient Group') }}</small>
                                            </span>
                                            <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                                                {{ __('+10%') }}
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <div class="mb-3">
                                <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">
                                    {{ __('Payment Gateway') }}
                                </p>
                                <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                    {{ __('Select your source to pay for service charges.') }}
                                </p>
                            </div>
                            <div class="grid grid-cols-1 gap-5 mb-5">
                                <div class="input-group select2-lg">
                                    <select name="account_from" class="select2 form-control !text-lg w-full">
                                        <option value="1" class="py-2" selected="">Main Wallet</option>
                                        <option value="2" class="py-2">Affiliate Wallet</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12">
            <div class="card order-info p-6 rounded-lg">
                <ul class="space-y-3 border-b dark:border-slate-700 pb-5">
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Price') }}</span>
                        <span>{{ __('$1490,00.00') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-base">
                        <span>{{ __('Discount') }}</span>
                        <span>{{ __('$500.00') }}</span>
                    </li>
                </ul>
                <div class="pricing-amount py-5 border-b dark:border-slate-700 mb-5">
                    <div class="amount flex items-center justify-between text-slate-900 dark:text-white">
                        <span>{{ __('Total Amount') }}</span>
                        <span class="order-info__total text-xl font-semibold">{{ __('$1490,00.00') }}</span>
                    </div>
                </div>
                <div class="order__discount-code space-y-4 mb-10">
                    <ul class="space-y-3 mb-5">
                        <li>
                            <a href="" class="inline-flex items-center justify-between text-sm w-full">
                                <span class="underline">{{ __('Terms and Condition') }}</span>
                                <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a href="" class="inline-flex items-center justify-between text-sm w-full">
                                <span class="underline">{{ __('Privacy Policy') }}</span>
                                <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a href="" class="inline-flex items-center justify-between text-sm w-full">
                                <span class="underline">{{ __('Customer Agreement') }}</span>
                                <iconify-icon class="text-lg" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </li>
                    </ul>
                    <div class="form-group text-start">
                        <div class="flex w-full items-start">
                            <input type="checkbox" name="confirmation" class="custom-control-input mt-1" id="checkbox-terms" required="">
                            <label class="custom-control-label text-xs dark:text-slate-100 ml-2" for="checkbox-terms">
                                {{ __('By confirming the order: I declare that i have read and agree with Terms & Conditions By confirming the order: I declare that i have read and agree with Terms & Conditions.') }}
                            </label>
                        </div>
                        <span class="text-sm text-primary hidden" id="term-validation">
                            {{ __('kindly accept the terms &amp; conditions for proceeding') }}
                        </span>
                    </div>
                </div>
                <a href="javascript:;" class="btn btn-dark inline-flex items-center justify-center w-full proceed-payment">
                    {{ __('Proceed With Payment') }}
                </a>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>
    <script>
        const inputPhone = document.querySelector("#phone_number");
        const inputWhatsapp = document.querySelector("#profile_whatsapp");
        window.intlTelInput(inputPhone, {
            showSelectedDialCode: true,
            utilsScript: "{{ asset('frontend/js/utils.js') }}",
        });
        window.intlTelInput(inputWhatsapp, {
            showSelectedDialCode: true,
            utilsScript: "{{ asset('frontend/js/utils.js') }}",
        });

        $(".form-select-img .form-select").select2({
            matcher: matchCustom,
            templateResult: formatState,
            templateSelection: formatState
        });

        function stringMatch(term, candidate) {
            return candidate && candidate.toLowerCase().indexOf(term.toLowerCase()) >= 0;
        }

        function matchCustom(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data;
            }
            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }
            // Match text of option
            if (stringMatch(params.term, data.text)) {
                return data;
            }
            // Match attribute "data-foo" of option
            if (stringMatch(params.term, $(data.element).attr('data-des'))) {
                return data;
            }
            // Return `null` if the term should not be displayed
            return null;
        }

        function formatState(opt) {
            if (!opt.id) {
                return opt.text.toUpperCase();
            }

            var optimage = $(opt.element).attr('data-image');
            var optdes = $(opt.element).attr('data-des');
            // console.log(optdes);
            if (!optimage) {
                return opt.text.toUpperCase();
            } else {
                var $opt = $(
                    '<div class="flex items-center"><div class="coin-icon"><img src="' + optimage + '" class="h-[40px] mr-2" /></div><div class="leading-none"><span class="text-sm">' + opt.text.toUpperCase() + '</span><ul class="flex flex-nowrap items-center text-xs">' + optdes + '</ul></div></div>'
                );
                return $opt;
            }
        }
    </script>
@endsection
