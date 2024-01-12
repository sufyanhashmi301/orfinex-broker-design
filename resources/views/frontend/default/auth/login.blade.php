@extends('frontend::layouts.auth')

@section('title')
    {{ __('Login') }}
@endsection
@section('content')

    <!-- Login Section -->
    <div class="loginwrapper bg-cover bg-no-repeat bg-center" style="background-image: url(https://cloud.orfinex.com/crm/orfinexlogin.png);">
        <div class="lg-inner-column">
            <div class="left-columns lg:w-1/2 lg:block hidden">
                <div class="logo-box-3">
                    <a href="{{ route('home')}}" class="">
                        <img src="{{ asset(setting('site_logo','global')) }}" alt="">
                    </a>
                </div>
            </div>
            <div class="lg:w-1/2 w-full flex flex-col items-center justify-center">
                <div class="auth-box-3">
                    <div class="mobile-logo text-center mb-6 lg:hidden block">
                        <a href="{{ route('home')}}">
                            <img src="{{ asset(setting('site_logo','global')) }}" alt="" class="mb-10 dark_logo">
                        </a>
                    </div>
                    <div class="text-center 2xl:mb-10 mb-5">
                        <h4 class="font-medium">{{ $data['title'] }}</h4>
                        <div class="text-slate-500 dark:text-slate-400 text-base">
                            {{ $data['bottom_text'] }}
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <strong>{{$error}}</strong>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                <input type="password" name="password" class="form-control py-2 h-[48px]" placeholder="Enter your password" required>
                            </div>
                        </div>
                        @if($googleReCaptcha)
                            <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                                 data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                            </div>
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
                        <button type="submit" class="btn btn-dark block w-full text-center">
                            {{ __('Account Login') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                    <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-6 uppercase text-sm text-center">
                        {{ __("Don't have an account?") }}
                        <a href="{{route('register')}}" class="text-slate-900 dark:text-white font-medium hover:underline">
                            {{ __('Signup for free') }}
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
