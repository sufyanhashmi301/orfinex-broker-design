@extends('frontend::layouts.auth')
@section('title')
    {{ __('2FA Security') }}
@endsection
@section('content')
    <div class="shadow-xl rounded-xl border p-8">
        <div class="text-center 2xl:mb-10 mb-5">
            <h4 class="font-medium">👋 {{ __('Welcome Back!') }}</h4>
            <div class="text-slate-500 dark:text-slate-400 text-base">
                {{ __('Sign in to continue with') }} {{ setting('site_title','global') }} {{ __('User Panel') }}
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                    <strong>{{ __('You entered') }} {{ $error }}</strong>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
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
                            placeholder="{{ __('Enter your Pin') }}"
                            required
                        />
                    </div>
                </div>
                <x-forms.button type="submit" class="w-full" size="lg" variant="primary">
                    {{ __('Authenticate Now') }}
                </x-forms.button>
            </form>
        </div>
    </div>
@endsection
