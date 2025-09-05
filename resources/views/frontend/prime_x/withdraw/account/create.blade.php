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
            
            // Check if OTP is required
            @if($withdrawAccountOtp)
                // Submit form via AJAX to get OTP
                const formData = new FormData(this);

                // Set loading state on submit button
                const $submitBtn = $('#submitWithdrawAccountBtn');
                const originalBtnHtml = $submitBtn.html();
                $submitBtn.prop('disabled', true).html('<iconify-icon icon="lucide:loader-2" class="animate-spin ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Processing...") }}');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            tNotify('success', response.message);
                            // Show OTP modal
                            $('#accountCreationOtpModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.message) {
                            {{-- tNotify('error', response.message); --}}
                            
                            // Check if it's a restriction message
                            if (response.is_restricted || response.message.includes('restricted') || response.message.includes('Too many')) {
                                // Show restriction message as notification instead of alert
                                tNotify('error', response.message);
                            }
                        } else {
                            tNotify('error', '{{ __("An error occurred. Please try again.") }}');
                        }
                    },
                    complete: function() {
                        // Reset loading state on submit button
                        $submitBtn.prop('disabled', false).html(originalBtnHtml);
                    }
                });
            @else
                // Submit form normally if OTP is not required
                this.submit();
            @endif
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
    </script>
@endsection
