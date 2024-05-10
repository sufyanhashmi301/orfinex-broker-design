@extends('frontend::layouts.user')

@section('title', __('Payments'))
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
                <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                    <a href="{{route('user.dashboard')}}">
                        <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                        <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                    </a>
                </li>
                <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                    Dashboard
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                </li>
                <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                    Fund Board
                </li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <form action="{{ route('user.pricing.invest.preview') }}" method="POST" class="form-validate is-alter form-profile" id="invest-form">
                        @csrf
                        <input type="hidden" name="amount" id="scheme_amount">
                        {{--<input type="hidden" name="leverage" id="leverage" value="{{ the_hash(5) }}">--}}
                        <input type="hidden" name="discount" id="discount_amount" value="0">
                        <input type="hidden" name="days_to_pass" id="days_to_pass" value="year">
                        <input type="hidden" name="profit_share_user" id="profit_share_user" value="50">
                        <input type="hidden" name="profit_share_admin" id="profit_share_admin" value="50">
                        <input type="hidden" name="payouts" id="payouts" value="monthly">

                        <div class="flex flex-wrap justify-between items-center mb-6">
                            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
                                Money well funded
                            </h4>
                            <div class="">
                                <ul class="nav nav-tabs flex items-center space-x-2" id="plans-tabs" role="tablist">
                                    <li class="nav-item p-0" role="presentation">
                                        <a href="#challenge-tab-pane" class="nav-link p-0 type active" id="challenge-tab" data-bs-toggle="pill" data-bs-target="#challenge-tab-pane" aria-controls="challenge-tab-pane" aria-selected="true">
                                            <span class="tab-btn">Challenge Funding</span>
                                        </a>
                                    </li>
                                    <span class="pricing-tab-switcher"></span>
                                    <li class="nav-item p-0" role="presentation">
                                        <a href="#direct-tab-pane" class="nav-link p-0 type " id="direct-tab" data-bs-toggle="pill" data-bs-target="#direct-tab-pane" aria-controls="direct-tab-pane" aria-selected="false">
                                            <span class="tab-btn">Direct Funding</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-100 dark:bg-slate-700 shadow rounded-xl mb-5">
                            <div class="tab-content" id="plans-tab-content">
                                <div class="tab-pane fade show active" id="challenge-tab-pane" role="tabpanel" aria-labelledby="challenge-tab" tabindex="0">
                                    <div class="grid md:grid-cols-2 grid-cols-1">
                                        <div class="text-center space-x-3 space-y-3 md:space-y-0">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center challenge-btn @if($subType == \App\Enums\FundedSchemeSubTypes::TWO_STEP_CHALLENGE || $type == 'direct') active @endif" data-challenge="two_step_challenge" id="step-challenge__2">
                                                {{fsst2n(\App\Enums\FundedSchemeSubTypes::TWO_STEP_CHALLENGE)}}
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center challenge-btn ms-2 @if($subType == \App\Enums\FundedSchemeSubTypes::SINGLE_STEP_CHALLENGE ) active @endif " data-challenge="single_step_challenge" id="step-challenge__1">
                                                {{fsst2n(\App\Enums\FundedSchemeSubTypes::SINGLE_STEP_CHALLENGE)}}
                                            </a>
                                        </div>
                                        <div class="text-center space-x-3 space-y-3 md:space-y-0" id="phaseButtons">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center phase-btn @if($stage == \App\Enums\FundedStage::STAGE_1) active @endif" data-phase="1">
                                                Phase 1
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center phase-btn ms-2 @if($stage == \App\Enums\FundedStage::STAGE_2) active @endif" data-phase="2">
                                                Phase 2
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="direct-tab-pane" role="tabpanel" aria-labelledby="direct-tab" tabindex="0">
                                    <div class="text-center md:text-start space-x-3 space-y-3 md:space-y-0">
                                        <a href="javascript:void(0);"class="btn btn-sm btn-outline-dark inline-flex items-center leverage-btn @if($subType == \App\Enums\FundedSchemeSubTypes::LEVERAGE_1 || $type == 'challenge')active @endif" data-leverage="{{\App\Enums\FundedSchemeSubTypes::LEVERAGE_1}}">
                                            {{fsst2n(\App\Enums\FundedSchemeSubTypes::LEVERAGE_1)}}
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-dark inline-flex items-center leverage-btn @if($subType == \App\Enums\FundedSchemeSubTypes::LEVERAGE_2 )active @endif" data-leverage="{{\App\Enums\FundedSchemeSubTypes::LEVERAGE_2}}">
                                            {{fsst2n(\App\Enums\FundedSchemeSubTypes::LEVERAGE_2)}}
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-dark inline-flex items-center leverage-btn @if($subType == \App\Enums\FundedSchemeSubTypes::LEVERAGE_3 )active @endif" data-leverage="{{\App\Enums\FundedSchemeSubTypes::LEVERAGE_3}}">
                                            {{fsst2n(\App\Enums\FundedSchemeSubTypes::LEVERAGE_3)}}
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="space-y-8">
                            <div class="input-area relative">
                                <div class="mb-3">
                                    <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">
                                        Account Balance
                                    </p>
                                    <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                        Select your initial starting capital.
                                    </p>
                                </div>
                                <div class="grid md:grid-cols-2 grid-cols-1 gap-5" id="append-data">
                                    <div class="text-center" id="sppiner-loader" style="display: none">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-area relative">
                                <div class="mb-3">
                                    <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">Platform</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                        Please select your trading platform.
                                    </p>
                                </div>
                                <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                    <div class="warning-radio">
                                        <label class="flex items-center cursor-pointer px-3 py-2 rounded border dark:border-slate-700">
                                            <input type="radio" class="hidden priceInput" name="platform" value="" checked disabled>
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="flex-1 inline-flex justify-end items-center">
                                                <div>
                                                    <img src="{{ asset('frontend/images/mt-5-logo.png') }}" alt="">
                                                    <span class="badge bg-slate-900 text-white capitalize mt-2">
                                                        Best for Web Trading
                                                    </span>
                                                </div>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-area relative">
                                <div class="mb-3">
                                    <p class="text-slate-900 dark:text-white text-sm font-medium leading-none mb-1">Addons</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                        Tailor your account to suit your trading style and preference.
                                    </p>
                                </div>
                                <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                    <div class="checkbox-area warning-checkbox">
                                        <label class="w-full inline-flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                            <input type="checkbox" class="hidden" name="weekly_payout" id="biWeeklyPayouts" data-price="5" value="{{ the_hash(5) }}" checked="checked">
                                            <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                            </span>
                                            <span class="flex-1 inline-flex justify-between items-center">
                                                <span class="leading-none">
                                                    <span class="leading-none dark:text-white text-sm block mb-1">
                                                        Bi-Weekly Payouts
                                                    </span>
                                                    <small class="leading-none dark:text-slate-100 text-xs">Instead of Monthly</small>
                                                </span>
                                                <span class="badge bg-slate-900 text-white capitalize">
                                                    +5%
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="checkbox-area warning-checkbox">
                                        <label class="w-full inline-flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                                            <input type="checkbox" class="hidden" name="swap_free" id="swap_free" data-price="10" value="{{ the_hash(10) }}">
                                            <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                            </span>
                                            <span class="flex-1 inline-flex justify-between items-center">
                                                <span class="leading-none">
                                                    <span class="leading-none dark:text-white text-sm block mb-1">
                                                        Swap Free (Islamic)
                                                    </span>
                                                    <small class="leading-none dark:text-slate-100 text-xs">Efficient Group</small>
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
                                        Payment Gateway
                                    </p>
                                    <p class="text-xs text-slate-600 dark:text-slate-100 leading-none">
                                        Select your source to pay for service charges.
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 gap-5 mb-5">
                                    <div id="all-payment-from-accounts-dropdown"></div>
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
                            <input type="text" class="form-control !pr-24" placeholder="Discount Code" id="discount-code">
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center text-sm px-2 dark:text-slate-100 hover:bg-slate-900 hover:text-white" id="apply-discount">Apply Code</button>
                        </div>
                    </div>
                    @if(!auth()->user()->referral)
                    <div class="input-area">
                        <div class="relative">
                            <input type="text" class="form-control !pr-24" placeholder="Affiliate Code" id="referral-code">
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center text-sm px-2 dark:text-slate-100 hover:bg-slate-900 hover:text-white" id="add-referral">Apply Code</button>
                        </div>
                    </div>
                    @endif
                    <div class="form-group text-start">
                        <p class="text-xs dark:text-slate-100 mb-2">By confirming the order:</p>
                        <div class="flex w-full items-start">
                            <input type="checkbox" name="confirmation" class="custom-control-input mt-1" id="checkbox-terms" required="">
                            <label class="custom-control-label text-xs dark:text-slate-100 ml-2" for="checkbox-terms">
                                <span>
                                    I declare that I have read and agree with
                                    <a href="javascript:;" class="btn-link !text-xs">Terms &amp; Condition</a>
                                </span>
                            </label>
                        </div>
                        <span style="color:red;" class="text-sm hidden" id="term-validation">
                            kindly accept the terms &amp; conditions for proceeding
                        </span>
                    </div>
                </div>
                <a href="javascript:;" class="btn btn-outline-dark inline-flex items-center justify-center w-full proceed-payment">
                    Proceed With Payment
                </a>
            </div>
        </div>
    </div>

    <input type="hidden" id="main-type" value="{{$type}}">
    <input type="hidden" id="sub-type" value="{{$subType}}">
    <input type="hidden" id="stage" value="{{$stage}}">
    <input type="hidden" id="invest-id" value="{{$ucode}}">
    <input type="hidden" id="show-data-url" value="{{route('user.pricing.show.data.invest')}}">

