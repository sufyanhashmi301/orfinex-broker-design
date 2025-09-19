@extends('backend.auth.index')

@section('title')
    {{ __('Login') }}
@endsection

@section('auth-content')
    <div class="max-w-sm w-full bg-white dark:bg-slate-900 shadow-xl rounded-2xl px-6 py-8 space-y-6">
        <!-- Branding -->
        <div class="text-center space-y-4">
            @php
                $showAdminAuthLogo = setting('admin_auth_logo_status', 'admin_auth_logo') && setting('admin_auth_logo_image', 'admin_auth_logo');
            @endphp
            @if($showAdminAuthLogo)
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset(setting('admin_auth_logo_image', 'admin_auth_logo')) }}" class="h-[56px] mx-auto" alt="Admin Auth Logo">
                </a>
            @endif
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-100 tracking-tight">
                {{ __('Admin Backoffice Login') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-slate-400">
                Secure access panel for authorized administrators
            </p>
        </div>

        <!-- Login Form -->
        <form action="{{ route('admin.login') }}" method="post" class="space-y-5">
        @csrf
        <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-600 dark:text-gray-300">
                    {{ __('Email Address') }}
                </label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="admin@example.com"
                    required
                    class="w-full rounded-md px-4 py-2 text-sm border-0 ring-1 ring-slate-200 dark:ring-slate-700 bg-white dark:bg-slate-800 dark:text-slate-200"
                />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-600 dark:text-gray-300">
                    {{ __('Password') }}
                </label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="••••••••"
                        required
                        class="w-full rounded-md px-4 py-2 text-sm border-0 ring-1 ring-slate-200 dark:ring-slate-700 bg-white dark:bg-slate-800 dark:text-slate-200"
                    />
                    <button type="button" class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center" data-toggle="#password">
                        <iconify-icon class="text-lg" icon="heroicons:eye"></iconify-icon>
                    </button>
                </div>
            </div>

            <!-- CAPTCHA -->
            @if($cloudflareTurnstile)
                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
                <div class="cf-turnstile mb-3" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
            @elseif($googleReCaptcha)
                <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                     data-sitekey="{{ json_decode($googleReCaptcha->data, true)['google_recaptcha_key'] }}">
                </div>
        @endif

        <!-- Actions -->
            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center text-gray-600 dark:text-gray-400">
                    <input type="checkbox" name="remember" class="mr-2">
                    {{ __('Keep me signed in') }}
                </label>
                <a href="{{ route('admin.forget.password.now') }}" class="text-blue-600 hover:underline dark:text-blue-400">
                    {{ __('Forgot Password?') }}
                </a>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-dark w-full py-2 text-center">
                {{ __('Access Now') }}
            </button>
        </form>

        <!-- Footer -->
        <!-- Footer -->
@if(!setting('is_whitelabel', 'global'))
    <div class="text-center border-t pt-6 mt-4 border-slate-200 dark:border-slate-700">
         @if(setting('provider_logo_status', 'provider_logo'))
        <p class="text-sm text-gray-400">
            {{ __('Powered by') }}
        </p>
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset(setting('provider_logo_image', 'provider_logo') ?: 'backend/images/brokeret_logo.png') }}" 
                     class="h-[56px] mx-auto" 
                     alt="{{ setting('site_title', 'global') }}">
            </a>
        @endif
    </div>
@endif
    </div>
@endsection

@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    <script>
        $(document).ready(function() {
            $('.toggle-password').click(function () {
                const input = $($(this).data('toggle'));
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);

                const icon = type === 'password' ? 'heroicons:eye' : 'heroicons:eye-slash';
                $(this).find('iconify-icon').attr('icon', icon);
            });
        });
    </script>
@endsection
