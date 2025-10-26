@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Now') }}
@endsection
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="card mb-6">
        <div class="card-body hidden md:block p-3">
            <div class="progress-steps md:flex justify-between items-center gap-5">
                <div class="single-step current">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-100 mb-2">{{ __('Step - 1') }}</div>
                        <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Withdraw Amount') }}</h4>
                    </div>
                </div>
                <div class="single-step">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-100 mb-2">{{ __('Step - 2') }}</div>
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
                        {{ __('Enter your withdraw details.') }}
                    </h4>
                    <div class="card">
                        <div class="card-body space-y-5 p-6">
                            <div class="input-area relative">
                                <label for="exampleFormControlInput1" class="form-label">
                                    {{ __('Account to withdraw:') }}
                                </label>
                                <div class="input-group select2-lg">
                                    <select id="tradingAccount" name="target_id"
                                        class="select2 form-control !text-lg w-full mt-2 py-2">
                                        <option selected class="inline-block font-Inter font-normal text-sm text-slate-600"
                                            disabled>
                                            --{{ __('Select Account') }}--
                                        </option>
                                        {{-- Forex Accounts --}}
                                        @foreach ($forexAccounts as $forexAccount)
                                            <option value="{{ the_hash($forexAccount->login) }}" data-type="forex"
                                                {{ old('target_id') == the_hash($forexAccount->login) ? 'selected' : '' }}
                                                class="inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $forexAccount->login }} - {{ $forexAccount->account_name }}
                                                ({{ get_mt5_account_equity($forexAccount->login) }}
                                                {{ $forexAccount->schema->is_cent_account ? $forexAccount->currency . ' (Cents)' : $forexAccount->currency }})
                                            </option>
                                        @endforeach
                                        {{-- Wallet Accounts --}}
                                        @include('frontend::wallet.include.__all-wallets-dropdown', [
                                            'target_id_name' => 'target_id',
                                        ])
                                    </select>
                                </div>
                            </div>
                            <div class="input-area relative">
                                <div class="flex items-center justify-between gap-3">
                                    <label for="exampleFormControlInput1" class="form-label">
                                        {{ __('Withdraw Account') }}
                                    </label>
                                    <a href="{{ route('user.withdraw.account.index') }}"
                                        class="btn-link inline-flex items-center justify-center mb-2"
                                        style="min-width: fit-content;">
                                        <iconify-icon icon="lucide:plus"></iconify-icon>
                                        {{ __('Add New Withdrawal Account') }}
                                    </a>
                                </div>
                                <div class="input-group select2-lg">
                                    <select name="withdraw_account" id="withdrawAccountId"
                                        class="select2 form-control !text-lg w-full mt-2 py-2">
                                        <option selected class="inline-block font-Inter font-normal text-sm text-slate-600"
                                            disabled>
                                            {{ __('Withdraw Method') }}
                                        </option>
                                        @foreach ($accounts as $account)
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
                                        class="form-control !text-lg withdrawAmount" placeholder="{{ __('Enter Amount') }}"
                                        value="{{ old('amount') }}" aria-describedby="basic-addon1">
                                    <span
                                        class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-100 flex items-center justify-center"
                                        id="basic-addon1">
                                        {{ $currency }}
                                    </span>
                                </div>
                                <div class="font-Inter text-xs text-danger pt-2 inline-block withdrawAmountRange"></div>
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
                                        <td
                                            class="text-slate-900 dark:text-slate-100 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Withdraw Amount') }}</strong>
                                        </td>
                                        <td class="dark:text-slate-100">
                                            <span class="withdrawAmount">{{ old('amount') }}</span>
                                            {{ $currency }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="buttons border-t border-slate-100 dark:border-slate-700 mt-4 pt-4">
                                <button type="submit" class="withdrawSubmitBtn btn w-full inline-flex justify-center btn-primary space-x-2" data-loading-text="Processing...">
                                    <span>{{ __('Withdraw Money') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if (setting('contact_widget_withdraw_page', 'contact_widget'))
        @include('frontend::include.__contact_widget')
    @endif

    {{-- Modal for OTP --}}
    @include('frontend::withdraw.modal.__otp_form')

    {{-- Modal for GA --}}
    @include('frontend::withdraw.modal.__ga_form')

    {{-- Choice prompt (professional) --}}
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="authChoiceModal" tabindex="-1" aria-labelledby="authChoiceModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body px-6 py-6 text-center">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-primary text-primary bg-opacity-30 mb-5"
                        style="--tw-bg-opacity: 0.3;">
                        <iconify-icon class="text-4xl" icon="lucide:shield-check"></iconify-icon>
                    </div>
                    <div class="title mb-2">
                        <h4 class="text-xl font-medium dark:text-white capitalize" id="authChoiceModalLabel">
                            {{ __('Select Verification Method') }}
                        </h4>
                    </div>
                    <p class="dark:text-slate-100">{{ __('Choose how you want to verify this withdrawal request.') }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-5">
                        <button type="button" class="btn btn-dark w-full inline-flex items-center justify-center"
                            id="chooseOtpBtn">
                            {{ __('Email OTP') }}
                        </button>
                        <button type="button" class="btn btn-primary w-full inline-flex items-center justify-center"
                            id="chooseGaBtn">
                            {{ __('Authenticator App') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for Cancel --}}
    @include('frontend::withdraw.modal.__cancel_otp')
@endsection

@section('script')
    <script>
        "use strict";
        var info = [];
        var currency = @json($currency);

        // Capture the selected account and append the `data-type` to the form
        $("#tradingAccount").on('change', function(e) {
            e.preventDefault();

            // Get the selected option and its data-type attribute
            var selectedOption = $(this).find('option:selected');
            var dataType = selectedOption.data('type'); // Get the data-type (e.g., forex or wallet)

            // Append the data-type to a hidden input field in the form
            $('input[name="account_type"]').val(dataType);
        });

        $("#withdrawAccountId").on('change', function(e) {
            e.preventDefault();
            // $('.selectDetailsTbody').children().not(':first', ':second').remove();
            $('.selectDetailsTbody').children().not(':first').remove();
            var accountId = $(this).val()
            var amount = $('.withdrawAmount').val();

            if (!isNaN(accountId)) {
                var url =
                    '{{ route('user.withdraw.details', ['accountId' => ':accountId', 'amount' => ':amount']) }}';
                url = url.replace(':accountId', accountId, );
                url = url.replace(':amount', amount);
                url = url.replace(/\/+$/, '');

                $.get(url, function(data) {
                    info = data.info;
                    if (info.pay_currency === currency) {
                        $('.conversion').addClass('hidden');
                    } else {
                        $('.conversion').removeClass('hidden');
                        $('#basic-addon2').text(info.pay_currency);
                        $('#amount').trigger('keyup');
                        $('.conversion-rate').text('1' + ' ' + currency + ' = ' + info.rate + ' ' + info
                            .pay_currency);

                    }
                    $(data.html).insertAfter(".detailsCol");

                    $('.withdrawAmountRange').text(info.range)
                    $('.processing-time').text(info.processing_time)
                })
            }


        })

        $("#amount").on('keyup', function(e) {
            "use strict"
            e.preventDefault();
            var amount = $(this).val()
            var charge = info.charge_type === 'percentage' ? calPercentage(amount, info.charge) : info.charge
            $('.withdrawAmount').text(amount)
            $('.withdrawFee').text(charge)
            $('.processing-time').text(info.processing_time)
            $('.withdrawAmountRange').text(info.range)
            $('.pay-amount').text(parseFloat(((amount * info.rate) - (charge * info.rate)).toFixed(4)).toString() +
                ' ' + info.pay_currency)
            $('#converted-amount').val(parseFloat((amount * info.rate).toFixed(4)).toString())

        })
        $("#converted-amount").on('keyup', function(e) {
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
            $('.pay-amount').text(parseFloat(((amount * info.rate) - (charge * info.rate)).toFixed(4)).toString() +
                ' ' + info.pay_currency)
        })

        @if (session()->has('withdrawal_data') &&
                !session('withdraw_auth_required') &&
                session('otp') &&
                Carbon::now()->lt(session('otp_expiration')))
            $(document).ready(function() {
                $('#otpModal').modal('show');
            });
        @endif

        $('body').on('click', '.otpSubmitBtn', function(e) {
            e.preventDefault();

            var otp = $('#otpInput').val();

            $.ajax({
                url: '{{ route('user.withdraw.otp.verify') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    otp: otp,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#otpModal').modal('hide');

                        tNotify('success', response.message);

                        // Submit the form after OTP verification is successful
                        $('#withdrawForm').submit();
                        $('#page-loader').show();
                    }
                },
                error: function(xhr, status, error) {
                    var response = xhr.responseJSON;
                    if (response.status === 'error') {
                        tNotify('error', response.message);
                        $('#otpInput').val('');
                    }
                }
            })

        })

        // If both options are enabled, show choice modal based on server session flags
        @if (session('withdraw_auth_required'))
            $(document).ready(function() {
                const options = @json(session('withdraw_auth_options'));
                if (options && options.otp && options.ga) {
                    $('#authChoiceModal').modal('show');
                } else if (options && options.ga && !options.otp) {
                    $('#gaModal').modal('show');
                } else if (options && options.otp && !options.ga) {
                    $('#otpModal').modal('show');
                }
                @php
                    session()->forget('withdraw_auth_required');
                    session()->forget('withdraw_auth_options');
                @endphp
            });
        @endif

        // Choice handlers
        $('body').on('click', '#chooseGaBtn', function() {
            $('#authChoiceModal').modal('hide');
            $('#gaModal').modal('show');
        });
        $('body').on('click', '#chooseOtpBtn', function() {
            $('#authChoiceModal').modal('hide');
            // Show loader and disable button while sending OTP
            var $btn = $(this);
            var originalHtml = $btn.html();
            $btn.prop('disabled', true).html(
                '<iconify-icon icon="lucide:loader-2" class="animate-spin ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __('Sending...') }}'
                );
            if (typeof $('#page-loader').show === 'function') {
                $('#page-loader').show();
            }
            // Send OTP only when user selects Email OTP
            $.ajax({
                url: '{{ route('user.withdraw.otp.resend') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    tNotify('success', response.message ||
                        '{{ __('OTP has been sent. Please verify it to proceed.') }}');
                    $('#otpModal').modal('show');
                },
                error: function(xhr) {
                    const resp = xhr.responseJSON || {};
                    tNotify('error', resp.message ||
                        '{{ __('Failed to send OTP. Please try again.') }}');
                },
                complete: function() {
                    if (typeof $('#page-loader').hide === 'function') {
                        $('#page-loader').hide();
                    }
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        // GA submit for withdraw
        $('body').on('click', '.gaSubmitBtn', function() {
            const code = $('#gaInput').val();
            if (!code || code.length !== 6) {
                tNotify('error', '{{ __('Please enter a valid 6-digit code') }}');
                return;
            }
            $(this).prop('disabled', true).text('{{ __('Verifying...') }}');
            $.ajax({
                url: '{{ route('user.withdraw.ga.verify') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    one_time_password: code
                },
                success: function(resp) {
                    tNotify('success', resp.message);
                    $('#gaModal').modal('hide');
                    // Submit the form now that GA is verified (session flag set)
                    $('#withdrawForm').submit();
                    $('#page-loader').show();
                },
                error: function(xhr) {
                    const resp = xhr.responseJSON || {
                        message: '{{ __('Invalid authenticator code.') }}'
                    };
                    tNotify('error', resp.message);
                    $('#gaInput').val('').focus();
                },
                complete: function() {
                    $('.gaSubmitBtn').prop('disabled', false).text('{{ __('Verify') }}');
                }
            });
        });

        $('body').on('click', '#resendOtpBtn', function(e) {
            e.preventDefault();

            $(this).prop('disabled', true);
            $(this).text('Resending OTP...');

            $.ajax({
                url: '{{ route('user.withdraw.otp.resend') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    tNotify('success', response.message);
                },
                error: function(xhr, status, error) {
                    tNotify('error', 'An error occurred while resending the OTP. Please try again.');
                },
                complete: function() {
                    $('#resendOtpBtn').prop('disabled', false);
                    $('#resendOtpBtn').text('Resend OTP');
                }
            });
        });

        $(document).ready(function() {
            // Ensure modals are appended to body to avoid z-index/stacking issues
            $('#authChoiceModal, #gaModal, #otpModal').appendTo('body');
            var oldAccountType = '{{ old('account_type') }}';
            if (oldAccountType) {
                $('input[name="account_type"]').val(oldAccountType);
                $("#withdrawAccountId").trigger('change');
            }

            var oldAmount = '{{ old('amount') }}';
            if (oldAmount) {
                var amount = oldAmount;
                $("#amount").val(amount).trigger('keyup');
            }
        });

        // Cancel OTP -> show confirm modal above (hide OTP first to avoid stacking issues)
        $('body').on('click', '#cancelOtpVerification', function() {
            $('#otpModal').modal('hide');
            $('#confirmCancelModal').modal('show');
        });

        // When user confirms cancellation, close confirm; OTP stays hidden
        $('body').on('click', '.confirmCancelBtn', function() {
            $('#confirmCancelModal').data('confirmed', true);
            $('#confirmCancelModal').modal('hide');
        });

        // If user dismisses confirm without confirming, re-open OTP modal
        $('#confirmCancelModal').on('hidden.bs.modal', function() {
            const wasConfirmed = !!$(this).data('confirmed');
            if (!wasConfirmed) {
                $('#otpModal').modal('show');
            }
            $(this).removeData('confirmed');
        });

        $('#gaModal').on('show.bs.modal', function() {
            let $inputs = $(".otp-input");

            // unbind first to prevent duplicate bindings
            $inputs.off();

            // move to next on input
            $inputs.on("input", function() {
                let $this = $(this);
                let value = $this.val().replace(/\D/g, ""); // allow only digits
                $this.val(value);

                if (value.length === 1) {
                    $this.next(".otp-input").focus().select();
                }

                updateHiddenInput();
            });

            // move to prev on backspace
            $inputs.on("keydown", function(e) {
                if (e.key === "Backspace" && !$(this).val()) {
                    $(this).prev(".otp-input").focus().select();
                }
            });

            // allow paste of full OTP
            $inputs.first().on("paste", function(e) {
                let paste = (e.originalEvent || e).clipboardData.getData("text").trim();
                if (/^\d+$/.test(paste) && paste.length === $inputs.length) {
                    $inputs.each(function(i) {
                        $(this).val(paste[i]);
                    });
                    updateHiddenInput();
                    $inputs.last().focus();
                }
            });

            function updateHiddenInput() {
                let otp = "";
                $inputs.each(function() {
                    otp += $(this).val();
                });
                $("#gaInput").val(otp);
            }
        });

        $(document).on('submit', '#withdrawForm', function () {
            const $form = $(this);
            const $btn = $form.find('[type=submit]');

            $btn.buttonLoading(true, {
                text: '<span class="text-sm">Please wait...</span>'
            });
        });
    </script>
@endsection