@endsection

@push('script')
    @include('frontend.default.investment.invest-jquery')
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {

            showData();
            var type = "{{$type}}";
            if( type == 'direct'){
                $('.pricing-tab-switcher').click();
            }
            {{--var type = "{{$type}}";--}}
            {{--if( type == 'direct'){--}}
            {{--    $('.pricing-tab-switcher').click();--}}
            // }
        });


        $('.phase-btn').on('click', function () {
            var phase = $(this).data('phase');
            updatePhaseData(phase);

            $(this).addClass('active').siblings().removeClass('active');
        });
        $('.leverage-btn').on('click', function () {
            var leverage = $(this).data('leverage');
            updateLeverageData(leverage);
            $(this).addClass('active').siblings().removeClass('active');

            $('#sub-type').val($('.leverage-btn.active').data('leverage'));
            // $('#sub-type').val(leverage);
            showData();
        });

        $('body').on('click', '.pricing-tab-switcher', function() {
            var tabSwitcher = $(this);
            tabSwitcher.toggleClass("active");

            $('#plans-tabs .nav-link').toggleClass('active');
            $('#plans-tab-content .tab-pane').toggleClass('show active');
            var tab1 = $('#challenge-tab-pane');
            var tab2 = $('#direct-tab-pane');

            if (tab1.hasClass('show active')) {
                $('#main-type').val('challenge');
                $('#sub-type').val($('.challenge-btn.active').data('challenge'));

            } else {
                $('#main-type').val('direct');
                $('#sub-type').val($('.leverage-btn.active').data('leverage'));
            }

            showData();
        });

        $('#direct-tab').on('click', function(){
            $('.pricing-tab-switcher').addClass('active');
            $('#main-type').val('direct');
            $('#sub-type').val($('.leverage-btn.active').data('leverage'));
            showData();
        });

        $('#challenge-tab').on('click', function(){
            $('.pricing-tab-switcher').removeClass('active');
            $('#main-type').val('challenge');
            $('#sub-type').val($('.challenge-btn.active').data('challenge'));

            showData();
        });

        $('#step-challenge__2').on('click', function() {
            $('#phaseButtons').show();
            $('#sub-type').val('two_step_challenge');

            showData();

        });

        $('#step-challenge__1').on('click', function() {
            $('#phaseButtons').hide();
            $('#sub-type').val('single_step_challenge');
            showData();
        });

        // Initialize the challenge data based on the default selected challenge
        updateChallangeData("{{$subType}}");

        $('.challenge-btn').on('click', function () {
            var challenge = $(this).data('challenge');

            updateChallangeData(challenge);

            $(this).addClass('active').siblings().removeClass('active');
        });

        function updateChallangeData(challenge) {

            $('.challenge-data').each(function () {
                var dataChallange = $(this).data('challenge');

                if (dataChallange === challenge) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            if(challenge == "{{\App\Enums\FundedSchemeSubTypes::SINGLE_STEP_CHALLENGE}}"){
                $('#phaseButtons').hide();
            }
        };


        // Initialize the phase data based on the default selected phase
        updatePhaseData("{{$stage}}");

        function updatePhaseData(phase) {

            $('.phase-data').each(function () {
                var dataPhase = $(this).data('phase');
                if (dataPhase === phase) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            $('#stage').val(phase);
            showData();
        }

        // Initialize the leverage data based on the default selected leverage
        {{--updateLeverageData("{{\App\Enums\FundedSchemeSubTypes::LEVERAGE_1}}");--}}

        function updateLeverageData(leverage) {
            $('.leverage-data').each(function () {
                var dataLeverage = $(this).data('leverage');

                if (dataLeverage === leverage) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('.payment-plans-row__container').click(function () {
            $(this).children('.plan-details').slideToggle();
        });

        $('.planSlide').hide();
        $('.planSlide.current').show();
        $('#next').click(function () {
            $('.planSlide.current').removeClass('current').hide().next().show().addClass('current');

            if ($('.planSlide.current').hasClass('last')) {
                $('#next').css('display', 'none');
            }
            $('#prev').css('display', 'block');
        });

        $('#prev').click(function () {
            $('.planSlide.current').removeClass('current').hide().prev().show().addClass('current');

            if ($('.planSlide.current').hasClass('first')) {
                $('#prev').css('display', 'none');
            }
            $('#next').css('display', 'block');
        });


        function showData() {
            $('#append-data').text('');
            var btn =null;
            let formData = new FormData();
            formData.append('type', $('#main-type').val());
            formData.append('sub_type', $('#sub-type').val());
            formData.append('stage', $('#stage').val());
            formData.append('invest_id', $('#invest-id').val());
            {{--var url = "{{route('user.pricing.investment.show.data')}}";--}}
            var url =  $('#show-data-url').val();
            submit_form_invest(formData, btn, url, 'append-data');

        }
        function submit_form_invest(formData,btn,url,appendId=null,modalId=null){
            // debugger;
            $.ajax({
                url : url,
                type: 'POST', data: formData, processData: false, contentType: false,
                beforeSend: function () {
                    $("#sppiner-loader").show();
                },
                success : function(res) {

                    if(res.success){
                        NioApp.Toast(res.success, 'success');
                        if(res.reload) {
                            setTimeout(function(){ location.reload(); }, 900);
                        }
                        if(res.redirect) {
                            setTimeout(function(){ window.location.replace(res.redirect); }, 900);
                        }
                        if (res.modal) {
                            $('#'+modalId).modal('toggle');
                            // NioApp.Form.errors(res, true);
                            // btn.prop('disabled', false);
                        }
                    }
                    else if(res.append){

                        $('#'+appendId).html(res.append);
                        $('.priceInput:checked').change();
                        // NioApp.Toast(res.error, 'warning');
                        // setTimeout(function(){ location.reload(); }, 900);

                        $(".form-select").select2({
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
                            if (!optimage) {
                                return opt.text.toUpperCase();
                            } else {
                                var $opt = $(
                                    '<div class="coin-item coin-btc"><div class="coin-icon"><img src="' + optimage + '" class="mr-2" width="40px" /></div><div class="coin-info"><span class="coin-name">' + opt.text.toUpperCase() + '</span><ul class="kanban-item-meta-list">' + optdes + '</ul></div></div>'
                                );
                                return $opt;
                            }
                        }
                    }
                    else if(res.error){
                        NioApp.Toast(res.error, 'warning');
                        setTimeout(function(){ location.reload(); }, 900);
                    }
                    else if (res.errors) {
                        NioApp.Form.errors(res, true);
                        btn.prop('disabled', false);
                    }
                },
                complete: function (data) {
                    // Hide image container
                    $("#sppiner-loader").hide();
                },
                error: function(data) {
                    btn.prop('disabled', false);
                    NioApp.Toast("Sorry something went wrong! Please reload the page and try again.", 'warning');
                }
            })
        }


        const input = document.querySelectorAll("#funded-phone-no, #funded-whatsapp-no");
        var phonevalidation = [];
        input.forEach((element) => {
            var iti = window.intlTelInput(element, {
                autoPlaceholder: "off",
                dropdownContainer: document.body,
                formatOnDisplay: true,
                // geoIpLookup: function(callback) {
                //     $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                //         var countryCode = (resp && resp.country) ? resp.country : "";
                //         callback(countryCode);
                //     });
                // },
                hiddenInput: "full_number",
                initialCountry: "auto",
                nationalMode: false,
                placeholderNumberType: "MOBILE",
                separateDialCode: true,
                utilsScript: "{{ asset('assets/libs/intl-tel/js/utils.js') }}",
            });
            phonevalidation.push(iti);
        });

        $(function () {
            let totalPrice = 0;
            let addon = 0;
            let discount = 0;

            checkedValue();
            get_real_accounts();
            $('body').on('click', '#apply-discount', function () {
                var code = $('#discount-code').val();
                if (code.length > 0) {
                    let formData = new FormData();
                    formData.append('code', code);
                    var btn = null;
                    var url = "{{ route('user.pricing.get.discount.by.code') }}";
                    // debugger;
                    $.ajax({
                        url: url,
                        type: 'POST', data: formData, processData: false, contentType: false,
                        success: function (res) {
                            if (res.success) {
                                NioApp.Toast(res.success, 'success');
                                var discount_per = res.percentage;
                                $('#discount_amount').val(discount_per);
                                discount = parseFloat(discount_per / 100) * parseFloat(totalPrice);
                                calc_total();
                                if (res.reload) {
                                    setTimeout(function () {
                                        location.reload();
                                    }, 900);
                                }
                            } else if (res.error) {
                                NioApp.Toast(res.error, 'warning');
                                if (res.reload) {
                                    setTimeout(function () {
                                        location.reload();
                                    }, 900);
                                }
                            } else if (res.errors) {
                                NioApp.Form.errors(res, true);
                                btn.prop('disabled', false);
                            }
                        },
                        error: function (data) {
                            btn.prop('disabled', false);
                            NioApp.Toast("{{ __("Sorry, something went wrong! Please reload the page and try again.") }}", 'warning');
                        }
                    })

                } else {
                    NioApp.Toast("{{ __("Please add valid discount code.") }}", 'warning');

                }
            });
            $('body').on('change', '.priceInput', function ()
            {

                $(".addonPrice").prop("checked", false);
                addon = 0;
                totalPrice = $(this).attr("data-price");
                discount_per = $('#discount_amount').val();
                // discount_per = 10;
                discount = parseFloat(discount_per / 100) * parseFloat(totalPrice);
                calc_total();
            });

            $('body').on('change', '.addonPrice', function (){
                let perc = 0;
                $('.addonPrice:checked').each(function () {
                    perc += parseInt($(this).attr("data-price"), 10);
                });

                addon = parseFloat(perc / 100) * parseFloat(totalPrice);
                calc_total();

            });

            // calculate Grand Total
            function calc_total() {
                var total = parseFloat(totalPrice) + parseFloat(addon) - parseFloat(discount);
                $('.order-info__total').text(total.toFixed(2))
                $("#scheme_amount").val(totalPrice);
            }

            function checkedValue() {
                addon = 0;
                totalPrice = $('input[name="scheme"]:checked').attr("data-price");
                calc_total();
            }

        });

        // get_real_accounts function
        function get_real_accounts() {
            let formData = new FormData();
            var btn = null;
            var url = "{{ route('user.get.all.payment.from.accounts') }}";
            submit_form(formData, btn, url, 'all-payment-from-accounts-dropdown');
        }

        $('body').on('click', '.proceed-payment', function () {
            var btn = $(this);
            $("#funded-phone-no").removeClass('error')
            $("#funded-whatsapp-no").removeClass('error')
            $(".invalid").remove();
            $("#funded-phone-no-validation-error").addClass('hidden');
            $("#funded-whatsapp-no-validation-error").addClass('hidden');
            var btn = $(this);
            var valid = phonevalidation[0].isValidNumber();
            var valid_whatsapp = phonevalidation[1].isValidNumber();

            if (!valid) {
                $("#funded-phone-no").addClass('error')
                $("#funded-phone-no-validation-error").removeClass('hidden');

            } else if (!valid_whatsapp) {
                $("#funded-whatsapp-no").addClass('error')
                $("#funded-whatsapp-no-validation-error").removeClass('hidden');
            } else {
                $('#term-validation').addClass('hidden')
                if ($("#checkbox-terms").prop("checked")) {
                    btn.prop('disabled', true);
                    let form = document.querySelector('#invest-form');
                    var type = $("a:active").data("type");
                    let formData = new FormData(form);
                    formData.append('phone_number', phonevalidation[0].getNumber());
                    formData.append('profile_whatsapp', phonevalidation[1].getNumber());
                    formData.append('payment_type', $('#wallets-type').data("type"));
                    // formData.append('amount', $('#active_wallet_amount').val());
                    var url = $('#invest-form').attr('action');
                    submit_form(formData, btn, url, 'success-message');

                } else {
                    $('#term-validation').removeClass('hidden')
                    // Perform actions when the checkbox is not checked
                }
            }

        });

        $('body').on('click', '#add-referral', function () {
            var btn = $(this);
            var code = $('#referral-code').val();
            if (code.length > 0) {
                btn.prop('disabled', true);

                let formData = new FormData();
                formData.append('code', code);
                var url = "{{route('user.pricing.add.referral')}}";
                submit_form(formData, btn, url);
            } else {
                NioApp.Toast("{{ __("Please add valid Affiliate code.") }}", 'warning');
            }
        });

    </script>
@endpush
