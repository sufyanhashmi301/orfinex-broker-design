@extends('frontend::layouts.auth')
@section('title')
    {{ __('2FA Security') }}
@endsection
@section('content')
    <div class="mb-5 sm:mb-8">
        <h1 class="mb-2 text-title-sm font-semibold text-gray-800 dark:text-white/90">
            {{ __('Welcome to :site_title', ['site_title' => setting('site_title')]) }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Sign in to continue with') }} {{ setting('site_title','global') }} {{ __('User Panel') }}
        </p>
    </div>

    @if ($errors->any())
        <x-frontend::alert type="error" class="mb-3">
            @foreach($errors->all() as $error)
                {{ __('You entered') }} {{ $error }}<br>
            @endforeach
        </x-frontend::alert>
    @endif
    
    <div class="site-auth-form">
        <form method="POST" action="{{ route('user.setting.2fa.verify') }}" class="space-y-5">
            @csrf
        
            <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                {{ __('Please enter the') }}
                <strong>{{ __('OTP') }}</strong> {{ __('generated on your Authenticator App.') }}
                <br> {{ __('Ensure you submit the current one because it refreshes every 30 seconds.') }}
            </p>

            <x-frontend::forms.label for="one_time_password" fieldLabel="{{ __('One Time Password') }}" fieldRequired />
            <div x-data="otpInput(6)" x-init="$nextTick(() => init())" class="flex gap-2">
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

                <input type="hidden" name="one_time_password" :value="otp">
            </div>

            <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary">
                {{ __('Authenticate Now') }}
            </x-frontend::forms.button>
        </form>
        <div class="mt-5 flex items-end gap-1">
            <p class="text-center text-sm font-normal text-gray-700 sm:text-start dark:text-gray-400">
                {{ __('Didn’t get the code?') }}
            </p>
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <x-frontend::text-link href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();" variant="text">
                    {{ __('Sign Out.') }}
                </x-frontend::text-link>
            </form>
        </div>
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
