@extends('backend.auth.index')
@section('title')
    {{ __('Login') }}
@endsection
@section('auth-content')

<div class="max-w-sm w-full">
    <div class="text-center">
        <a href="{{ route('home')}}" class="inline-block">
            @php
                $logoSrc = setting('site_logo','global')
                    ? asset(setting('site_logo','global'))
                    : asset('backend/images/example_logo_light.png');
            @endphp
            <img src="{{ $logoSrc }}" class="h-[56px]"  alt="{{asset(setting('site_title','global') )}}">
        </a>
        <h2 class="text-2xl font-semibold text-gray-700 mt-5">
            {{ __('Backoffice Login') }}
        </h2>
    </div>
    <!-- BEGIN: Login Form -->
    <form action="{{ route('admin.login') }}" method="post" class="space-y-5">
        @csrf
        <div class="relative rounded shadow">
            <div class="absolute z-10 rounded ring-1 ring-slate-100 ring-inset pointer-events-none" style="inset: 0px"></div>
            <div>
                <input
                    type="email"
                    name="email"
                    class="relative w-full border-0 text-sm ring-1 ring-slate-100 ring-inset aer dark:text-slate-300 px-3 py-2"
                    placeholder="Admin Email"
                    required
                />
            </div>
            <div>
                <input
                    type="password"
                    name="password"
                    class="relative w-full border-0 text-sm ring-1 ring-slate-100 ring-inset aeg dark:text-slate-300 px-3 py-2"
                    placeholder="Password"
                    required
                />
            </div>
            @if($googleReCaptcha)
                <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                     data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                </div>
            @endif
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
        <button class="btn btn-dark block w-full text-center" type="submit">
            {{ __('Access Now') }}
        </button>
    </form>
    <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
        <div class="absolute inline-block bg-body dark:bg-body dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
            {{ __('Powered by') }}
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="https://brokeret.com/" target="__blank">
            <img src="{{ asset('backend/images/brokeret_logo.png') }}" class="h-8 inline-flex" alt="">
        </a>
    </div>
</div>

@endsection
@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
