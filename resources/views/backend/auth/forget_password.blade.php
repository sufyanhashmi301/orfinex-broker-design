@extends('backend.auth.index')
@section('title')
    {{ __('Reset Password') }}
@endsection
@section('auth-content')
    <div class="md:hidden text-center mb-3">
        <a href="{{ route('home')}}" class="inline-block">
            @php
                $logoSrc = setting('site_logo','global')
                    ? asset(setting('site_logo','global'))
                    : asset('backend/images/example_logo_light.png');
            @endphp
            <img src="{{ $logoSrc }}" class="h-[56px]"  alt="{{asset(setting('site_title','global') )}}">
        </a>
    </div>
    <div class="flex bg-white border rounded-lg shadow-lg overflow-hidden mx-auto w-full max-w-5xl">
        <div class="hidden md:flex items-center justify-center md:w-1/2 bg-cover p-8 lg:px-16" style="background: url( {{asset( setting('login_bg','global') )}} ) no-repeat center center;">
            <div class="2xl:mb-10 text-center space-y-3">
                <a href="{{ route('home')}}" class="inline-flex items-center justify-center">
                    @php
                        $logoSrc = setting('site_logo','global')
                            ? asset(setting('site_logo','global'))
                            : asset('backend/images/example_logo_light.png');
                    @endphp
                    <img src="{{ $logoSrc }}" class="h-[56px]"  alt="{{asset(setting('site_title','global') )}}">
                </a>
            </div>
        </div>
        <div class="w-full p-8 md:w-1/2 lg:px-16">
            <h2 class="text-2xl font-semibold text-gray-700">
                {{ __('Admin Reset Password') }}
            </h2>
            <div class="auth-body">
                <form action="{{ route('admin.forget.password.submit') }}" method="post" class="space-y-4">
                    @csrf
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif

                    <div class="fromGroup">
                        <label class="block capitalize form-label">{{ __('Admin Email') }}</label>
                        <div class="relative ">
                            <input
                                type="email"
                                name="email"
                                class="form-control py-2 h-[48px]"
                                placeholder="Admin Email"
                                required
                            />
                        </div>
                    </div>
                    <div class="fromGroup">
                        <button class="btn btn-dark block w-full text-center" type="submit">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
                <!-- END: Login Form -->
                <div class=" relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                    <div class=" absolute inline-block bg-white dark:bg-slate-800 dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2
                                px-4 min-w-max text-sm text-slate-500 dark:text-slate-400font-normal ">
                        {{ __('Or continue with') }}
                    </div>
                </div>
                <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-6 uppercase text-sm text-center">
                    <a href="{{ route('admin.login') }}" class="btn btn-light inline-flex items-center justify-center w-full">
                        {{ __('Login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
