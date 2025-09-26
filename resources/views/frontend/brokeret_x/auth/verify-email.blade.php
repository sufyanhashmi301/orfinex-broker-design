@extends('frontend::layouts.auth')
@section('title')
    {{ __('Verify Email') }}
@endsection
@section('content')
    <div class="mb-5 sm:mb-8">
        <h1 class="mb-2 text-title-sm font-semibold text-gray-800 dark:text-white/90">
            {{ __('Welcome to :site_title', ['site_title' => setting('site_title', 'common_settings')]) }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('To start using your account, we need to verify your email address. Please check your inbox for the verification email we just sent.') }}
        </p>
    </div>
    @if (session('status') == 'invalid-code')
        <x-frontend::alert type="error" class="mb-3">
            {{ __('kindly provide a valid 4 digits code! Maybe it\'s invalid or expired.') }}
        </x-frontend::alert>
    @endif
    
    @if (session('status') == 'verification-link-sent')
        <x-frontend::alert type="success" class="mb-3">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </x-frontend::alert>
    @endif

    <form method="POST" action="{{ route('verification.verify.code') }}" class="space-y-4">
        @csrf

        <x-frontend::forms.label for="verification_code" fieldLabel="{{ __('Code') }}" fieldRequired />
        <div x-data="otpInput(4)" x-init="$nextTick(() => init())" class="flex gap-2">
            <template x-for="(digit, index) in digits" :key="index">
                <input
                    type="text"
                    maxlength="1"
                    class="otp-input w-14 h-14 text-center border rounded dark:bg-dark-900 shadow-theme-xs
                        focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800
                        border-gray-300 bg-transparent text-gray-800 focus:ring-3 focus:outline-hidden
                        dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    @input="onInput($event, index)"
                    @keydown="onKeydown($event, index)"
                    @paste="onPaste($event)">
            </template>

            <input type="hidden" name="verification_code" :value="otp">
        </div>

        <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary">
            {{ __('Verify Code') }}
        </x-frontend::forms.button>
    </form>

    <div class="relative py-3 sm:py-5">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200 dark:border-gray-800"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="bg-white p-2 text-gray-400 sm:px-5 sm:py-2 dark:bg-gray-900">
                {{ __('Didn\'t receive the email?') }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <x-frontend::forms.button type="submit" class="w-full" size="md" variant="secondary">
            {{ __('Resend Verification Email') }}
        </x-frontend::forms.button>
    </form>

    <div class="text-center mt-5 space-y-1">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-400">{{ __("Need Help?") }}</p>
        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
            {{ __("If you're having trouble or need assistance, contact our support team at ") }}
            <x-frontend::text-link href="mailto:{{ setting('support_email', 'common_settings') }}" variant="text">
                {{ setting('support_email', 'common_settings') }}
            </x-frontend::text-link>
        </p>
    </div>
    
@endsection
@section('script')
    <script>
        function otpInput(length) {
            return {
                length,
                digits: Array(length).fill(""),
                inputs: [],

                init() {
                    this.inputs = this.$el.querySelectorAll('.otp-input');
                },

                get otp() {
                    return this.digits.join('');
                },

                onInput(e, i) {
                    let v = e.target.value.replace(/\D/g, '').slice(-1);
                    this.digits[i] = v;
                    e.target.value = v;

                    if (v && i < this.length - 1) {
                        this.inputs[i + 1].focus();
                    }
                },

                onKeydown(e, i) {
                    if (e.key === 'Backspace' && !this.digits[i] && i > 0) {
                        this.inputs[i - 1].focus();
                    }
                },

                onPaste(e) {
                    e.preventDefault();
                    const data = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                    if (!data) return;

                    [...data].forEach((ch, idx) => {
                        if (idx < this.length) {
                            this.digits[idx] = ch;
                            this.inputs[idx].value = ch;
                        }
                    });

                    this.inputs[Math.min(data.length - 1, this.length - 1)].focus();
                }
            }
        }
    </script>
@endsection