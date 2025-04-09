<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="otpModal"
     tabindex="-1"
     aria-labelledby="otpModal"
     aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body px-6 py-6 text-center">
                <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-primary text-primary bg-opacity-30 mb-5" style="--tw-bg-opacity: 0.3;">
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
                <form method="post" class="mt-5">
                    <div class="input-area">
                        <input type="text" name="otp" id="otpInput" class="form-control !text-lg" placeholder="Enter OTP">
                    </div>
                    <p class="dark:text-slate-300 my-5">
                        {{ __("Don't received code ?") }}
                        <a href="javascript:;" id="resendOtpBtn" class="btn-link hover:underline">{{ __('Resend') }}</a>
                    </p>
                    <div class="action-btns">
                        <button type="button" class="otpSubmitBtn btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __(' Verify OTP') }}
                        </button>
                        <a href="javascript:;" class="btn btn-danger inline-flex items-center justify-center" id="cancelOtpVerification">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
