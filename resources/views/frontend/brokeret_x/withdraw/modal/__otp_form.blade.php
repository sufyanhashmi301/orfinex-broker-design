<div class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999"
    id="otpModal"
    aria-labelledby="otpModal"
    :class="{ 'hidden': !isOtpModalOpen }"
    @keydown.escape="showCancelModal">
    <!-- Overlay -->
    <div @click="showCancelModal" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[20px]"></div>
    <div @click.stop class="relative w-full max-w-xl rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
        <div class="text-center">
            <div class="relative flex items-center justify-center z-1 mb-7">
                <svg class="fill-brand-50 dark:fill-brand-500/15" width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M34.364 6.85053C38.6205 -2.28351 51.3795 -2.28351 55.636 6.85053C58.0129 11.951 63.5594 14.6722 68.9556 13.3853C78.6192 11.0807 86.5743 21.2433 82.2185 30.3287C79.7862 35.402 81.1561 41.5165 85.5082 45.0122C93.3019 51.2725 90.4628 63.9451 80.7747 66.1403C75.3648 67.3661 71.5265 72.2695 71.5572 77.9156C71.6123 88.0265 60.1169 93.6664 52.3918 87.3184C48.0781 83.7737 41.9219 83.7737 37.6082 87.3184C29.8831 93.6664 18.3877 88.0266 18.4428 77.9156C18.4735 72.2695 14.6352 67.3661 9.22531 66.1403C-0.462787 63.9451 -3.30193 51.2725 4.49185 45.0122C8.84391 41.5165 10.2138 35.402 7.78151 30.3287C3.42572 21.2433 11.3808 11.0807 21.0444 13.3853C26.4406 14.6722 31.9871 11.951 34.364 6.85053Z" fill="" fill-opacity=""></path>
                </svg>

                <span class="absolute -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check text-brand-500 dark:text-brand-500/15">
                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </span>
            </div>

            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90 sm:text-title-sm">
                {{ __('Enter OTP') }}
            </h4>
            <p class="text-base text-gray-500 dark:text-gray-400">
                {{ __('We have sent a verification code via email') }}
            </p>
            <form method="post" class="mt-5" @submit.prevent="submitOtp">
                <div class="input-area">
                    <input type="text" name="otp" id="otpInput" x-model="otpInput" 
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Enter OTP">
                </div>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ __("Don't received code ?") }}
                    <a href="javascript:;" @click="resendOtp" class="text-brand-500 hover:text-brand-600 dark:text-brand-400" :disabled="isResendingOtp">
                        <span x-show="!isResendingOtp">{{ __('Resend') }}</span>
                        <span x-show="isResendingOtp">{{ __('Resending OTP...') }}</span>
                    </a>
                </p>
                <div class="flex items-center justify-center w-full gap-3 mt-7">
                    <x-frontend::forms.button type="submit" x-ref="submitBtn" variant="primary" size="md" icon="check" icon-position="left">
                        {{ __('Verify OTP') }}
                    </x-frontend::forms.button>
                    <x-frontend::forms.button type="button" variant="secondary" size="md" icon="x" icon-position="left" @click="showCancelModal">
                        {{ __('Cancel') }}
                    </x-frontend::forms.button>
                </div>
            </form>
        </div>
    </div>
</div>