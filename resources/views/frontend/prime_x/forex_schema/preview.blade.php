@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Preview') }}
@endsection
@section('content')
    <div class="card p-6 mb-5 space-y-3">
        <h3 class="card-title">
            {{ __('Choose Account Type') }}
        </h3>
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4" id="account-type-tabs">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active" data-type="real" id="real-tab">
                    {{ __('Real') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300" data-type="demo" id="demo-tab">
                    {{ __('Demo') }}
                </a>
            </li>
        </ul>
        <p class="card-text">
            {{ __('Risk-Free Account: Trade using virtual money') }}
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 mb-5">
        <div class="h-full">
            <h4 class="text-xl text-slate-900 mb-3">
                {{ __('Account Options') }}
            </h4>
            <div class="card h-auto">
                <div class="card-body p-6">
                    <form class="space-y-5" action="{{route('user.forex-account-create-now')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="account_type" id="account-type" value="real">
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Select Account Type:') }}
                            </label>
                            <select class="form-control py-2 h-[48px]" aria-label="Default select example" id="select-schema" name="schema_id" required>
                                @foreach($schemas as $plan)
                                    <option value="{{$plan->id}}"
                                            @if($plan->id == $schema->id ) selected @endif
                                            data-is-real-islamic="{{$plan->is_real_islamic}}"
                                            data-is-demo-islamic="{{$plan->is_demo_islamic}}"
                                            data-first-min-deposit="{{$plan->first_min_deposit}}"
                                            data-leverage="{{$plan->leverage}}">
                                        {{$plan->title}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <div class="flex items-center space-x-5 flex-wrap">
                                <div class="form-switch ps-0" style="line-height:0;">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox" data-target="#live-islamic-group">
                                        <input type="checkbox" name="is_islamic" value="1" class="sr-only peer" id="islamic-checkbox">
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
                            <input type="text" class="form-control py-2 h-[48px]" placeholder="{{ __('Enter Nickname') }}" aria-label="{{ __('Nickname') }}" name="account_name" id="enter-nickname" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Main Password:') }}
                            </label>
                            <input type="text" class="form-control py-2 h-[48px]" placeholder="{{ __('Enter Main Password') }}" aria-label="{{ __('Main Password') }}" name="main_password" id="enter-main-password" aria-describedby="basic-addon1" required>
                            <ul>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-2" id="length-check-main">
                                    {{ __('Use from 8 to 15 characters') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="letters-check-main">
                                    {{ __('Use both uppercase and lowercase letters') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="number-check-main">
                                    {{ __('At least one number') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="special-check-main">
                                    {{ __('At least one special character(!@#$%^&*(),-.?":{}|<>)') }}
                                </li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn inline-flex justify-center btn-dark me-3" id="create-forex-account">
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
        <div class="h-full">
            <h4 class="text-xl text-slate-900 mb-3">
                {{ __('Account Specifications and Features') }}
            </h4>
            <div class="card h-auto">
                <div class="card-header noborder">
                    <h3 class="card-title">
                        {{ __('Standard') }}
                    </h3>
                </div>
                <div class="card-body p-6 pt-0">
                    <ul class="space-y-5">
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('Spread from') }}</span>
                            <span id="display-spread">{{ $schema->spread }}</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('Commission') }}</span>
                            <span id="display-commission">{{$schema->commission == 0 ? __('No Commission') : $schema->commission}}</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('Leverage') }}</span>
                            <span id="display-leverage">{{ explode(',', $schema->leverage)[0] }}</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('Initial Deposit Limit') }}</span>
                            <span id="initial-deposit">{{ $schema->first_min_deposit }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-slate-800 bg-opacity-[14%] text-slate-800 dark:bg-slate-500 dark:bg-opacity-[14%] dark:text-slate-300">
        <div class="flex items-center space-x-3 rtl:space-x-reverse">
            <iconify-icon class="text-xl flex-0" icon="lucide:info"></iconify-icon>
            <p class="flex-1 font-Inter">
                {{ __('Comprehensive details on our instruments and trading conditions can be found on the') }} <a href="" class="text-warning-600">{{ __('Customer Contract') }}</a> {{ __('page.') }}
            </p>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            function updateIslamicCheckboxState(accountType, isRealIslamic, isDemoIslamic) {
                var isIslamic = false;
                if (accountType === 'real') {
                    isIslamic = isRealIslamic == 1;
                } else if (accountType === 'demo') {
                    isIslamic = isDemoIslamic == 1;
                }
                $('#islamic-checkbox').prop('disabled', !isIslamic);
            }

            function updateLeverageAndDeposit(result) {
                $('#display-commission').text(result.commission);
                $('#display-spread').text(result.spread);
                $('#select-leverage').html(result.leverage);
                $('#display-leverage').text(result.display_leverage);
                $('#initial-deposit').text(result.first_min_deposit);
            }

            $('#account-type-tabs .nav-link').on('click', function () {
                $('#account-type-tabs .nav-link').removeClass('active');
                $(this).addClass('active');
                var accountType = $(this).data('type');
                $('#account-type').val(accountType);

                $('#islamic-checkbox').prop('checked', false);

                var isRealIslamic = $('#select-schema').find('option:selected').data('is-real-islamic');
                var isDemoIslamic = $('#select-schema').find('option:selected').data('is-demo-islamic');
                updateIslamicCheckboxState(accountType, isRealIslamic, isDemoIslamic);
            });

            $("#islamic-checkbox").on('change', function () {
                var isIslamic = $(this).is(':checked');
                var accountType = $('#account-type').val();
                var isRealIslamic = $('#select-schema').find('option:selected').data('is-real-islamic');
                var isDemoIslamic = $('#select-schema').find('option:selected').data('is-demo-islamic');
                updateIslamicCheckboxState(accountType, isRealIslamic, isDemoIslamic);
            });

            $("#select-schema").on('change', function (e) {
                "use strict";
                e.preventDefault();
                var id = $(this).val();
                var url = '{{ route("user.schema.select", ":id") }}';
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    success: function (result) {
                        $('#first-min-amount').text(result.first_min_deposit);
                        updateLeverageAndDeposit(result);

                        $('#select-schema').data('is-real-islamic', result.is_real_islamic);
                        $('#select-schema').data('is-demo-islamic', result.is_demo_islamic);

                        $('#islamic-checkbox').prop('checked', false);
                        updateIslamicCheckboxState($('#account-type').val(), result.is_real_islamic, result.is_demo_islamic);
                    }
                });
            });

            var initialAccountType = $('#account-type').val();
            var initialIsRealIslamic = $('#select-schema').find('option:selected').data('is-real-islamic');
            var initialIsDemoIslamic = $('#select-schema').find('option:selected').data('is-demo-islamic');
            updateIslamicCheckboxState(initialAccountType, initialIsRealIslamic, initialIsDemoIslamic);

            $("#select-leverage").on('change', function () {
                var selectedLeverage = $(this).val();
                $('#display-leverage').text(selectedLeverage); // Update the display-leverage with the selected value
            });

            $("#selectWallet").on('change', function (e) {
                "use strict";
                $('.gatewaySelect').empty();
                $('.manual-row').empty();
                var wallet = $(this).val();
                if (wallet === 'gateway') {
                    $.get('{{ route('gateway.list') }}', function (data) {
                        $('.gatewaySelect').append(data);
                        $('select').niceSelect();
                    });
                }
            });

            $('body').on('change', '#gatewaySelect', function (e) {
                "use strict";
                e.preventDefault();
                $('.manual-row').empty();
                var code = $(this).val();
                var url = '{{ route("user.deposit.gateway", ":code") }}';
                url = url.replace(':code', code);
                $.get(url, function (data) {
                    if (data.credentials !== undefined) {
                        console.log(data.credentials);
                        $('.manual-row').append(data.credentials);
                        imagePreview();
                    }
                });

                $('#amount').on('keyup', function (e) {
                    "use strict";
                    var amount = $(this).val();
                    $('.amount').text(Number(amount));
                    $('.currency').text(currency);
                    var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge;
                    $('.charge2').text(charge + ' ' + currency);
                    $('.total').text(Number(amount) + Number(charge) + ' ' + currency);
                });
            });

            $('#enter-main-password').on('input', function () {
                var password = $(this).val();
                checkPassword(password, 'main', 'create-forex-account');
            });
        });
    </script>
@endsection
