@extends('frontend::layouts.auth')
@section('title')
    {{ __('Forgot password') }}
@endsection
@section('content')

    <!-- Login Section -->
    <div class="h-screen lg:flex">
        <div class="hidden w-1/2 overflow-hidden lg:block p-3">
            <div class="w-full h-full flex items-center justify-around bg-cover bg-no-repeat bg-center rounded-lg" style="background-image:url('https://cdn.brokeret.com/crm-assets/login-image/c19.png')">
                <div class="mx-auto max-w-xs text-center">
                    <a href="{{ route('home')}}" class="">
                        <img src="{{ asset(setting('site_logo','global')) }}" class="h-[56px]" alt="{{ __('Logo') }}">
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center py-10 px-10 lg:w-1/2">
            <div class="w-full max-w-lg mx-auto lg:mx-0">
                <div class="mobile-logo text-center mb-6 lg:hidden block">
                    <a href="{{ route('home')}}">
                        <img src="{{ asset(setting('site_logo','global')) }}" alt="{{ __('Logo') }}" class="h-[56px]">
                    </a>
                </div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-1">{{ __('Password Reset') }}</h2>
                <div class="text-slate-500 dark:text-slate-400 text-sm">
                    {{ __('Enter your Email and instructions will be sent to you!') }}
                </div>
                <div class="mt-5">
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
                    @if(session('status'))
                        <div class="alert alert-warning alert-dismissible fade show mt-2 text-sm" role="alert">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <p class="flex-1 font-Inter">
                                    {{ __(session('status')) }}
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
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf
                        <div class="fromGroup">
                            <label class="block capitalize form-label" for="email">{{ __('Email') }}</label>
                            <div class="relative">
                                <input
                                    class="form-control py-2 h-[48px]"
                                    type="text"
                                    name="email"
                                    placeholder="{{ __('Enter your email address') }}"
                                    required
                                    value="{{ old('email') }}"
                                />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary block w-full text-center">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                    <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 mt-12 uppercase text-sm text-center">
                        {{ __("Or back to ") }}
                        <a href="{{ route('login') }}" class="text-slate-900 dark:text-white font-medium uppercase hover:underline">
                            {{ __('Login.') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Section End -->
@endsection
