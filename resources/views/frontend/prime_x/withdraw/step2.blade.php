@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Now') }}
@endsection
@section('content')
    <div class="flex justify-end flex-wrap items-center mb-5">
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('user.withdraw.account.index') }}" class="btn btn-primary inline-flex items-center">
                Add Withdraw Details
            </a>
        </div>
    </div>
    <div class="card mb-6">
        <div class="card-body hidden md:block p-3">
            <div class="progress-steps md:flex justify-between items-center gap-5">
                <div class="single-step current">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 1') }}</div>
                        <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Select Payout') }}</h4>
                    </div>
                </div>
                <div class="single-step current">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 2') }}</div>
                        <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Withdraw Amount') }}</h4>
                    </div>
                </div>
                <div class="single-step">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 3') }}</div>
                        <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Success') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="progress-steps-form">
        <form action="{{ route('user.withdraw.now') }}" id="withdraw-form" method="post">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                <div>
                    <div class="card">
                        <div class="card-body p-6">

                            <div class="flex flex-wrap justify-between items-center mb-5">
                                <div class="space-x-3">
                                    <h5>Withdraw from {{ $wallet->title }} ({{ $wallet->unique_id }})</h5>
                                </div>
                            </div>
                            <div class="mb-5">
                                <label for="exampleFormControlInput1" class="form-label">
                                    Available Balance
                                </label>
                                <div class="text-slate-900 dark:text-white text-xl font-medium">
                                    {{ number_format($wallet->available_balance, 2) }} {{$currency}}
                                </div>
                            </div>
                            <input type="hidden" name="wallet_id" value="{{ $wallet->id }}">

                            {{-- <div class="input-area relative mb-5">
                                <label for="exampleFormControlInput1" class="form-label">
                                    Select Wallet
                                </label>
                                <div class="input-group select2-lg">
                                    <select id="tradingAccount" name="target_id"
                                            class="select2 form-control !text-lg w-full mt-2 py-2">
                                        <option selected class="inline-block font-Inter font-normal text-sm text-slate-600" disabled>
                                            {{ __('Select Wallet') }}
                                        </option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $wallet->title }} ({{ $wallet->unique_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            @if (count($withdraw_to_accounts) != 0)
                                <div class="input-area relative">
                                    <label for="exampleFormControlInput1" class="form-label">
                                        {{ __('Withdraw To') }}
                                    </label>
                                    <div class="input-group select2-lg">
                                        <select name="withdraw_account" id="withdrawAccountId" class="select2 form-control !text-lg w-full mt-2 py-2">
                                            <option selected disabled class="inline-block font-Inter font-normal text-sm text-slate-600">
                                                Select Details
                                            </option>
                                            @foreach($withdraw_to_accounts as $withdraw_to_account)
                                                <option value="{{ $withdraw_to_account->id }}" class="inline-block font-Inter font-normal text-sm text-slate-600">
                                                    {{ $withdraw_to_account->method_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="font-Inter text-xs text-red-500 pt-2 inline-block processing-time"></div> --}}
                                </div>
                                <div class="input-area mt-4 relative">
                                    <label for="exampleFormControlInput1" class="form-label">Amount in {{$currency}} <span class="amount-range"></span></label>
                                    <div class="relative">
                                        <input type="text" name="amount" id="amount"
                                            oninput="this.value = validateDouble(this.value)"
                                            class="form-control !text-lg withdrawAmount" placeholder="Enter Amount"
                                            aria-describedby="basic-addon1">
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center" id="basic-addon1">
                                            {{ $currency }}
                                        </span>
                                    </div>
                                    {{-- <div class="font-Inter text-xs text-red-500 pt-2 inline-block "></div> --}}
                                </div>
                                <div class="input-area relative conversion mt-4 hidden">
                                    <label for="exampleFormControlInput1" class="form-label">Amount in <span class="conversion_currency"></span></label>
                                    <div class="relative">
                                        <input type="text" oninput="this.value = validateDouble(this.value)"
                                            class="form-control !text-lg " id="converted-amount"
                                            placeholder="Enter Amount" aria-describedby="basic-addon2">
                                        <span
                                            class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center"
                                            id="basic-addon2">{{ $currency }}</span>
                                    </div>
                                    {{-- <div class="font-Inter text-xs text-red-500 pt-2 inline-block conversion-rate"></div> --}}
                                </div> 
                                <div class="payment-details" style="display: none">
                                    <div class="buttons mt-4 pt-4">
                                        <button type="submit" class="btn w-full inline-flex justify-center btn-primary withdraw-btn">
                                            {{ __('Request Withdraw') }}
                                        </button>
                                    </div>
                                </div>
                                <style>
                                    button:disabled {
                                        opacity: 0.6;
                                    }
                                </style>
                            @else
                                
                                <div class="fflex space-x-2 items-center">
                                    <a href="{{ route('user.withdraw.account.index') }}" style="border-width:" class="btn btn-outline-dark inline-flex items-center justify-center">
                                        <span class="flex items-center">
                                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:hand-coins"></iconify-icon>
                                            Add Withdraw Details
                                        </span>
                                    </a>
                                </div>
                   
                            @endif
                            
                        </div>
                    </div>
                </div>
                <div class="payment-details" style="display: none">
                    <div class="card transaction-list">
                        <div class="card-body p-6">
                            <div class="flex flex-wrap justify-between items-center mb-5">
                                <div class="space-x-3">
                                    <h5>Withdraw Details</h5>
                                </div>
                            </div>

                            <table class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                                <tbody class="selectDetailsTbody">
                                    <tr class="detailsCol border-b border-slate-100 dark:border-slate-700">
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Withdraw Amount') }}</strong>
                                        </td>
                                        <td class="dark:text-slate-300">
                                            <span class="withdrawAmount"></span>
                                            {{$currency}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        "use strict";
        var info = [];
        var currency = @json($currency)

        $("#withdrawAccountId").on('change', function (e) {
            e.preventDefault();
            
            $('.selectDetailsTbody').children().not(':first', ':second').remove();
            var accountId = $(this).val()
            var amount = $('.withdrawAmount').val();

            if (!isNaN(accountId)) {
                var url = '{{ route("user.withdraw.details",['accountId' => ':accountId', 'amount' => ':amount']) }}';
                url = url.replace(':accountId', accountId,);
                url = url.replace(':amount', amount);

                $.get(url, function (data) {
                    info = data.info;
                    if (info.pay_currency === currency) {
                        $('.conversion').addClass('hidden');
                    } else {
                        $('.conversion').removeClass('hidden');
                        $('#basic-addon2').text(info.pay_currency);
                        $('#amount').trigger('keyup')
                        $('.conversion-rate').text('1' + ' ' + currency + ' = ' + info.rate + ' ' + info.pay_currency)

                    }
                    // console.log(data)
                    $(data.html).insertAfter(".detailsCol");
                    $('.withdrawAmountRange').text(info.range)
                    $('.conversion_currency').text(info.pay_currency)
                    $('.processing-time').text(info.processing_time)
                    $('.amount-range').text('(Min: ' + info.min_withdraw + ' - Max: ' + info.max_withdraw + ')')
                    $('.payment-details').show()
                })
            }


        })

        $("#amount").on('keyup', function (e) {
            "use strict"
            e.preventDefault();
            console.log('amount')
            var amount = $(this).val()
            var charge = info.charge_type === 'percentage' ? calPercentage(amount, info.charge) : info.charge
            $('.withdrawAmount').text(amount)
            $('.withdrawFee').text(charge)
            $('.processing-time').text(info.processing_time)
            $('.withdrawAmountRange').text(info.range)
            $('.pay-amount').text(parseFloat(((amount * info.rate) - (charge * info.rate)).toFixed(4)).toString() + ' ' + info.pay_currency)
            $('#converted-amount').val(parseFloat((amount * info.rate).toFixed(4)).toString())

        })
        $("#converted-amount").on('keyup', function (e) {
            "use strict"
            e.preventDefault();
            console.log('converted-amount')
            var converted_amount = $(this).val();
            var amount = parseFloat((converted_amount / info.rate).toFixed(4)).toString();
            $('#amount').val(amount);
            var charge = info.charge_type === 'percentage' ? calPercentage(amount, info.charge) : info.charge
            $('.withdrawAmount').text(amount)
            $('.withdrawFee').text(charge)
            $('.processing-time').text(info.processing_time)
            $('.withdrawAmountRange').text(info.range)
            $('.pay-amount').text(parseFloat(((amount * info.rate) - (charge * info.rate)).toFixed(4)).toString() + ' ' + info.pay_currency)


        })

        $('#withdraw-form').on('submit', function() {
            $('.withdraw-btn').attr('disabled', 'true')
        })
    </script>
@endsection
