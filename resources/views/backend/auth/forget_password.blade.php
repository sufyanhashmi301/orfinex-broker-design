@extends('backend.auth.index')

@section('title')
    {{ __('Reset Password') }}
@endsection

@section('auth-content')
    <div class="max-w-sm w-full bg-white dark:bg-slate-900 shadow-xl rounded-2xl px-6 py-8 space-y-6">
        <!-- Branding -->
        <div class="text-center space-y-4">
            <a href="{{ route('home') }}" class="inline-block">
                @php
                    $siteTitle = setting('site_title', 'global') ?? 'Your Site';
                @endphp
                <img src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" class="h-[56px] mx-auto" alt="{{ $siteTitle }}">
            </a>
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-100 tracking-tight">
                {{ __('Reset Password') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-slate-400">
                We’ll send a reset link to your email address
            </p>
        </div>

        <!-- Reset Form -->
        <form action="{{ route('admin.forget.password.submit') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Email Input -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-600 dark:text-gray-300">
                    {{ __('Email Address') }}
                </label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="you@example.com"
                    required
                    class="w-full rounded-md px-4 py-2 text-sm border-0 ring-1 ring-slate-200 dark:ring-slate-700 bg-white dark:bg-slate-800 dark:text-slate-200"
                />
                @error('email')
                <p class="text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- CAPTCHA -->
            @if(!empty($cloudflareTurnstile) && !empty($siteKey))
                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
                <div class="cf-turnstile mb-3" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
            @elseif(!empty($googleReCaptcha) && isset($googleReCaptcha->data))
                @php
                    $captchaData = json_decode($googleReCaptcha->data, true);
                    $recaptchaKey = $captchaData['google_recaptcha_key'] ?? null;
                @endphp
                @if($recaptchaKey)
                    <div class="g-recaptcha mb-3" id="feedback-recaptcha" data-sitekey="{{ $recaptchaKey }}"></div>
            @endif
        @endif

        <!-- Submit -->
            <button type="submit" class="btn btn-dark w-full py-2 text-center">
                {{ __('Send Password Reset Link') }}
            </button>
        </form>

        <!-- Back to Login -->
        <div class="mt-4 text-center text-sm">
            <a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline dark:text-blue-400">
                {{ __('← Back to Login') }}
            </a>
        </div>

        <!-- Footer -->
        @if(!setting('is_whitelabel', 'global'))
            <div class="text-center border-t pt-6 mt-4 border-slate-200 dark:border-slate-700">
                <p class="text-sm text-gray-400">{{ __('Powered by') }}</p>
                <a href="https://brokeret.com/" target="_blank">
                    <img src="{{ asset('backend/images/brokeret_logo.png') }}" class="h-6 mt-2 mx-auto" alt="Brokeret Logo">
                </a>
            </div>
        @endif
    </div>
@endsection

@section('script')
    @if(!empty($googleReCaptcha) && isset($googleReCaptcha->data))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
