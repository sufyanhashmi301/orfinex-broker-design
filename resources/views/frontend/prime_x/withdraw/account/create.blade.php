@extends('frontend::user.setting.index')
@section('title')
    {{ __('Withdraw Account Create') }}
@endsection

@section('settings-content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">@yield('title')</h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('user.withdraw.account.index') }}" class="btn btn-primary loaderBtn inline-flex items-center justify-center">
                    {{ __('Withdraw Accounts') }}
                </a>
            </div>
        </div>
        <div class="card-body p-6">
            @php
                $withdrawAccountApproval = setting('withdraw_account_approval', 'withdraw_settings');
                $withdrawAccountOtp = setting('withdraw_account_otp', 'withdraw_settings');
            @endphp

            @php
                $twoFaEnabledForUser = setting('fa_verification', 'permission') && auth()->user()->two_fa;
            @endphp
            
            @if($withdrawAccountOtp)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:shield-check" class="text-blue-600 dark:text-blue-400 text-xl"></iconify-icon>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                {{ __('Two-Factor Authentication Required') }}
                            </h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                <p>{{ __('For security purposes, you will receive a verification code via email after submitting this form. Please verify the OTP to complete your withdraw account creation.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if($withdrawAccountApproval)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:clock" class="text-yellow-600 dark:text-yellow-400 text-xl"></iconify-icon>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                {{ __('Manual Approval Required') }}
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <p>{{ __('Your withdraw account will be reviewed by our admin team before approval. You will be notified once the review is complete.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="progress-steps-form">
                <form action="{{ route('user.withdraw.account.store') }}" method="post" enctype="multipart/form-data" id="withdrawAccountForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 selectMethodRow">
                        <div class="input-area relative selectMethodCol">
                            <label for="exampleFormControlInput1" class="form-label">
                                {{ __('Choice Method:') }}
                            </label>
                            <div class="input-group select2-lg">
                                <select name="withdraw_method_id" id="selectMethod" class="select2 form-control !text-lg w-full mt-2 py-2">
                                    <option selected>{{ __('Select Method') }}</option>
                                    @foreach($withdrawMethods as $raw)
                                        <option value="{{ $raw->id }}">{{ $raw->name }}
                                            ({{ ucwords($raw->type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons text-right mt-4">
                        <button type="submit" id="submitWithdrawAccountBtn" class="btn inline-flex justify-center btn-primary">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:check"></iconify-icon>
                            {{ __('Add New Withdraw Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include OTP Modals -->
    @include('frontend::withdraw.modal.__account_creation_otp_form')
    @include('frontend::withdraw.modal.__account_creation_cancel_otp')
    @include('frontend::withdraw.account.modal.__ga_form')
    <!-- Choice Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
         id="accountCreationAuthChoiceModal"
         tabindex="-1"
         aria-labelledby="accountCreationAuthChoiceModalLabel"
         aria-hidden="true"
         data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body px-6 py-6 text-center">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-primary text-primary bg-opacity-30 mb-5" style="--tw-bg-opacity: 0.3;">
                        <iconify-icon class="text-4xl" icon="lucide:shield-check"></iconify-icon>
                    </div>
                    <div class="title mb-2">
                        <h4 class="text-xl font-medium dark:text-white capitalize" id="accountCreationAuthChoiceModalLabel">
                            {{ __('Select Verification Method') }}
                        </h4>
                    </div>
                    <p class="dark:text-slate-100">{{ __('Choose how you want to verify account creation.') }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-5">
                        <button type="button" class="btn btn-dark w-full inline-flex items-center justify-center" id="accountCreationChooseOtpBtn">
                            {{ __('Email OTP') }}
                        </button>
                        <button type="button" class="btn btn-primary w-full inline-flex items-center justify-center" id="accountCreationChooseGaBtn">
                            {{ __('Authenticator App') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#selectMethod").on('change', function (e) {
            "use strict"
            e.preventDefault();

            //$('.manual-row').empty();
            $('.selectMethodRow').children().not(':first').remove();

            var id = $(this).val()

            var url = '{{ route("user.withdraw.method",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {
                $(data).insertAfter(".selectMethodCol");
                imagePreview();
            })
        })

        // Handle form submission for withdraw account creation
        $('#withdrawAccountForm').on('submit', function(e) {
            e.preventDefault();
            
            const bothMethodsEnabled = @json($withdrawAccountOtp && $twoFaEnabledForUser);
            const onlyGaEnabled = @json(!$withdrawAccountOtp && $twoFaEnabledForUser);
            const onlyOtpEnabled = @json($withdrawAccountOtp && !$twoFaEnabledForUser);

            const form = this;
            const formData = new FormData(form);

            if (bothMethodsEnabled) {
                $('#accountCreationAuthChoiceModal').modal('show');
                // attach one-time handlers for choice
                $('#accountCreationChooseGaBtn').off('click').on('click', function() {
                    formData.set('verification_method', 'ga');
                    $('#accountCreationAuthChoiceModal').modal('hide');
                    submitAccountCreationForm(formData);
                });
                $('#accountCreationChooseOtpBtn').off('click').on('click', function() {
                    formData.set('verification_method', 'otp');
                    // Show loader and disable choice button while sending OTP
                    var $btn = $(this);
                    var originalHtml = $btn.html();
                    $btn.prop('disabled', true).html('<iconify-icon icon="lucide:loader-2" class="animate-spin ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Sending...") }}');
                    if (typeof $('#page-loader').show === 'function') { $('#page-loader').show(); }
                    $('#accountCreationAuthChoiceModal').modal('hide');
                    submitAccountCreationForm(formData, $btn, originalHtml);
                });
                return;
            }

            if (onlyGaEnabled) {
                formData.set('verification_method', 'ga');
                submitAccountCreationForm(formData);
                return;
            }

            // Check if OTP is required
            @if($withdrawAccountOtp)
                // Submit form via AJAX to get OTP
                // Set loading state on submit button
                const $submitBtn = $('#submitWithdrawAccountBtn');
                const originalBtnHtml = $submitBtn.html();
                $submitBtn.prop('disabled', true).html('<iconify-icon icon="lucide:loader-2" class="animate-spin ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Processing...") }}');
                submitAccountCreationForm(formData, $submitBtn, originalBtnHtml);
            @else
                // Submit form normally if OTP is not required
                this.submit();
            @endif
        });

        function submitAccountCreationForm(formData, $submitBtn = $('#submitWithdrawAccountBtn'), originalBtnHtml = $('#submitWithdrawAccountBtn').html()) {
            $.ajax({
                url: $('#withdrawAccountForm').attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.show_ga) {
                            $('#accountCreationGaModal').modal('show');
                        } else if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            tNotify('success', response.message || '');
                            $('#accountCreationOtpModal').modal('show');
                        }
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        if (response.is_restricted || (response.message.includes && (response.message.includes('restricted') || response.message.includes('Too many')))) {
                            tNotify('error', response.message);
                        } else {
                            tNotify('error', response.message);
                        }
                    } else {
                        tNotify('error', '{{ __("An error occurred. Please try again.") }}');
                    }
                },
                complete: function() {
                    if ($submitBtn && originalBtnHtml) {
                        $submitBtn.prop('disabled', false).html(originalBtnHtml);
                    }
                    if (typeof $('#page-loader').hide === 'function') { $('#page-loader').hide(); }
                }
            });
        }
        // Ensure modals are in body to avoid overlay/pointer issues
        (function ensureModalParents(){
            $('#accountCreationAuthChoiceModal, #accountCreationGaModal, #accountCreationOtpModal, #confirmAccountCreationCancelModal').appendTo('body');
        })();

        // GA verification for account creation
        $('.accountCreationGaSubmitBtn').on('click', function() {
            const code = $('#accountCreationGaInput').val();
            if (!code || code.length !== 6) {
                tNotify('error', '{{ __("Please enter a valid 6-digit OTP") }}');
                return;
            }
            $(this).prop('disabled', true).html('<iconify-icon icon="lucide:loader-2" class="animate-spin ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Verifying...") }}');
            $.ajax({
                url: '{{ route("user.withdraw.account.verify-ga.post") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    one_time_password: code
                },
                success: function(response) {
                    if (response.status === 'success') {
                        tNotify('success', response.message);
                        $('#accountCreationGaModal').modal('hide');
                        setTimeout(function() {
                            window.location.href = response.redirect || '{{ route("user.withdraw.account.index") }}';
                        }, 1200);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON || {};
                    tNotify('error', response.message || '{{ __("Invalid authenticator code.") }}');
                    $('#accountCreationGaInput').val('').focus();
                },
                complete: function() {
                    $('.accountCreationGaSubmitBtn').prop('disabled', false).html('<iconify-icon icon="lucide:check" class="ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Verify") }}');
                }
            });
        });

        // Auto-focus OTP input when modal opens
        $('#accountCreationOtpModal').on('shown.bs.modal', function () {
            $('#accountCreationOtpInput').focus();
        });

        // Handle OTP input for account creation (NO AUTO-SUBMIT)
        $('#accountCreationOtpInput').on('input', function() {
            const value = $(this).val();
            
            // Only allow numbers
            const numericValue = value.replace(/[^0-9]/g, '');
            if (value !== numericValue) {
                $(this).val(numericValue);
            }
            
            // Limit to 4 digits
            if (numericValue.length > 4) {
                $(this).val(numericValue.substring(0, 4));
            }
            // Note: No auto-submit - user must click "Verify OTP" button
        });

        // Handle paste event for OTP (NO AUTO-SUBMIT)
        $('#accountCreationOtpInput').on('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
            const numericValue = pastedText.replace(/[^0-9]/g, '').substring(0, 4);
            $(this).val(numericValue);
            // Note: No auto-submit - user must click "Verify OTP" button
        });

        // Handle OTP verification for account creation
        $('.accountCreationOtpSubmitBtn').on('click', function() {
            const otp = $('#accountCreationOtpInput').val();
            
            if (!otp || otp.length !== 4) {
                tNotify('error', '{{ __("Please enter a valid 4-digit OTP") }}');
                return;
            }
            
            $(this).prop('disabled', true).html('<iconify-icon icon="lucide:loader-2" class="animate-spin ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Verifying...") }}');
            
            $.ajax({
                url: '{{ route("user.withdraw.account.verify-otp.post") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    verification_code: otp
                },
                success: function(response) {
                    if (response.status === 'success') {
                        tNotify('success', response.message);
                        
                        // Close modal
                        $('#accountCreationOtpModal').modal('hide');
                        
                        // Redirect to success page
                        setTimeout(function() {
                            window.location.href = response.redirect || '{{ route("user.withdraw.account.index") }}';
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        tNotify('error', response.message);
                        
                        // Check if it's a restriction message
                        if (response.is_restricted || response.message.includes('restricted') || response.message.includes('Too many')) {
                            // Show restriction message as notification only, don't show modal restriction
                            tNotify('error', response.message);
                            // Close modal if restricted
                            $('#accountCreationOtpModal').modal('hide');
                        } else {
                            // Clear OTP input on error (only if not restricted)
                            $('#accountCreationOtpInput').val('').focus();
                        }
                    } else {
                        tNotify('error', '{{ __("An error occurred. Please try again.") }}');
                    }
                    
                    $('.accountCreationOtpSubmitBtn').prop('disabled', false).html('<iconify-icon icon="lucide:check" class="ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Verify OTP") }}');
                }
            });
        });

        // Handle resend OTP for account creation
        $('#resendAccountCreationOtpBtn').on('click', function(e) {
            e.preventDefault();
            
            if ($(this).hasClass('disabled')) return;
            
            $(this).addClass('disabled').html('{{ __("Sending...") }}');
            
            $.ajax({
                url: '{{ route("user.withdraw.account.resend-otp") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        tNotify('success', response.message);
                        
                        // Clear OTP input
                        $('#accountCreationOtpInput').val('').focus();
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        tNotify('error', response.message);
                        
                        // Check if it's a restriction message
                        if (response.is_restricted || response.message.includes('restricted') || response.message.includes('Too many')) {
                            // Show restriction message as notification only, don't show modal restriction
                            tNotify('error', response.message);
                            // Close modal if restricted
                            $('#accountCreationOtpModal').modal('hide');
                        }
                    } else {
                        tNotify('error', '{{ __("Failed to resend OTP. Please try again.") }}');
                    }
                },
                complete: function() {
                    // Always reset the button state
                    $('#resendAccountCreationOtpBtn').removeClass('disabled').html('{{ __("Resend") }}');
                }
            });
        });

        // Handle cancel OTP verification for account creation
        $('#cancelAccountCreationOtpVerification').on('click', function(e) {
            e.preventDefault();
            $('#confirmAccountCreationCancelModal').modal('show');
        });

        // Handle confirm cancel for account creation
        $('.confirmAccountCreationCancelBtn').on('click', function() {
            $('#accountCreationOtpModal').modal('hide');
            $('#confirmAccountCreationCancelModal').modal('hide');
        });

        // Reset modal state when modal is hidden
        $('#accountCreationOtpModal').on('hidden.bs.modal', function() {
            $('#accountCreationOtpInput').val('').prop('disabled', false);
            $('.accountCreationOtpSubmitBtn').prop('disabled', false).html('<iconify-icon icon="lucide:check" class="ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Verify OTP") }}');
            $('#resendAccountCreationOtpBtn').prop('disabled', false).removeClass('disabled').html('{{ __("Resend") }}');
        });

        // Reset modal state when modal is shown
        $('#accountCreationOtpModal').on('shown.bs.modal', function() {
            $('#accountCreationOtpInput').val('').prop('disabled', false);
            $('.accountCreationOtpSubmitBtn').prop('disabled', false).html('<iconify-icon icon="lucide:check" class="ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Verify OTP") }}');
            $('#resendAccountCreationOtpBtn').prop('disabled', false).removeClass('disabled').html('{{ __("Resend") }}');
        });

        $('#accountCreationGaModal').on('show.bs.modal', function () {
            let $inputs = $(".otp-input");

            // unbind first to prevent duplicate bindings
            $inputs.off();

            // move to next on input
            $inputs.on("input", function () {
                let $this = $(this);
                let value = $this.val().replace(/\D/g, ""); // allow only digits
                $this.val(value);

                if (value.length === 1) {
                    $this.next(".otp-input").focus().select();
                }

                updateHiddenInput();
            });

            // move to prev on backspace
            $inputs.on("keydown", function (e) {
                if (e.key === "Backspace" && !$(this).val()) {
                    $(this).prev(".otp-input").focus().select();
                }
            });

            // allow paste of full OTP
            $inputs.first().on("paste", function (e) {
                let paste = (e.originalEvent || e).clipboardData.getData("text").trim();
                if (/^\d+$/.test(paste) && paste.length === $inputs.length) {
                    $inputs.each(function (i) {
                        $(this).val(paste[i]);
                    });
                    updateHiddenInput();
                    $inputs.last().focus();
                }
            });

            function updateHiddenInput() {
                let otp = "";
                $inputs.each(function () {
                    otp += $(this).val();
                });
                $("#accountCreationGaInput").val(otp);
            }
        });
    </script>
@endsection
