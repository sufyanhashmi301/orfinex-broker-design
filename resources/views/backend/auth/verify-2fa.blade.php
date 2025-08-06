@extends('backend.auth.index')

@section('title')
    {{ __('Two-Factor Authentication') }}
@endsection

@section('auth-content')
    <div class="max-w-sm w-full bg-white dark:bg-slate-900 shadow-xl rounded-2xl px-6 py-8 space-y-6">
        <!-- Branding -->
        <div class="text-center space-y-4">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" class="h-[56px] mx-auto" alt="{{ asset(setting('site_title','global')) }}">
            </a>
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-100 tracking-tight">
                {{ __('Two-Factor Authentication') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-slate-400">
                {{ __('To complete your login, please enter the verification code sent to your email.') }}
            </p>
        </div>

        <!-- Verification Form -->
        <div class="text-center space-y-3 mb-5">
            @if (session('status') == 'invalid-code')
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ __('Please provide a valid 4-digit code! The code may be invalid or expired.') }}
                </div>
            @endif

            @if (session('status') == 'restricted')
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ __('Too many resend attempts. Please wait before trying again.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label for="verification_code" class="block text-sm font-medium text-gray-600 dark:text-gray-300">
                        {{ __('Verification Code') }}
                    </label>
                    <input
                        type="text"
                        name="verification_code"
                        id="verification_code"
                        class="w-full rounded-md px-4 py-2 text-sm border-0 ring-1 ring-slate-200 dark:ring-slate-700 bg-white dark:bg-slate-800 dark:text-slate-200"
                        placeholder="{{ __('Enter 4-digit code') }}"
                        required
                        maxlength="4"
                        pattern="[0-9]{4}"
                    />
                </div>
                <button type="submit" class="btn btn-dark w-full py-2 text-center">
                    {{ __('Verify Code') }}
                </button>
            </form>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ __('A new verification code has been sent to your email address.') }}
            </div>
        @endif

        <!-- Resend Code Section -->
        <div class="text-center space-y-3 mb-5">
            <p class="dark:text-white text-sm font-semibold">
                {{ __("Didn't receive the code?") }}
            </p>
            <p class="text-slate-500 dark:text-slate-400 text-sm">
                {{ __("Click the button below to resend the verification code.") }}
            </p>
            
            @if($isRestricted)
                @php
                    $hours = floor($remainingTime / 3600);
                    $minutes = floor(($remainingTime % 3600) / 60);
                @endphp
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ __('Too many resend attempts. Please wait') }} {{ $hours }}h {{ $minutes }}m {{ __('before trying again.') }}
                </div>
                <button type="button" class="btn btn-secondary block w-full text-center mb-3" disabled>
                    {{ __('Resend Verification Code') }} ({{ __('Restricted') }})
                </button>
            @else
                <form method="POST" action="{{ route('admin.2fa.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary block w-full text-center mb-3">
                        {{ __('Resend Verification Code') }}
                        @if($resendAttempts > 0)
                            ({{ __('Attempt') }} {{ $resendAttempts }}/3)
                        @endif
                    </button>
                </form>
            @endif
        </div>

        <!-- Help Section -->
        <div class="text-center space-y-3">
            <p class="dark:text-white text-sm font-semibold">
                {{ __("Need Help?") }}
            </p>
            <p class="text-slate-500 dark:text-slate-400 text-sm">
                {{ __("If you're having trouble or need assistance, contact our support team at ") }}
                <a href="mailto:{{ setting('support_email', 'common_settings') }}" class="underline">
                    {{ setting('support_email', 'common_settings') }}
                </a>
            </p>
        </div>

        <!-- Logout Option -->
        <div class="flex justify-center font-normal text-slate-500 dark:text-slate-400 mt-12 uppercase text-sm">
            {{ __("Not ready yet? ") }}
            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-slate-900 dark:text-white font-medium uppercase hover:underline ml-2">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
@endsection 