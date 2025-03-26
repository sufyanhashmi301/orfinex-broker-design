@extends('frontend::deposit.index')
@section('deposit_content')
    <div class="progress-steps-form">
        <form action="{{ route('user.deposit.now') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                <div class="input-area relative mb-5">
                    <label for="" class="form-label">{{ __('Account to Deposit') }}</label>
                    <div class="input-group select2-lg">
                        <select  id="tradingAccount" name="target_id" class="select2 form-control !text-lg w-full mt-2 py-2">
                            <option selected disabled>--{{ __('Select Account') }}--</option>
                            @foreach($forexAccounts as $forexAccount)
                                <option value="{{ $forexAccount->login }}" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="input-area relative">
                    <label for="" class="form-label">{{ __('Payment Method') }}</label>
                    <div class="input-group select2-lg">
                        <select name="gateway_code" id="gatewaySelect" class="select2 form-control !text-lg w-full mt-2 py-2">
                            <option selected class="inline-block font-Inter font-normal text-sm text-slate-600" disabled>--{{ __('Select Gateway') }}--</option>
                            @foreach($gateways as $gateway)
                                <option value="{{ $gateway->gateway_code }}" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $gateway->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="font-Inter text-xs text-red-500 pt-2 inline-block charge"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">

                <div class="input-area relative">
                    <label for="" class="form-label">{{ __('Enter Amount') }}</label>
                    <div class="relative">
                        <input type="text" name="amount" class="form-control !text-lg"
                               oninput="this.value = validateDouble(this.value)" aria-label="Amount" id="amount"
                               aria-describedby="basic-addon1">
                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center" id="basic-addon1">{{ $currency }}</span>
                    </div>
                    <div class="font-Inter text-xs text-red-500 pt-2 inline-block min-max"></div>
                </div>
                <div class="input-area relative conversion hidden">
                    <label for="" class="form-label">{{ __('Enter Amount') }}</label>
                    <div class="relative">
                        <input type="text"  class="form-control !text-lg"
                               oninput="this.value = validateDouble(this.value)" aria-label="Amount" id="converted-amount"
                               aria-describedby="basic-addon2">
                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center" id="basic-addon2">{{ $currency }}</span>
                    </div>
                    <div class="font-Inter text-xs text-red-500 pt-2 inline-block conversion-rate"></div>
                </div>

            </div>

            <div class="grid grid-cols-12 gap-3 manual-row">

            </div>

            <div class="transaction-list mt-5">
                <div class="user-panel-title">
                    <h3 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">{{ __('Review Details') }}</h3>
                </div>
                <div class="max-w-[1005px] mx-auto shadow-base dark:shadow-none my-8 rounded-md overflow-x-auto">
                    <table class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                        <tbody>
                            <tr class="border-b border-slate-100 dark:border-slate-700">
                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                    <strong>{{ __('Amount') }}</strong>
                                </td>
                                <td><span class="amount"></span> <span class="currency"></span></td>
                            </tr>
                            <tr class="border-b border-slate-100 dark:border-slate-700">
                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                    <strong>{{ __('Charge') }}</strong>
                                </td>
                                <td class="charge2"></td>
                            </tr>
                            <tr class="border-b border-slate-100 dark:border-slate-700">
                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                    <strong>{{ __('Payment Method') }}</strong>
                                </td>
                                <td id="logo"><img src="" class="payment-method" alt=""></td>
                            </tr>
                            <tr class="border-b border-slate-100 dark:border-slate-700">
                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                    <strong>{{ __('Total') }}</strong>
                                </td>
                                <td class="total"></td>
                            </tr>
                            <tr class="conversion border-b border-slate-100 dark:border-slate-700">
                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                    <strong>{{ __('Conversion Rate') }}</strong>
                                </td>
                                <td class="conversion-rate"></td>
                            </tr>
                            <tr class="conversion border-b border-slate-100 dark:border-slate-700">
                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                    <strong>{{ __('Pay Amount') }}</strong>
                                </td>
                                <td class="pay-amount"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="buttons text-right mt-4">
                <button type="submit" class="btn inline-flex justify-center btn-dark">
                    {{ __('Proceed to Payment') }}<i class="anticon anticon-double-right"></i>
                </button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>

        var globalData;
        var currency = @json($currency)

        $("#gatewaySelect").on('change', function (e) {
            "use strict"
            e.preventDefault();
            $('.manual-row').empty();
            var code = $(this).val()
            var url = '{{ route("user.deposit.gateway",":code") }}';
            url = url.replace(':code', code);
            $.get(url, function (data) {

                globalData = data;

                if (data.currency === currency){
                    $('.conversion').addClass('hidden');
                }else {
                    $('.conversion').removeClass('hidden');
                    $('#basic-addon2').text(globalData.currency);
                    $('#amount').trigger('keyup')
                }

                $('.charge').text('Charge ' + data.charge + ' ' + (data.charge_type === 'percentage' ? ' % ' : currency))
                $('.conversion-rate').text('1' +' '+ currency + ' = ' + data.rate +' '+ data.currency)


                $('.min-max').text('Minimum ' + data.minimum_deposit + ' ' + currency + ' and ' + 'Maximum ' + data.maximum_deposit + ' ' + currency)
                $('#logo').html(`<img class="payment-method h-12" src='${data.gateway_logo}'>`);
                var amount = $('#amount').val()

                if (Number(amount) > 0) {
                    $('.amount').text((Number(amount)))
                    var charge = data.charge_type === 'percentage' ? calPercentage(amount, data.charge) : data.charge
                    $('.charge2').text(charge + ' ' + currency)
                    $('.total').text((Number(amount) + Number(charge)) + ' ' + currency)
                }

                if (data.credentials !== undefined) {
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

                var total = (Number(amount) + Number(charge));

                $('.total').text(total + ' ' + currency)

                $('.pay-amount').text(parseFloat((total * globalData.rate).toFixed(4)).toString() +' '+ globalData.currency)

                $('#converted-amount').val(parseFloat((total * globalData.rate).toFixed(4)).toString())
            })
            $('#converted-amount').on('keyup', function (e) {
                "use strict"
                var converted_amount = $(this).val();
                var amount = parseFloat((converted_amount / globalData.rate).toFixed(4)).toString();
                $('#amount').val(amount);
                $('.amount').text((Number(amount)))
                $('.currency').text(currency)

                var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge
                $('.charge2').text(charge + ' ' + currency)

                var total = (Number(amount) + Number(charge));

                $('.total').text(total + ' ' + currency)

                $('.pay-amount').text(parseFloat((total * globalData.rate +' '+ globalData.currency).toFixed(4)).toString());


            })


        });
    </script>
@endsection
