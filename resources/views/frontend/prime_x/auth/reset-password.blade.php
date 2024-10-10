@extends('frontend::layouts.auth')
@section('title')
    {{ __('Reset Password') }}
@endsection
@section('content')

    <!-- Login Section -->
    <div class="h-screen md:flex">
        <div class="hidden w-1/2 overflow-hidden md:block p-3">
            <div class="w-full h-full flex items-center justify-around bg-cover bg-no-repeat bg-center rounded-lg" style="background-image:url({{ asset('frontend/images/primex_login_bg.png') }})">
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
                <div class="text-center 2xl:mb-10 mb-5">
                    <h4 class="font-medium">👋 {{ __('Reset Password') }}</h4>
                    <div class="text-slate-500 dark:text-slate-400 text-base">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        @foreach($errors->all() as $error)
                            <strong>{{ $error }}</strong>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="{{ __('Close') }}"></button>
                    </div>
                @endif

                @if(session('status'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ session('status') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="{{ __('Close') }}"></button>
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
                                placeholder="{{ __('Enter your email address') }}"
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
                                placeholder="{{ __('Enter your new password') }}"
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
                                placeholder="{{ __('Confirm your new password') }}"
                                required
                            />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary block w-full text-center">
                        {{ __('Reset Password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Login Section End -->
@endsection
