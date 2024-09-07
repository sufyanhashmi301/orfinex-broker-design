@extends('frontend::layouts.auth')
@section('title')
    {{ __('Verify Email') }}
@endsection
@section('content')
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
                        <h4 class="font-medium">👋 {{ __('Welcome Back!') }}</h4>
                        <div class="text-slate-500 dark:text-slate-400 text-base">
                            {{ __('verify your email address by clicking on the link we just emailed to you') }}
                        </div>
                    </div>
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif
                    <div class="site-auth-form">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <button type="submit" class="btn btn-primary block w-full text-center mb-3">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('logout') }}">
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



