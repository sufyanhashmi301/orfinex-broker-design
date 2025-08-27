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
    <div class="md:flex justify-between items-center mb-5">
        <div class="">
            <ul class="m-0 p-0 list-none">
                <li class="inline-block relative top-[3px] text-base text-primary font-Inter">
                    <a href="{{route('user.dashboard')}}">
                        <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                        <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                    </a>
                </li>
                <li class="inline-block relative text-sm text-primary font-Inter">
                    {{ __('Dashboard') }}
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                </li>
                <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                    {{ __('Fund Board') }}
                </li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
                        {{ __('Money well funded') }}
                    </h4>
                    <div class="">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-slate-600 dark:text-slate-100 font-Inter font-normal">{{ __('Challenge Funding') }}</span>
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" value="" checked="checked" class="sr-only peer">
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                            <span class="text-sm text-slate-600 dark:text-slate-100 font-Inter font-normal">{{ __('Direct Funding') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-6 pt-0">
                    <div class="p-4 bg-slate-100 dark:bg-slate-700 shadow rounded-xl mb-5">
                        <div class="tab-content" id="plans-tab-content">
                            <div class="tab-pane fade show active" id="challenge-tab-pane" role="tabpanel" aria-labelledby="challenge-tab">
                                <div class="grid md:grid-cols-2 grid-cols-1">
                                    <div class="text-center space-x-3 space-y-3 md:space-y-0">
                                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center challenge-btn active" data-challenge="two_step_challenge" id="step-challenge__2">
                                            {{ __('2 Step Challenge') }}
                                        </button>
                                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center challenge-btn" data-challenge="single_step_challenge" id="step-challenge__1">
                                            {{ __('1 Step Challenge') }}
                                        </button>
                                    </div>
                                    <div class="text-center space-x-3 space-y-3 md:space-y-0" id="phaseButtons" style="">
                                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center phase-btn active" data-phase="1">
                                            {{ __('Phase 1') }}
                                        </button>
                                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center phase-btn" data-phase="2">
                                            {{ __('Phase 2') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="direct-tab-pane" role="tabpanel" aria-labelledby="direct-tab">
                                <div class="text-center md:text-start space-x-3 space-y-3 md:space-y-0">
                                    <button class="btn btn-sm btn-outline-dark leverage-btn active" data-leverage="5">
                                        {{ __('Leverage 1:5') }}
                                    </button>
                                    <button class="btn btn-sm btn-outline-dark leverage-btn" data-leverage="10">
                                        {{ __('Leverage 1:10') }}
                                    </button>
                                    <button class="btn btn-sm btn-outline-dark leverage-btn" data-leverage="20">
                                        {{ __('Leverage 1:20') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="" class="space-y-8">
                        <div class="input-area relative">
                            <div class="mb-3">
                                <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">{{ __('Account Balance') }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                    {{ __('Select your initial starting capital.') }}
                                </p>
                            </div>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="scheme" value="" data-price=" 139" checked="">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="flex-1 inline-flex justify-between items-center">
                                            <span class="dark:text-white">$10000.00</span>
                                            <span class="badge bg-slate-900 text-white capitalize">
                                                <strike>$ 199.00</strike> / $ 139.00
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="scheme" value="" data-price=" 139">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="flex-1 inline-flex justify-between items-center">
                                            <span class="dark:text-white">$10000.00</span>
                                            <span class="badge bg-slate-900 text-white capitalize">
                                                <strike>$ 199.00</strike> / $ 139.00
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="scheme" value="" data-price=" 139">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="flex-1 inline-flex justify-between items-center">
                                            <span class="dark:text-white">$10000.00</span>
                                            <span class="badge bg-slate-900 text-white capitalize">
                                                <strike>$ 199.00</strike> / $ 139.00
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="scheme" value="" data-price=" 139">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="flex-1 inline-flex justify-between items-center">
                                            <span class="dark:text-white">$10000.00</span>
                                            <span class="badge bg-slate-900 text-white capitalize">
                                                <strike>$ 199.00</strike> / $ 139.00
                                            </span>
                                        </span>
                                    </label>
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
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                <div class="warning-radio">
                                    <label class="flex items-center cursor-pointer px-3 py-2 rounded border dark:border-slate-700">
                                        <input type="radio" class="hidden priceInput" name="platform" value="" checked>
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="flex-1 inline-flex justify-end items-center">
                                            <div>
                                                <img src="{{ asset('frontend/images/mt-5-logo.png') }}" alt="">
                                                <span class="badge bg-slate-900 text-white capitalize mt-2">
                                                    {{ __('Best for Web Trading') }}
                                                </span>
                                            </div>
                                        </span>
                                    </label>
                                </div>
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
                                            <span class="badge bg-slate-900 text-white capitalize">
                                                +5%
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
                                            <span class="badge bg-slate-900 text-white capitalize">
                                                +10%
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
                                        <option value="1" class="py-2" selected="">{{ __('Main Wallet') }}</option>
                                        <option value="2" class="py-2">{{ __('Affiliate Wallet') }}</option>
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
                <div class="pricing-amount text-center text-2xl text-slate-900 dark:text-white font-bold mb-3">
                    <span class="amount">
                        $ <span class="order-info__total">139.00</span>
                    </span>
                </div>
                <div class="order__discount-code space-y-4 mb-10">
                    <div class="input-area">
                        <div class="relative">
                            <input type="text" class="form-control !pr-24" placeholder="{{ __('Discount Code') }}" id="discount-code">
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center text-sm px-2 dark:text-slate-100 hover:bg-slate-900 hover:text-white" id="apply-discount">
                                {{ __('Apply Code') }}
                            </button>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="relative">
                            <input type="text" class="form-control !pr-24" placeholder="{{ __('Affiliate Code') }}" id="referral-code">
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center text-sm px-2 dark:text-slate-100 hover:bg-slate-900 hover:text-white" id="add-referral">
                                {{ __('Apply Code') }}
                            </button>
                        </div>
                    </div>
                    <div class="form-group text-start">
                        <p class="text-xs dark:text-slate-100 mb-2">{{ __('By confirming the order:') }}</p>
                        <div class="flex w-full items-start">
                            <input type="checkbox" name="confirmation" class="custom-control-input mt-1" id="checkbox-terms" required="">
                            <label class="custom-control-label text-xs dark:text-slate-100 ml-2" for="checkbox-terms">
                                <span>
                                    {{ __('I declare that I have read and agree with') }}
                                    <a href="javascript:;" class="btn-link !text-xs">{{ __('Terms &amp; Condition') }}</a>
                                </span>
                            </label>
                        </div>
                        <span style="color:red;" class="text-sm hidden" id="term-validation">
                            {{ __('kindly accept the terms &amp; conditions for proceeding') }}
                        </span>
                    </div>
                </div>
                <a href="javascript:;" class="btn btn-outline-dark inline-flex items-center justify-center w-full proceed-payment">
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
