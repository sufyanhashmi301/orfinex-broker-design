@extends('frontend::layouts.auth')

@section('title')
    {{ __('Sign In') }}
@endsection
@section('content')
    <div>
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
            {{ __('Welcome to :site_title', ['site_title' => setting('site_title')]) }}
        </h1>

        <!-- auth tabs -->
        <div class="my-5 sm:my-8">
            @include('frontend::auth.include.__tabs')
        </div>

        @if ($errors->any())
            <x-frontend::alert type="error" class="mb-3">
                @foreach($errors->all() as $error)
                    {{ __($error) }}<br>
                @endforeach
            </x-frontend::alert>
        @endif
        <div>
            <!-- BEGIN: Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <x-frontend::forms.field
                    :fieldId="'email'"
                    :fieldLabel="'Email Or Username'"
                    :fieldPlaceholder="'Enter your email address or username'"
                    :fieldRequired="true"
                    :type="'email'"
                    :fieldName="'email'"
                    :fieldValue="old('email')"
                />
                <x-frontend::forms.password-field
                    :fieldId="'password'"
                    :fieldLabel="'Password'"
                    :fieldPlaceholder="'Enter your password'"
                    :fieldRequired="true"
                    :fieldName="'password'"
                    :fieldValue="old('password')"
                />
                @if ($cloudflareTurnstile)
                    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
                    <div class="cf-turnstile" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
                @else
                    @if ($googleReCaptcha)
                        <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                            data-sitekey="{{ json_decode($googleReCaptcha->data, true)['google_recaptcha_key'] }}">
                        </div>
                    @endif
                @endif

                <!-- Checkbox -->
                <div class="flex flex-wrap items-center justify-between gap-2.5">
                    <x-frontend::forms.checkbox fieldId="checkboxLabelOne" fieldName="remember" :fieldRequired="false">
                        {{ __('Keep me signed in') }}
                    </x-frontend::forms.checkbox>

                    <div class="flex items-center space-x-2">
                        @if (Route::has('password.request'))
                            <x-frontend::text-link href="{{ route('password.request') }}" variant="text">
                                {{ __('Forget Password') }}
                            </x-frontend::text-link>
                        @endif
                        <span class="text-gray-700 dark:text-gray-400">|</span>
                        <x-frontend::text-link href="{{ route('password.get') }}" variant="text">
                            {{ __('Get Password') }}
                        </x-frontend::text-link>
                    </div>
                </div>

                <div>
                    <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary">
                        {{ __('Account Login') }}
                    </x-frontend::forms.button>
                </div>
            </form>
            <!-- END: Login Form -->

            @php
                $socialLogins = App\Models\Social::activePlatforms();
            @endphp
            @if ($socialLogins->isNotEmpty())
                <div class="relative py-3 sm:py-5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-800"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white p-2 text-gray-400 sm:px-5 sm:py-2 dark:bg-gray-900">{{ __('Or sign in with') }}</span>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-3 sm:gap-5">
                    @foreach ($socialLogins as $socialLogin)
                        <a href="{{ route('social.redirect', $socialLogin->driver) }}" class="inline-flex h-10 w-10 flex-col items-center justify-center">
                            <img src="https://cdn.brokeret.com/crm-assets/admin/social/{{ strtolower($socialLogin->title) }}.webp" class="w-full" alt="{{ ucfirst($socialLogin->title) }}">
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    @if ($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
