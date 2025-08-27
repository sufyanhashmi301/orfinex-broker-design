@extends('frontend::layouts.auth')

@section('title')
    {{ __('Login') }}
@endsection
@section('content')
    <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
        <div>
            <div class="mb-5 sm:mb-8">
                <h1 class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                    {{ __('Sign In') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Enter your email and password to sign in!') }}
                </p>
            </div>
            @if ($errors->any())
                <x-alert type="error" class="mb-3">
                    @foreach($errors->all() as $error)
                        {{ __($error) }}<br>
                    @endforeach
                </x-alert>
            @endif
            <div>
                <!-- BEGIN: Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <x-forms.field
                        :fieldId="'email'"
                        :fieldLabel="'Email Or Username'"
                        :fieldPlaceholder="'Enter your email address or username'"
                        :fieldRequired="true"
                        :type="'email'"
                        :fieldName="'email'"
                        :fieldValue="old('email')"
                    />
                    <x-forms.password-field
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
                    <div class="flex items-center justify-between">
                        <x-forms.checkbox fieldId="checkboxLabelOne" fieldName="remember" :fieldRequired="false">
                            {{ __('Keep me signed in') }}
                        </x-forms.checkbox>

                        <div class="flex items-center space-x-2">
                            @if (Route::has('password.request'))
                                <x-text-link href="{{ route('password.request') }}" variant="text">
                                    {{ __('Forget Password') }}
                                </x-text-link>
                            @endif
                            <span class="text-gray-700 dark:text-gray-400">|</span>
                            <x-text-link href="{{ route('password.get') }}" variant="text">
                                {{ __('Get Password') }}
                            </x-text-link>
                        </div>
                    </div>

                    <div>
                        <x-forms.button type="submit" class="w-full" size="lg" variant="primary">
                            {{ __('Account Login') }}
                        </x-forms.button>
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
                            <span class="p-2 text-gray-400 bg-white dark:bg-gray-900 sm:px-5 sm:py-2">{{ __('Or') }}</span>
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
    
                <div class="text-center mt-5">
                    <p class="text-sm font-normal text-gray-700 dark:text-gray-400">
                        {{ __("Don't have an account?") }}
                        <x-text-link href="{{ route('register') }}" variant="text">
                            {{ __('Sign up now.') }}
                        </x-text-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @if ($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
