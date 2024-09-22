@extends('frontend::send_money.index-internal')
@section('send_money_content_internal')

    <div class="progress-steps-form">
        <form action="{{ route('user.send-money.internal-now') }}" method="post">
            @csrf
            <input type="hidden" name="target_type" id="selectedAccountType" value="">
            <input type="hidden" name="receiver_type" id="selectedReceiverAccountType" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                <div>
                    <h4 class="text-xl text-slate-900 mb-3">
                        {{ __('Enter your transfer details.') }}
                    </h4>
                    <div class="card">
                        <div class="card-body p-6">
                            <div class="input-area relative mb-5">
                                <label for="exampleFormControlInput1" class="form-label">{{ __('Account From:') }}</label>
                                <div class="input-group select2-lg">
                                    <select id="tradingAccount" name="target_id" class="select2 form-control !text-lg w-full mt-2 py-2">
                                        <option selected disabled>--{{ __('Select Account') }}--</option>
                                        @foreach($forexAccounts as $forexAccount)
                                            <option value="{{ the_hash($forexAccount->login) }}" data-type="forex">{{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})</option>
                                        @endforeach
                                        @include('frontend::wallet.include.__all-wallets-dropdown', ['target_id_name' => 'target_id'])
                                        <option value="profit_wallet" data-type="wallet">{{ __('Profit Wallet') . ' (' . $user->profit_balance . ' ' . $currency . ')' }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="input-area relative mb-5">
                                <label for="exampleFormControlInput1" class="form-label">{{ __('Account To:') }}</label>
                                <div class="input-group select2-lg">
                                    <select id="receiverTradingAccount" name="receiver_account" class="select2 form-control !text-lg w-full mt-2 py-2">
                                        <option selected disabled>--{{ __('Select Account') }}--</option>
                                        @foreach($forexAccounts as $forexAccount)
                                            <option value="{{ the_hash($forexAccount->login) }}" data-type="forex">{{ $forexAccount->login }} - {{ $forexAccount->account_name }}({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})</option>
                                        @endforeach
                                        @include('frontend::wallet.include.__all-wallets-dropdown', ['target_id_name' => 'receiver_account'])
                                    </select>
                                </div>
                            </div>

                            <div class="input-area relative mb-5">
                                <label for="exampleFormControlInput1" class="form-label">{{ __('Enter Amount') }}</label>
                                <div class="relative">
                                    <input type="text" class="form-control !text-lg sendAmount" name="amount" required
                                           placeholder="Enter Amount" aria-label="Amount"
                                           oninput="this.value = validateDouble(this.value)" aria-describedby="basic-addon1">
                                    <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center" id="basic-addon1">
                                        {{ $currency }}
                                    </span>
                                </div>
                                <div class="font-Inter text-xs text-red-500 pt-2 inline-block min-max">
                                    {{ 'Minimum '. setting('internal_min_send','fee').' '.$currency.' and Maximum '. setting('internal_max_send','fee').' '.$currency }}
                                </div>
                            </div>

                            <div class="input-area relative col-span-12">
                                <label for="exampleFormControlInput1"
                                       class="form-label">{{ __('Transfer Note (Optional)') }}</label>
                                <textarea class="form-control !text-lg" rows="5" placeholder="Transfer Note" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-xl text-slate-900 mb-3">
                        {{ __('Review Details:') }}
                    </h4>
                    <div class="card">
                        <div class="card-body p-6">
                            <div class="transaction-list mt-5">
                                <div class="max-w-[1005px] mx-auto my-8 rounded-md overflow-x-auto">
                                    <table class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                                        <tbody>
                                            <tr>
                                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                                    <strong>{{ __('Payment Amount') }}</strong>
                                                </td>
                                                <td class="dark:text-slate-300">
                                                    <span class="previewAmount"></span> {{ $currency }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                                    <strong>{{ __('Charge') }}</strong>
                                                </td>
                                                <td class="dark:text-slate-300">
                                                    <span class="previewCharge"></span> {{ $currency }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                                    <strong>{{ __('User Account') }}</strong>
                                                </td>
                                                <td class="dark:text-slate-300 userAccount"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="buttons border-t border-slate-100 dark:border-slate-700 mt-4 pt-4">
                                        <button type="submit" class="btn w-full inline-flex justify-center btn-primary">
                                            {{ __('Transfer Now') }}
                                            <i class="anticon anticon-double-right"></i>
                                        </button>
                                    </div>
                                </div>
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

        $('#tradingAccount').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var selectedAccountType = selectedOption.data('type');
            $('#selectedAccountType').val(selectedAccountType);  // Set the selected account type
        });

        $('#receiverTradingAccount').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var selectedReceiverAccountType = selectedOption.data('type');
            $('#selectedReceiverAccountType').val(selectedReceiverAccountType);  // Set the selected receiver account type
        });


        $('.userAccountCheck').on('change',function (e) {
            "use strict"
            var account = $(this).val();

            $('.userAccount').text(account)

            var url = '{{ route("user.account.exist",":account") }}';
            url = url.replace(':account', account);
            $.get(url, function (data) {
                $('.notifyUser').text(data)
            })
        })

        $('.sendAmount').on('keyup',function (e) {
            "use strict"
            var amount = $(this).val();
            $('.previewAmount').text(amount);

            var charge = @json(setting('internal_send_charge','fee'));
            var chargeType = @json(setting('internal_send_charge_type','fee'));


            if (chargeType === 'percentage') {
                var finalCharge = calPercentage(amount, charge)
            } else {
                var finalCharge = charge

            }
            $('.previewCharge').text(finalCharge);
        })
        $('#tradingAccount').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var selectedAccountType = selectedOption.data('type');
            $('#selectedAccountType').val(selectedAccountType);
        });

    </script>
@endsection



