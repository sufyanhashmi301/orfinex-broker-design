@extends('frontend::layouts.auth')
@section('title')
    {{ __('Forgot password') }}
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
                <h2 class="text-2xl font-semibold text-gray-700">Password Reset</h2>
                <div class="font-normal text-sm text-slate-500 dark:text-slate-400 text-center px-2 bg-slate-100 dark:bg-slate-600 rounded py-3 mb-4 mt-3">
                    {{ __('Enter your Email and new password will be sent to you!') }}
                </div>
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
                    @if(session('status'))
                        <div class="alert alert-warning alert-dismissible fade show mt-2 text-sm" role="alert">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <p class="flex-1 font-Inter">
                                    {{ session('status') }}
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
                        <form method="POST" action="{{ route('password.request.submit') }}" class="space-y-4">
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
                            {{ __('Email Password') }}
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
                        <a href="{{ route('login') }}" class="btn btn-light inline-flex items-center justify-center w-full">
                            {{ __('Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Section End -->
@endsection


