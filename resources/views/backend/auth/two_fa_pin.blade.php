@extends('backend.auth.index')
@section('title')
    {{ __('2FA Security') }}
@endsection
@section('auth-content')
    <div class="max-w-sm w-full space-y-10">
        <div class="text-center">
            <a href="{{ route('home')}}" class="inline-block">
                <img src="{{asset(setting('site_logo','global') )}}" class="h-[56px]"  alt="{{asset(setting('site_title','global') )}}">
            </a>
            <div class="mt-5">
                <p class="text-slate-500 dark:text-slate-400 mb-2">
                    {{ __('Please enter the') }}
                    <strong>{{ __('OTP') }}</strong> {{ __('generated on your Authenticator App.') }}
                </p>
                <p class="text-slate-500 dark:text-slate-400">
                    {{ __('Ensure you submit the current one because it refreshes every 30 seconds.') }}
                </p>
            </div>
        </div>
        <div class="space-y-5">
            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    @foreach($errors->all() as $error)
                        <strong>You Entered {{$error}}</strong>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="site-auth-form">
                <form method="POST" action="{{ route('admin.2fa.verify') }}" class="space-y-5">
                    @csrf
                    <div class="single-field">

                        <label class="form-label" for="password">{{ __('One Time Password') }}</label>
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
                <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                    <div class="absolute inline-block bg-white dark:bg-slate-800 dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
                        {{ __('Or continue with') }}
                    </div>
                </div>
                <div class="mt-6">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-light inline-flex items-center justify-center w-full">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection