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
                        <h4 class="card-title mb-5">👋 {{ __('Welcome to '). setting('site_title', 'common_settings') }}</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">
                            {{ __('To start using your account, we need to verify your email address. Please check your inbox for the verification email we just sent.') }}
                        </p>
                    </div>
                    <div class="text-center space-y-3 mb-5">
                    @if (session('status') == 'invalid-code')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ __('kindly provide a valid 4 digits code! Maybe it\'s invalid or expired.') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('verification.verify.code') }}" class="space-y-4">
                        @csrf
                        <div class="fromGroup">
                            <label class="block capitalize form-label">{{ __('Code') }}</label>
                            <div class="relative ">
                                <input type="text" name="verification_code" class="form-control py-2 h-[48px]" placeholder="{{ __('Enter 4 digits code!') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary block w-full text-center">
                            {{ __('Verify Code') }}
                        </button>
                    </form>
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
                            {{ __("Click the button below to resend the verification code.") }}
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
                            <a href="mailto:{{ setting('support_email', 'common_settings') }}" class="underline">
                                {{ setting('support_email', 'common_settings') }}
                            </a>
                        </p>
                        @php
                            $socialLinks = social_links();
                        @endphp
                        @if($socialLinks->isNotEmpty())
                        <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                            <div class="absolute inline-block bg-white dark:bg-slate-800 dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
                                {{ __('Connect With Us') }}
                            </div>
                        </div>
                        <div class="mt-8 w-full">
                            <ul class="flex items-center justify-center mt-5 gap-2">
                                @foreach($socialLinks as $socialLink)
                                    <li>
                                        <a href="{{ $socialLink->link }}" target="_blank">
                                            @switch($socialLink->slug)
                                                @case('facebook_link')
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/facebook.webp" class="h-6" alt="Facebook">
                                                @break
                                                @case('twitter_link')
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/x.webp" class="h-6" alt="Twitter">
                                                @break
                                                @case('instagram_link')
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/instagram.webp" class="h-6" alt="Instagram">
                                                @break
                                                @case('linkedin_link')
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/linkedin.webp" class="h-6" alt="LinkedIn">
                                                @break
                                                @case('skype_link')
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/skype.webp" class="h-6" alt="Skype">
                                                @break
                                                @case('telegram_link')
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/telegram.webp" class="h-6" alt="Telegram">
                                                @break
                                                @case('whatsapp_link')
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/whatsapp.webp" class="h-6" alt="Whatsapp">
                                                @break
                                                @case('discord_link')
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/discord.webp" class="h-6" alt="Discord">
                                                @break
                                                @default
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/facebook.webp" class="h-6" alt="Social Icon">
                                            @endswitch
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-center font-normal text-slate-500 dark:text-slate-400 mt-10 uppercase text-sm">
                {{ __('Not ready yet?') }}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-900 dark:text-white font-medium uppercase hover:underline ml-2">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
