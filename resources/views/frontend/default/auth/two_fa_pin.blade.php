@extends('frontend::layouts.auth')
@section('title')
    {{ __('2FA Security') }}
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
                            {{ __('Sign in to continue with') }} {{ setting('site_title','global') }} {{ __('User Panel') }}
                        </div>
                    </div>
                    @if ($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                @foreach($errors->all() as $error)
                                    <strong>You Entered {{$error}}</strong>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="site-auth-form">
                            <form method="POST" action="{{ route('user.setting.2fa.verify') }}">
                                @csrf

                                <div class="single-field">
                                    <p>{{ __('Please enter the') }}
                                        <strong>{{ __('OTP') }}</strong> {{ __('generated on your Authenticator App.') }}
                                        <br> {{ __('Ensure you submit the current one because it refreshes every 30 seconds.') }}
                                    </p>

                                    <label class="box-label" for="password">{{ __('One Time Password') }}</label>
                                    <div class="password">
                                        <input
                                            class="form-control"
                                            type="password"
                                            id="one_time_password"
                                            name="one_time_password"
                                            placeholder="Enter your Pin"
                                            required
                                        />
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-dark block w-full text-center">
                                    {{ __('Authenticate Now') }}
                                </button>
                            </form>

                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection


