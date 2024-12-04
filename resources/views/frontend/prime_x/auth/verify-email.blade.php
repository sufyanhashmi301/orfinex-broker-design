@extends('frontend::layouts.auth')
@section('title')
    {{ __('Verify Email') }}
@endsection
@section('content')
    <div class="h-screen flex items-center justify-center md:px-0 px-3">
        <div class="w-full max-w-lg">
            <div class="text-center mb-6">
                <a href="{{ route('home')}}" class="inline-flex">
                    <img src="{{ asset(setting('site_logo','global')) }}" class="h-[56px]" alt="{{ __('Logo') }}">
                </a>
            </div>
            <div class="card border">
                <div class="card-body p-6">
                    <div class="text-center mb-5">
                        <h4 class="card-title mb-5">👋 {{ __('Welcome to '). setting('site_title', 'common_setting') }}</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">
                            {{ __('To start using your account, we need to verify your email address. Please check your inbox for the verification email we just sent.') }}
                        </p>
                    </div>
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif
                    <div class="text-center space-y-3 mb-5">
                        <p class="dark:white text-sm font-semibold">
                            {{ __("Didn't receive the email?") }}
                        </p>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">
                            {{ __("Click the button below to resend the verification link.") }}
                        </p>
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary block w-full text-center mb-3">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>
                    </div>
                    <div class="text-center space-y-3">
                        <p class="dark:white text-sm font-semibold">
                            {{ __("Need Help?") }}
                        </p>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">
                            {{ __("If you're having trouble or need assistance, contact our support team at ") }}
                            <a href="mailto:{{ setting('support_email', 'common_setting') }}" class="underline">
                                {{ setting('support_email', 'common_setting') }}
                            </a>
                        </p>
                        <form method="POST" action="{{ route('logout') }}" class="mt-6">
                            @csrf
                            <button type="submit" class="btn btn-danger block w-full text-center">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
