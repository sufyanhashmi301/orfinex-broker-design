@extends('frontend::layouts.auth')
@section('title')
    {{ __('Sign In') }}
@endsection
@section('description')
    {{ __('Sign in to access your account.') }}
@endsection
@section('content')
    <div class="">
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show mt-2 text-sm" role="alert">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <p class="flex-1 font-Inter">
                        @foreach($errors->all() as $error)
                            {{$error}}
                        @endforeach
                    </p>
                    <div class="flex-0 text-lg cursor-pointer">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
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
                    <input type="email" name="email" class="form-control py-2 h-[48px]" placeholder="Enter your email address or username" required>
                </div>
            </div>
            <div class="fromGroup">
                <label class="block capitalize form-label">{{ __('Password') }}</label>
                <div class="relative ">
                    <input type="password" name="password" id="password" class="form-control py-2 h-[48px]" placeholder="Enter your password" required>
                    <button type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password dark:text-slate-200" toggle="#password">
                        <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="formGroup">
                {{-- @if($googleReCaptcha)
                    <div class="g-recaptcha" id="feedback-recaptcha" data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}"></div>
                @endif --}}

                @if(config('services.turnstile.sitekey'))
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.sitekey') }}"></div>
                @endif
            </div>
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
            <div class="absolute inline-block bg-white dark:bg-slate-800 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 dark:text-slate-400font-normal">
                {{ __('Or') }}
            </div>
        </div>
        <div class="md:max-w-[345px] mt-6 mx-auto font-normal text-slate-500 text-center dark:text-slate-400mt-12 uppercase text-sm">
            {{ __('Don’t have an account?') }}
            <a href="{{ route('register') }}" class="text-slate-900 dark:text-white font-medium hover:underline">
                {{ __('Sign up') }}
            </a>
        </div>

        
        @include('frontend::auth.join_us')

    </div>
@endsection
@section('script')
    {{-- @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif --}}
    @if(config('services.turnstile.sitekey'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
@endsection
