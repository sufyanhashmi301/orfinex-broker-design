@extends('backend.auth.index')
@section('title')
    {{ __('Login') }}
@endsection
@section('auth-content')    
<div class="md:hidden text-center mb-3">
    <a href="{{ route('home')}}" class="">
        <img src="{{asset(setting('site_logo','global') )}}" class="h-[56px]"  alt="{{asset(setting('site_title','global') )}}">
    </a>
</div>
<div class="flex bg-white border rounded-lg shadow-lg overflow-hidden mx-auto w-full max-w-5xl">
    <div class="hidden md:flex items-center justify-center md:w-1/2 bg-cover p-8 lg:px-16" style="background-image: url({{ asset(setting('login_bg','global')) }})">
        <div class="2xl:mb-10 text-center space-y-3">
            <a href="{{ route('home')}}" class="inline-flex items-center justify-center">
                <img src="{{asset(setting('site_logo','global') )}}" class="h-[56px]" alt="{{asset(setting('site_title','global') )}}">
            </a>
        </div>
    </div>
    <div class="w-full p-8 md:w-1/2 lg:px-16">
        <h2 class="text-2xl font-semibold text-gray-700">
            {{ __('Admin Login') }}
        </h2>
        <div class="">
            <!-- BEGIN: Login Form -->
            <form action="{{ route('admin.login') }}" method="post" class="space-y-4">
                @csrf
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
                    <label class="block capitalize form-label">{{ __('Password') }}</label>
                    <div class="relative ">
                        <input
                            type="password"
                            name="password"
                            class="form-control py-2 h-[48px]"
                            placeholder="Password"
                            required
                        />
                    </div>
                </div>
                <div class="flex justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input class="hiddens mr-2" type="checkbox" name="remember" />
                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                            {{ __('Keep me signed in') }}
                        </span>
                    </label>
                    <a href="{{route('admin.forget.password.now')}}" class="text-sm text-slate-800 dark:text-slate-400 leading-6 font-medium">
                        {{ __('Forget Password?') }}
                    </a>
                </div>
                <div class="fromGroup">
                    <button class="btn btn-dark block w-full text-center" type="submit">{{ __('Admin Login') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
