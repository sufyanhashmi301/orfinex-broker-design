@extends('backend.auth.index')
@section('title')
    {{ __('Reset Password') }}
@endsection
@section('auth-content')
    <div class="logo d-sm-none mb-3">
        <a href="{{ route('home') }}">
            <img src="{{asset(setting('site_logo','global') )}}" height="65" alt="{{asset(setting('site_title','global') )}}"/>
        </a>
    </div>
    <div class="login">
        <div class="side-img primary-overlay" style="background: url( {{asset( setting('login_bg','global') )}} ) no-repeat center center;">
            
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{asset(setting('site_logo','global') )}}" alt="{{asset(setting('site_title','global') )}}"/>
                </a>
            </div>
        </div>
        <div class="login-content">
            <div class="title mb-4">
                <h3>{{ __('Admin Reset Password') }}</h3>
            </div>
            <div class="auth-body">
                <form action="{{ route('admin.forget.password.submit') }}" method="post">
                    @csrf

                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif

                    <div class="single-box">
                        <label for="" class="box-label">Admin Email</label>
                        <input
                            type="email"
                            name="email"
                            class="box-input"
                            placeholder="Admin Email"
                            required
                        />
                    </div>
                    <div class="single-box">
                        <button class="site-btn primary-btn" type="submit">Send Password Reset Link</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
