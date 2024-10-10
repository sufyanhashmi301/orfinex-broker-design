@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Now') }}
@endsection
@section('content')
<div class="flex justify-end flex-wrap items-center mb-5">
    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
        <a href="{{ route('user.withdraw.account.index') }}" class="btn btn-primary loaderBtn inline-flex items-center">
            {{ __('Add Withdraw Account') }}
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
                    <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Withdraw Amount') }}</h4>
                </div>
            </div>
            <div class="single-step">
                <div class="progress_bar mb-5"></div>
                <div class="">
                    <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 2') }}</div>
                    <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Success') }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="progress-steps-form">
    <form action="{{ route('user.withdraw.now') }}" method="post" id="withdrawForm">
        @csrf
        <input type="hidden" name="account_type" value="{{ old('account_type') }}">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
            <div>
                <h4 class="text-xl text-slate-900 mb-3">
                    {{ __('Enter your deposit details.') }}
                </h4>
                <div class="card">
                    <div class="card-body p-6">
                        <div class="input-area relative mb-5">
                            <label for="exampleFormControlInput1" class="form-label">
                                {{ __('Account to withdraw:') }}
                            </label>
                            <div class="input-group select2-lg">
                                <select id="tradingAccount" name="target_id"
                                        class="select2 form-control !text-lg w-full mt-2 py-2">
                                    <option selected
                                            class="inline-block font-Inter font-normal text-sm text-slate-600"
                                            disabled>
                                        --{{ __('Select Account') }}--
                                    </option>
                                    {{-- Forex Accounts --}}
                                    @foreach($forexAccounts as $forexAccount)
                                        <option value="{{ the_hash($forexAccount->login) }}"
                                                data-type="forex"
                                                {{ old('target_id') == the_hash($forexAccount->login) ? 'selected' : '' }}
                                                class="inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $forexAccount->login }} - {{ $forexAccount->account_name }}
                                            ({{ get_mt5_account_equity($forexAccount->login) }} {{ $currency }})
                                        </option>
                                    @endforeach
                                    {{-- Wallet Accounts --}}
                                    @include('frontend::wallet.include.__all-wallets-dropdown', ['target_id_name' => 'target_id'])
                                </select>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label for="exampleFormControlInput1" class="form-label">
                                {{ __('Withdraw Account') }}
                            </label>
                            <div class="input-group select2-lg">
                                <select name="withdraw_account" id="withdrawAccountId"
                                        class="select2 form-control !text-lg w-full mt-2 py-2">
                                    <option selected
                                            class="inline-block font-Inter font-normal text-sm text-slate-600"
                                            disabled>
                                        {{ __('Withdraw Method') }}
                                    </option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}"
                                                {{ old('withdraw_account') == $account->id ? 'selected' : '' }}
                                                class="inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $account->method_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="font-Inter text-xs text-danger pt-2 inline-block processing-time"></div>
                        </div>
                        <div class="input-area relative">
                            <label for="exampleFormControlInput1" class="form-label">{{ __('Amount') }}</label>
                            <div class="relative">
                                <input type="text" name="amount" id="amount"
                                       oninput="this.value = validateDouble(this.value)"
                                       class="form-control !text-lg withdrawAmount"
                                       placeholder="{{ __('Enter Amount') }}"
                                       value="{{ old('amount') }}"
                                       aria-describedby="basic-addon1">
                                <span
                                    class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center"
                                    id="basic-addon1">
                                    {{ $currency }}
                                </span>
                            </div>
                            <div
                                class="font-Inter text-xs text-danger pt-2 inline-block withdrawAmountRange"></div>
                        </div>
                        <div class="input-area relative conversion hidden">
                            <label for="exampleFormControlInput1" class="form-label">{{ __('Amount') }}</label>
                            <div class="relative">
                                <input type="text" oninput="this.value = validateDouble(this.value)"
                                       class="form-control !text-lg " id="converted-amount"
                                       placeholder="{{ __('Enter Amount') }}" aria-describedby="basic-addon2">
                                <span
                                    class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center"
                                    id="basic-addon2">{{ $currency }}</span>
                            </div>
                            <div class="font-Inter text-xs text-danger pt-2 inline-block conversion-rate"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="text-xl text-slate-900 mb-3">
                    {{ __('Withdraw Details') }}
                </h4>
                <div class="card transaction-list">
                    <div class="card-body p-6">
                        <table class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                            <tbody class="selectDetailsTbody">
                            <tr class="detailsCol">
                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                    <strong>{{ __('Withdraw Amount') }}</strong>
                                </td>
                                <td class="dark:text-slate-300">
                                    <span class="withdrawAmount">{{ old('amount') }}</span>
                                    {{ $currency }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="buttons border-t border-slate-100 dark:border-slate-700 mt-4 pt-4">
                            <button type="submit" class="btn w-full inline-flex justify-center btn-primary">
                                {{ __('Withdraw Money') }}
                            </button>
                        </div>
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
        var currency = @json($currency);

        // Capture the selected account and append the `data-type` to the form
        $("#tradingAccount").on('change', function (e) {
            e.preventDefault();

            // Get the selected option and its data-type attribute
            var selectedOption = $(this).find('option:selected');
            var dataType = selectedOption.data('type'); // Get the data-type (e.g., forex or wallet)

            // Append the data-type to a hidden input field in the form
            $('input[name="account_type"]').val(dataType);
        });

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
                    $(data.html).insertAfter(".detailsCol");

                    $('.withdrawAmountRange').text(info.range)
                    $('.processing-time').text(info.processing_time)
                })
            }


        })

        $("#amount").on('keyup', function (e) {
            "use strict"
            e.preventDefault();
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
    </script>
@endsection
