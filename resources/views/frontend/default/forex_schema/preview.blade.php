@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Preview') }}
@endsection
@section('content')
    <div class="card mx-auto max-w-2xl">
        <div class="card-body p-6">
            <form class="space-y-4" action="{{route('user.forex-account-create-now')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="md:flex md:items-center">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 mb-1 md:mb-0" for="">
                            {{ __('Select Account Type:') }}
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <select class="form-control py-2 h-[48px]" aria-label="Default select example" id="select-schema" name="schema_id" required>
                            @foreach($schemas as $plan)
                                <option value="{{$plan->id}}"
                                        @if($plan->id == $schema->id ) selected @endif>{{$plan->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 mb-1 md:mb-0" for="">
                            {{ __('Select Group:') }}
                        </label>
                    </div>
                    <div class="md:w-2/3">
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
                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 mb-1 md:mb-0" for="">
                            {{ __('Select Leverage:') }}
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <select class="form-control py-2 h-[48px]" aria-label="Default select example" id="select-leverage" name="leverage" required>
                            @foreach(explode(',', $schema->leverage) as $leverage)
                                <option value="{{$leverage}}">{{$leverage}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 mb-1 md:mb-0" for="">
                            {{ __('Account Nickname:') }}
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input type="text" class="form-control py-2 h-[48px]" placeholder="Enter Nickname" aria-label="Nickname" name="account_name" id="enter-nickname" aria-describedby="basic-addon1" required>
                    </div>
                </div>
                <div class="md:flex">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 mb-1 md:mb-0 md:mt-3" for="">
                            {{ __('Main Password:') }}
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input type="text" class="form-control py-2 h-[48px]" placeholder="Enter Main Password" aria-label="Main Password" name="main_password" id="enter-main-password" aria-describedby="basic-addon1" required>
                        <ul>
                            <li class="text-xs font-Inter font-normal text-danger mt-2" id="length-check-main">
                                Use from 8 to 15 characters
                            </li>
                            <li class="text-xs font-Inter font-normal text-danger mt-1" id="letters-check-main">
                                Use both uppercase and lowercase letters
                            </li>
                            <li class="text-xs font-Inter font-normal text-danger mt-1" id="number-check-main">
                                At least one number
                            </li>
                            <li class="text-xs font-Inter font-normal text-danger mt-1" id="special-check-main">
                                At least one special character(!@#$%^&*(),-.?":{}|<>)
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="text-right mt-4">
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
