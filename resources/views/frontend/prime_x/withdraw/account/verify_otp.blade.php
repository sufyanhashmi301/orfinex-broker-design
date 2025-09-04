@extends('frontend::user.setting.index')
@section('title')
    {{ __('Two-Factor Authentication for Account Creation') }}
@endsection
@section('settings-content')
    <div class="max-w-sm w-full mx-auto bg-white dark:bg-slate-900 shadow-xl rounded-2xl px-6 py-8 space-y-6">
        <!-- Branding -->
        <div class="text-center space-y-4">
            <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-primary text-primary bg-opacity-30 mb-5 mx-auto" style="--tw-bg-opacity: 0.3;">
                <iconify-icon class="text-4xl" icon="lucide:shield-check"></iconify-icon>
            </div>
            <div class="title mb-2">
                <h4 class="text-xl font-medium dark:text-white capitalize">
                    {{ __('Enter OTP') }}
                </h4>
            </div>
            <p class="dark:text-slate-300">
                {{ __('We have sent a verification code via email') }}
            </p>
        </div>

        <!-- Verification Form -->
        <form method="post" class="mt-5" id="otpVerificationForm">
            @csrf
            <div class="input-area">
                <input type="text" name="verification_code" id="verification_code" class="form-control !text-lg" placeholder="Enter OTP" maxlength="4" pattern="[0-9]{4}">
            </div>
            <p class="dark:text-slate-300 my-5">
                {{ __("Don't received code ?") }}
                @if($isRestricted)
                    <span class="text-warning">{{ __('Too many resend attempts. Please wait') }} {{ $formattedTime ?: '2 hours' }} {{ __('before trying again.') }}</span>
                @else
                    <a href="javascript:;" id="resendOtpBtn" class="btn-link hover:underline">{{ __('Resend') }}</a>
                @endif
            </p>
            <div class="action-btns">
                <button type="submit" id="verifyBtn" class="otpSubmitBtn btn btn-dark inline-flex items-center justify-center mr-2">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                    {{ __('Verify OTP') }}
                </button>
                <a href="javascript:;" class="btn btn-danger inline-flex items-center justify-center" id="cancelOtpVerification">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>

        
    </div>

    <!-- Cancel OTP Confirmation Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
         id="confirmCancelModal"
         tabindex="-1"
         aria-labelledby="confirmCancelModal"
         aria-hidden="true"
    >
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body p-6 py-8 text-center space-y-5">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                        <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                    </div>
                    <div class="title">
                        <h4 class="text-xl font-medium dark:text-white capitalize">
                            {{ __('Are you sure?') }}
                        </h4>
                    </div>
                    <p class="dark:text-slate-300">
                        {{ __('You want to Cancel OTP verification') }}
                    </p>
                    <div class="action-btns">
                        <button type="button" class="confirmCancelBtn btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Confirm') }}
                        </button>
                        <a href="javascript:;" class="btn btn-danger inline-flex items-center justify-center" type="button"
                           data-bs-dismiss="modal"
                           aria-label="Close">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Auto-focus OTP input
    $('#verification_code').focus();
    
    // Handle OTP input (NO AUTO-SUBMIT)
    $('#verification_code').on('input', function() {
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
    $('#verification_code').on('paste', function(e) {
        e.preventDefault();
        const pastedText = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        const numericValue = pastedText.replace(/[^0-9]/g, '').substring(0, 4);
        $(this).val(numericValue);
        // Note: No auto-submit - user must click "Verify OTP" button
    });
    
    // Handle form submission
    $('#otpVerificationForm').on('submit', function(e) {
        e.preventDefault();
        
        const otp = $('#verification_code').val();
        if (!otp || otp.length !== 4) {
            tNotify('error', '{{ __("Please enter a valid 4-digit OTP") }}');
            return;
        }
        
        $('#verifyBtn').prop('disabled', true).html('<iconify-icon icon="lucide:loader-2" class="animate-spin ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Verifying...") }}');
        
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
                        // Reload page to show restriction status
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        // Clear OTP input on error (only if not restricted)
                        $('#verification_code').val('').focus();
                    }
                } else {
                    tNotify('error', '{{ __("An error occurred. Please try again.") }}');
                }
                
                $('#verifyBtn').prop('disabled', false).html('<iconify-icon icon="lucide:check" class="ltr:mr-2 rtl:ml-2"></iconify-icon>{{ __("Verify OTP") }}');
            }
        });
    });
    
    // Handle resend OTP
    $('#resendOtpBtn').on('click', function(e) {
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
                    $('#verification_code').val('').focus();
                    
                    // Reload page to update restriction status
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.message) {
                    tNotify('error', response.message);
                    
                    // Check if it's a restriction message
                    if (response.is_restricted || response.message.includes('restricted') || response.message.includes('Too many')) {
                        // Reload page to show restriction status
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                } else {
                    tNotify('error', '{{ __("Failed to resend OTP. Please try again.") }}');
                }
            },
            complete: function() {
                // Always reset the button state
                $('#resendOtpBtn').removeClass('disabled').html('{{ __("Resend") }}');
            }
        });
    });

    // Handle cancel OTP verification
    $('#cancelOtpVerification').on('click', function(e) {
        e.preventDefault();
        $('#confirmCancelModal').modal('show');
    });

    // Handle confirm cancel
    $('.confirmCancelBtn').on('click', function() {
        window.location.href = '{{ route("user.withdraw.account.create") }}';
    });
});
</script>
@endsection 