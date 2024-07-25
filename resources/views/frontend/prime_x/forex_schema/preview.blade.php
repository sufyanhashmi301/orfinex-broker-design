@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Preview') }}
@endsection
@section('content')
    <div class="card p-6 mb-5 space-y-3">
        <h3 class="card-title">
            {{ __('Choose Account Type') }}
        </h3>
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    Real
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                    Demo
                </a>
            </li>
        </ul>
        <p class="card-text">
            {{ __('Risk-Free Account: Trade using virtual money') }}
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
        <div>
            <h4 class="text-xl text-slate-900 mb-3">
                {{ __('Account Options') }}
            </h4>
            <div class="card h-full">
                <div class="card-body p-6">
                    <form class="space-y-5" action="{{route('user.forex-account-create-now')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Select Account Type:') }}
                            </label>
                            <select class="form-control py-2 h-[48px]" aria-label="Default select example" id="select-schema" name="schema_id" required>
                                @foreach($schemas as $plan)
                                    <option value="{{$plan->id}}"
                                            @if($plan->id == $schema->id ) selected @endif>{{$plan->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <div class="flex items-center space-x-5 flex-wrap">
                                <div class="form-switch ps-0" style="line-height:0;">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox" data-target="#live-islamic-group">
                                        <input type="checkbox" name="" value="1" class="sr-only peer">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label pt-0 !mb-0" style="width:auto">
                                    {{ __('Request Swap-Free Option (Islamic Account)') }}
                                </label>
                            </div>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Select Group:') }}
                            </label>
                            <select class="form-control py-2 h-[48px]" aria-label="Default select example" id="select-group" name="group" required>
                                @if($schema->real_swap_free)
                                    <option value="real_swap_free">{{__('Real (Swap)')}}</option>
                                @endif
                                @if($schema->real_islamic)
                                    <option value="real_islamic">{{__('Real (Islamic)')}}</option>
                                @endif
                                @if($schema->demo_swap_free)
                                    <option value="demo_swap_free">{{__('Demo (Swap)')}}</option>
                                @endif
                                @if($schema->demo_islamic)
                                    <option value="demo_islamic">{{__('Demo (Islamic)')}}</option>
                                @endif
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Select Leverage:') }}
                            </label>
                            <select class="form-control py-2 h-[48px]" aria-label="Default select example" id="select-leverage" name="leverage" required>
                                @foreach(explode(',', $schema->leverage) as $leverage)
                                    <option value="{{$leverage}}">{{$leverage}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Account Nickname:') }}
                            </label>
                            <input type="text" class="form-control py-2 h-[48px]" placeholder="Enter Nickname" aria-label="Nickname" name="account_name" id="enter-nickname" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Main Password:') }}
                            </label>
                            <input type="text" class="form-control py-2 h-[48px]" placeholder="Enter Main Password" aria-label="Main Password" name="main_password" id="enter-main-password" aria-describedby="basic-addon1" required>
                            <ul>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-2" id="length-check-main">
                                    Use from 8 to 15 characters
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="letters-check-main">
                                    Use both uppercase and lowercase letters
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="number-check-main">
                                    At least one number
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="special-check-main">
                                    At least one special character(!@#$%^&*(),-.?":{}|<>)
                                </li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn inline-flex justify-center btn-dark me-3" id="create-forex-account" disabled>
                                {{ __('Create Account') }}
                            </button>
                            <a href="{{route('user.schema')}}" class="btn inline-flex justify-center btn-outline-dark">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div>
            <h4 class="text-xl text-slate-900 mb-3">
                {{ __('Account Specifications and Features') }}
            </h4>
            <div class="card h-full">
                <div class="card-header noborder">
                    <h3 class="card-title">
                        {{ __('Standard') }}
                    </h3>
                </div>
                <div class="card-body p-6 pt-0">
                    <ul class="space-y-5">
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>Spread from</span>
                            <span>0.2</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>No Commission</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>Leverage</span>
                            <span>100</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>Initial Deposit Limit</span>
                            <span>1000</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $("#select-schema").on('change', function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).val();

            var invest_amount = $("#enter-amount");
            invest_amount.val('');
            invest_amount.attr('readonly', false);

            var url = '{{ route("user.schema.select", ":id") }}';
            url = url.replace(':id', id);

            $.ajax({
                url: url, success: function (result) {
                    $('#first-min-amount').text(result.first_min_deposit);
                    $('#select-leverage').html(result.leverage);
                    $('#select-group').html(result.group);
                    // $('#return-interest').html(result.return_interest);
                    // $('#number-period').html(result.number_period);
                    // $('#capital_back').html(result.capital_back);

                    // if (result.invest_amount > 0) {
                    //     invest_amount.val(result.invest_amount);
                    //     invest_amount.attr('readonly', true);
                    // }

                }
            });
        });


        $("#selectWallet").on('change', function (e) {
            "use strict";
            $('.gatewaySelect').empty();
            $('.manual-row').empty();
            var wallet = $(this).val();
            if (wallet === 'gateway') {
                $.get('{{ route('gateway.list') }}', function (data) {
                    $('.gatewaySelect').append(data)
                    $('select').niceSelect();

                });
            }

        })
        // $("#create-forex-account").on('click', function (e) {
        //     e.preventDefault();
        //     var password = $("#enter-main-password").val();
        //    var isValid =  checkPassword(password);
        //    if(isValid){
        //        $(this).submit();
        //    }
        // })
        $('body').on('change', '#gatewaySelect', function (e) {
            "use strict"
            e.preventDefault();

            $('.manual-row').empty();

            var code = $(this).val()

            var url = '{{ route("user.deposit.gateway",":code") }}';
            url = url.replace(':code', code);
            $.get(url, function (data) {

                if (data.credentials !== undefined) {

                    console.log(data.credentials);

                    $('.manual-row').append(data.credentials)
                    imagePreview()
                }

            });

            $('#amount').on('keyup', function (e) {
                "use strict"
                var amount = $(this).val()
                $('.amount').text((Number(amount)))
                $('.currency').text(currency)
                var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge
                $('.charge2').text(charge + ' ' + currency)

                $('.total').text((Number(amount) + Number(charge)) + ' ' + currency)
            })


        });


        $('#enter-main-password').on('input', function () {
            var password = $(this).val();
            checkPassword(password,'main','create-forex-account');

        });


    </script>
@endsection
