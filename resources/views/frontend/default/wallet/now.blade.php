@extends('frontend::wallet.index')
@section('wallet_exchange_content')
    <div class="progress-steps-form">
        <form action="{{ route('user.wallet-exchange-now') }}" method="post">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                <div class="input-area relative">
                    <label for="exampleFormControlInput1" class="form-label">{{ __('From Wallet:') }}</label>
                    <div class="input-group select2-lg">
                        <select name="from_wallet" class="select2 form-control !text-lg w-full mt-2 py-2">
                            <option value="1" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ __('Main Wallet').' ('. $user->balance.' '.$currency .')' }}</option>
                            <option selected value="2" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ __('Profit Wallet').' ('. $user->profit_balance.' '.$currency .')' }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="input-area relative">
                    <label for="exampleFormControlInput1" class="form-label">{{ __('To Wallet:') }}</label>
                    <div class="input-group select2-lg">
                        <select name="to_wallet" class="select2 form-control !text-lg w-full mt-2 py-2">
                            <option selected value="1" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ __('Main Wallet').' ('. $user->balance.' '.$currency .')' }}</option>
                            <option value="2" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ __('Profit Wallet').' ('. $user->profit_balance.' '.$currency .')' }}</option>
                        </select>
                    </div>
                </div>

                <div class="input-area relative">
                    <label for="exampleFormControlInput1" class="form-label">{{ __('Enter Amount:') }}</label>
                    <div class="relative">
                        <input type="text" name="amount" class="form-control !text-lg"
                               oninput="this.value = validateDouble(this.value)" aria-label="Amount" id="amount"
                               aria-describedby="basic-addon1">
                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center" id="basic-addon1">{{ $currency }}</span>
                    </div>
                    <div class="font-Inter text-xs text-red-500 pt-2 inline-block charge"></div>
                </div>
            </div>

            <div class="transaction-list mt-5">
                <div class="user-panel-title">
                    <h3 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">{{ __('Transfer Details:') }}</h3>
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
                                    <strong>{{ __('Total') }}</strong>
                                </td>
                                <td class="total"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="buttons text-right mt-4">
                <button type="submit" class="btn inline-flex justify-center btn-dark">
                    {{ __('Transfer Now') }}<i class="anticon anticon-double-right"></i>
                </button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>

        "use strict"

        var currency = @json($currency);

        var charge_type = @json( setting('wallet_exchange_charge_type','fee') );
        var charge = @json( setting('wallet_exchange_charge','fee') );

        $('#amount').on('keyup',function (e) {

            var amount = $(this).val()

            $('.amount').text((Number(amount)))

            $('.currency').text(currency)

            var finalCharge = charge_type === 'percentage' ? calPercentage(amount, charge) : charge


            $('.charge2').text(finalCharge + ' ' + currency)

            $('.total').text((Number(amount) + Number(finalCharge)) + ' ' + currency)


            $('.charge').text('Charge ' + charge + ' ' + (charge_type === 'percentage' ? ' % ' : currency))
        })
    </script>
@endsection



