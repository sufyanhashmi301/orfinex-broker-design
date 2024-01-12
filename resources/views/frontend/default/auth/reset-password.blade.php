@extends('frontend::layouts.auth')
@section('title')
    {{ __('Reset Password') }}
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
                        <h4 class="font-medium">👋 {{ __('Reset password') }}</h4>
                        <div class="text-slate-500 dark:text-slate-400 text-base">
                            {{  __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <strong>{{$error}}</strong>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>{{ session('status') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div class="fromGroup">
                            <label class="block capitalize form-label">{{ __('Email') }}</label>
                            <div class="relative">
                                <input
                                    class="form-control !text-lg"
                                    type="text"
                                    name="email"
                                    placeholder="Enter your email address"
                                    required
                                    value="{{ old('email',$request->email) }}"
                                />
                            </div>
                        </div>
                        <div class="fromGroup">
                            <label class="block capitalize form-label" for="email">{{ __('New Password') }}</label>
                            <div class="relative">
                                <input
                                    class="form-control !text-lg"
                                    type="password"
                                    name="password"
                                    required
                                />
                            </div>
                        </div>

                        <div class="fromGroup">
                            <label class="block capitalize form-label" for="email">{{ __('Confirm Password') }}</label>
                            <div class="relative">
                                <input
                                    class="form-control !text-lg"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark block w-full text-center">
                            {{ __('Reset Password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Section End -->
@endsection

