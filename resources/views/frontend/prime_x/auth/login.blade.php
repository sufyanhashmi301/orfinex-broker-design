@extends('frontend::layouts.auth')

@section('title')
    {{ __('Login') }}
@endsection
@section('content')

    <!-- Login Section -->
    <div class="h-screen md:flex">
        <div class="hidden w-1/2 overflow-hidden md:block p-3">
            <div class="w-full h-full flex items-center justify-around bg-cover bg-no-repeat bg-center rounded-lg" style="background-image:url('https://cdn.brokeret.com/crm-assets/login-image/c19.png')">
                <div class="mx-auto max-w-xs text-center">
                    <a href="{{ route('home')}}" class="">
                        <img src="{{ asset(setting('site_logo','global')) }}" class="h-[56px]" alt="{{ __('Logo') }}">
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center py-10 px-10 md:w-1/2">
            <div class="w-full max-w-lg">
                <div class="mobile-logo text-center mb-6 lg:hidden block">
                    <a href="{{ route('home')}}">
                        <img src="{{ asset(setting('site_logo','global')) }}" alt="{{ __('Logo') }}" class="h-[56px]">
                    </a>
                </div>
                <h2 class="text-2xl font-semibold text-gray-700">{{ __('Sign In') }}</h2>
                <div class="">
                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show mt-2 text-sm" role="alert">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <p class="flex-1 font-Inter">
                                    @foreach($errors->all() as $error)
                                        {{ __($error) }}
                                    @endforeach
                                </p>
                                <div class="flex-0 text-lg cursor-pointer">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}">
                                        <iconify-icon icon="line-md:close"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- BEGIN: Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div class="fromGroup">
                            <label class="block capitalize form-label">{{ __('Email Or Username') }}</label>
                            <div class="relative ">
                                <input type="email" name="email" class="form-control py-2 h-[48px]" placeholder="{{ __('Enter your email address or username') }}" required>
                            </div>
                        </div>
                        <div class="fromGroup">
                            <label class="block capitalize form-label">{{ __('Password') }}</label>
                            <div class="relative ">
                                <input type="password" name="password" id="password" class="form-control py-2 h-[48px]" placeholder="{{ __('Enter your password') }}" required>
                                <button type="button" class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center" data-toggle="#password">
                                    <iconify-icon class="text-lg" icon="heroicons:eye-slash"></iconify-icon>
                                </button>
                            </div>
                        </div>
                        @if($cloudflareTurnstile)
                            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
                            <div class="cf-turnstile" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
                        @else
                            @if($googleReCaptcha)
                                <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                                    data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                                </div>
                            @endif
                        @endif

                        <div class="flex justify-between">
                            <label class="flex items-center cursor-pointer">
                                <input class="hiddens mr-2" type="checkbox" name="remember" />
                                <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                                    {{ __('Keep me signed in') }}
                                </span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-slate-800 dark:text-slate-400 leading-6 font-medium">
                                    {{ __('Forget Password') }}
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary block w-full text-center">
                            {{ __('Account Login') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                    <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                        <div class="absolute inline-block bg-body dark:bg-body dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
                            {{ __('Or continue with') }}
                        </div>
                    </div>
                    <div class="max-w-[242px] mx-auto mt-8 w-full">
                        <!-- BEGIN: Social Log in Area -->
                        <ul class="flex justify-center gap-2">
                            @php
                                $socialLinks = App\Models\Social::activePlatforms();
                            @endphp
                            @foreach ($socialLinks as $socialLink)
                                <li>
                                    <a href="{{ route('social.redirect', $socialLink->driver) }}" class="inline-flex h-10 w-10 flex-col items-center justify-center">
                                        <img src="https://cdn.brokeret.com/crm-assets/admin/social/{{ strtolower($socialLink->title) }}.webp" class="w-full" alt="{{ ucfirst($socialLink->title) }}">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <!-- END: Social Log In Area -->
                    </div>
                    <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 mt-12 uppercase text-sm text-center">
                        {{ __("Don’t have an account? ") }}
                        <a href="{{route('register')}}" class="text-slate-900 dark:text-white font-medium uppercase hover:underline">
                            {{ __('Sign up now.') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Section End -->
@endsection
@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
