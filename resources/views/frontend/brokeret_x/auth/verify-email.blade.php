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

        <x-frontend::forms.field
            type="text"
            fieldId="verification_code"
            fieldLabel="{{ __('Code') }}"
            fieldName="verification_code"
            fieldPlaceholder="{{ __('Enter 4 digits code!') }}"
        />
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
