@extends('frontend::layouts.auth')
@section('title')
    {{ __('Verify Email') }}
@endsection
@section('description')
    {{ __('verify your email address by clicking on the link we just emailed to you') }}
@endsection
@section('content')
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif
    <div class="site-auth-form">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary block w-full text-center mb-3">
                {{ __('Resend Verification Email') }}
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger block w-full text-center">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
@endsection



