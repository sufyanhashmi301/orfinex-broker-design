@extends('frontend::layouts.auth')

@section('title')
    {{ __('Login') }}
@endsection
@section('content')

    <!-- Login Section -->
    <div class="loginwrapper flex-col justify-center items-center bg-cover bg-no-repeat bg-center p-8 lg:py-20 lg:px-0" style="background-image: url(https://cloud.orfinex.com/crm/orfinexlogin.png);">
        <div class="md:hidden text-center mb-3">
            <a href="{{ route('home')}}" class="">
                <img src="{{ asset(setting('site_logo','global')) }}" class="h-[56px]" alt="">
            </a>
        </div>
        <div class="flex bg-white border rounded-lg shadow-lg overflow-hidden mx-auto w-full max-w-5xl">
            <div class="hidden md:flex items-center justify-center md:w-1/2 bg-cover p-8 lg:px-16" style="background-image:url({{ asset('frontend/images/login-left.png') }})">
                <div class="2xl:mb-10 text-center space-y-3">
                    <a href="{{ route('home')}}" class="inline-flex items-center justify-center">
                        <img src="{{ asset(setting('site_logo','global')) }}" class="h-[56px]" alt="">
                    </a>
                </div>
            </div>
            <div class="w-full p-8 md:w-1/2 lg:px-16">
                <h2 class="text-2xl font-semibold text-gray-700">Sign In</h2>
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
                        <div class="flex justify-center">
                            @if (Route::has('password.request.form'))
                                <a href="{{ route('password.request.form') }}" class="text-sm text-slate-800 dark:text-slate-400 leading-6 font-medium text-center">
                                    {{ __('Get Password') }}
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-dark block w-full text-center">
                            {{ __('Account Login') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                    <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                        <div class="absolute inline-block bg-white dark:bg-slate-800 dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
                            Don't have an account?
                        </div>
                    </div>
                    <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-6 uppercase text-sm text-center">
                        <a href="{{route('register')}}" class="btn btn-light inline-flex items-center justify-center w-full">
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
