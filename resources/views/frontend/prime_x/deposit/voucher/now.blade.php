@extends('frontend::deposit.index')
@section('deposit_content')
    <div class="progress-steps-form mb-6">
        <form action="{{ route('user.deposit.redeem.now') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="gateway_code" value="{{ $gatewayCode }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                <div>
                    <h4 class="text-xl text-slate-900 mb-3">
                        {{ __('Enter your deposit details.') }}
                    </h4>
                    <div class="card">
                        <div class="card-body p-6 space-y-5">
                            <div class="input-area relative">
                                <label for="" class="form-label">{{ __('Account to Deposit:') }}</label>
                                <div class="input-group select2-lg">
                                    <select  id="tradingAccount" name="target_id" class="select2 form-control !text-lg w-full mt-2 py-2">
                                        <option selected disabled>--{{ __('Select Account') }}--</option>
                                        @foreach($forexAccounts as $forexAccount)
                                            <option value="{{the_hash($forexAccount->login) }}" data-type="forex" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})</option>
                                        @endforeach
                                        {{--mail wallet--}}
                                        @include('frontend::wallet.include.__specific-wallet-dropdown', ['target_id_name' => 'target_id', 'wallet_type' => \App\Enums\AccountBalanceType::MAIN])
                                    </select>
                                </div>
                            </div>
                            <div class="input-area relative">
                                <label for="" class="form-label">{{ __('Enter Code:') }}</label>
                                <input type="text" name="code" class="form-control !text-lg" id="voucher-code">
                                <div class="font-Inter text-xs text-danger inline-block invalid-code hidden"></div>
                            </div>
                            <div class="input-area relative">
                                <label for="" class="form-label">{{ __('Amount:') }}</label>
                                <div class="relative">
                                    <input type="text" name="amount" class="form-control !text-lg" id="voucher-amount" readonly>
                                    <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center" id="basic-addon2">{{ $currency }}</span>
                                </div>
                            </div>
                            <div class="manual-row space-y-5 hidden"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-xl text-slate-900 mb-3">
                        {{ __('Review Details:') }}
                    </h4>
                    <div class="card transaction-list">
                        <div class="card-body p-6">
                            <table class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                                <tbody>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Payment Method') }}</strong>
                                        </td>
                                        <td id="logo">
                                            <img src="" class="payment-method" alt="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Amount') }}</strong>
                                        </td>
                                        <td class="dark:text-slate-300">
                                            <span class="amount"></span>
                                            <span class="currency">{{ $currency }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Charge') }}</strong>
                                        </td>
                                        <td class="charge2 dark:text-slate-300"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Total') }}</strong>
                                        </td>
                                        <td class="total dark:text-slate-300"></td>
                                    </tr>
                                    <tr class="conversion">
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Conversion Rate') }}</strong>
                                        </td>
                                        <td class="conversion-rate dark:text-slate-300"></td>
                                    </tr>
                                    <tr class="conversion">
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Pay Amount') }}</strong>
                                        </td>
                                        <td class="pay-amount dark:text-slate-300"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Expires At') }}</strong>
                                        </td>
                                        <td class="expires-at dark:text-slate-300"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="buttons border-t border-slate-100 dark:border-slate-700 mt-4 pt-4">
                                <button type="submit" class="btn w-full inline-flex justify-center btn-primary proceed-btn">
                                    {{ __('Proceed to Payment') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-slate-800 bg-opacity-[14%] text-slate-800 dark:bg-slate-500 dark:bg-opacity-[14%] dark:text-slate-300">
        <div class="accordion-item">
            <h2 class="accordion-header text-lg" id="flush-headingStaySafeOnline">
                <button class="flex items-center w-full accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-staySafeOnline" aria-expanded="false" aria-controls="flush-staySafeOnline">
                    <iconify-icon class="text-xl mr-2" icon="lucide:info"></iconify-icon>
                    {{ __('Stay safe online') }}
                    <iconify-icon class="chevron-icon transition-transform duration-200 ease-in-out ml-auto" icon="lucide:chevron-down"></iconify-icon>
                </button>
            </h2>
            <div id="flush-staySafeOnline" class="accordion-collapse collapse" aria-labelledby="flush-headingStaySafeOnline" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body p-3 pb-0">
                    {{ __('Protect your security by never sharing your personal or credit card information over the phone, by email, or chat.') }}
                    <a href="" class="text-warning-600">Learn more</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let assetPath = '{{ asset('') }}/';

        // Capture the account type (forex or wallet) when the user selects an account
        $('#tradingAccount').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var selectedAccountType = selectedOption.data('type');
            $('#selectedAccountType').val(selectedAccountType);  // Set the selected account type
        });
        var globalData;
        var currency = @json($currency)

        $(document).ready(function() {
            "use strict"
            $('.manual-row').empty();
            var code = $("input[name='gateway_code']").val();
            var url = '{{ route("user.deposit.gateway",":code") }}';
            url = url.replace(':code', code);
            $.get(url, function (data) {

                globalData = data;
                
                $('.charge').text('Charge ' + data.charge + ' ' + (data.charge_type === 'percentage' ? ' % ' : currency))
                $('.conversion-rate').text('1' +' '+ currency + ' = ' + data.rate +' '+ data.currency)

                $('#logo').html(`<img class="payment-method h-12" src='${assetPath + data.logo}'>`);
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

            function updateVoucherCalculation(amount) {
                if (!globalData) return;

                amount = parseFloat(amount);

                $('.amount').text((Number(amount.toFixed(2))))
                $('.currency').text(currency)

                var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge
                $('.charge2').text(charge.toFixed(2) + ' ' + currency)

                var total = (Number(amount) - Number(charge));

                $('.total').text(total.toFixed(2) + ' ' + currency)

                $('.pay-amount').text(parseFloat((total * globalData.rate).toFixed(4)).toString() +' '+ globalData.currency)

                $('#converted-amount').val(parseFloat((total * globalData.rate).toFixed(4)).toString())
            }

            $('#voucher-code').on('input', function () {
                var code = $(this).val().trim();

                if (code.length < 6) {
                    $('#voucher-amount').val('');
                    $('.title, .code, .amount, .expires-at').text('');
                    $('.invalid-code').addClass('hidden').text('');
                    return;
                }
                
                $.ajax({
                    url: "{{ route('user.deposit.get.voucher') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        code: code
                    },
                    success: function (response) {
                        if (response.success) {

                            updateVoucherCalculation(response.amount);

                            $('#voucher-amount').val(response.amount);
                            $('.title').text(response.title);
                            $('.code').text(response.code);
                            $('#voucher_code').val(response.code);
                            $('.expires-at').text(response.expires_at);

                            // Hide any previous error
                            $('.invalid-code').addClass('hidden').text('');
                            $('.proceed-btn').removeAttr('disabled').removeClass('cursor-not-allowed');
                        }else{

                            updateVoucherCalculation(response.amount);
                            $('.invalid-code').removeClass('hidden').text(response.message);

                            // Optionally clear previous data
                            $('#voucher-amount').val('');
                            $('.title, .code, #voucher_code, .amount, .expires-at').text('');
                            $('.proceed-btn').attr('disabled', true).addClass('cursor-not-allowed');
                        }
                    }
                })
            });

        });
    </script>
@endsection
