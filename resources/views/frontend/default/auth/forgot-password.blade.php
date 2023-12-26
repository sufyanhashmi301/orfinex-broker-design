@extends('frontend::layouts.auth')
@section('title')
    {{ __('Forgot password') }}
@endsection
@section('content')
    <!-- Login Section -->

    <div class="loginwrapper bg-cover bg-no-repeat bg-center" style="background-image: url({{ asset('frontend/images/all-img/page-bg.png') }});">
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
                            <img src="{{ asset(setting('site_logo','global')) }}" alt="" class="mb-10 white_logo">
                        </a>
                    </div>
                    <div class="text-center 2xl:mb-10 mb-5">
                        <h4 class="font-medium">{{ $data['title'] }}</h4>
                        <div class="text-slate-500 dark:text-slate-400 text-base">
                            {{ $data['bottom_text'] }}
                        </div>
                    </div>
                    <div class="font-normal text-base text-slate-500 dark:text-slate-400 text-center px-2 bg-slate-100 dark:bg-slate-600 rounded py-3 mb-4 mt-10">
                        {{ __('Enter your Email and instructions will be sent to you!') }}
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <strong>{{$error}}</strong>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>{{ session('status') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <!-- BEGIN: Login Form -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf
                        <div class="fromGroup">
                            <label class="block capitalize form-label" for="email">{{ __('Email') }}</label>
                            <div class="relative">
                                <input
                                    class="form-control py-2 h-[48px]"
                                    type="text"
                                    name="email"
                                    placeholder="Enter your email address"
                                    required
                                    value="{{ old('email') }}"
                                />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark block w-full text-center">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                    <div class=" relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                        <div class=" absolute inline-block bg-white dark:bg-slate-800 dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2
                                    px-4 min-w-max text-sm text-slate-500 dark:text-slate-400font-normal ">
                            {{ __('Or continue with') }}
                        </div>
                    </div>
                    <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-6 uppercase text-sm text-center">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('login') }}" class="text-slate-900 dark:text-white font-medium hover:underline">
                            {{ __('Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Section End -->
@endsection


