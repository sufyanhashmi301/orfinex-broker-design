@extends('frontend::layouts.auth')
@section('title')
    {{ __('2FA Security') }}
@endsection
@section('description')
    {{ __('Sign in to continue with') }} {{ setting('site_title','global') }} {{ __('User Panel') }}
@endsection
@section('content')
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
@endsection


