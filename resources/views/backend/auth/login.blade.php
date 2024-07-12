@extends('backend.auth.index')
@section('title')
    {{ __('Login') }}
@endsection
@section('auth-content')
    <div class="logo d-sm-none mb-3">
        <a href="{{ route('home') }}">
            <img src="{{asset(setting('site_logo','global') )}}" height="65" alt="{{asset(setting('site_title','global') )}}"/>
        </a>
    </div>
    <div class="login">
        <div class="side-img primary-overlay" style="background: url({{ asset(setting('login_bg','global')) }}) no-repeat center center;">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{asset(setting('site_logo','global') )}}" alt="{{asset(setting('site_title','global') )}}"/>
                </a>
            </div>
        </div>
        <div class="login-content">
            <div class="title mb-4">
                <h3>{{ __('Admin Login') }}</h3>
            </div>
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
            <div class="auth-body">
                <form action="{{ route('admin.login') }}" method="post">
                    @csrf
                    <div class="single-box">
                        <label for="" class="box-label">{{ __('Admin Email') }}</label>
                        <input
                            type="email"
                            name="email"
                            class="box-input"
                            placeholder="Admin Email"
                            required
                        />
                    </div>
                    <div class="single-box">
                        <label for="" class="box-label">{{ __('Password') }}</label>
                        <input
                            type="password"
                            name="password"
                            class="box-input"
                            placeholder="Password"
                            required
                        />
                    </div>
                    @if($googleReCaptcha)
                        <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                             data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                        </div>
                    @endif
                    <div class="single-box">
                        <button class="site-btn primary-btn" type="submit">{{ __('Admin Login') }}</button>
                        <a href="{{route('admin.forget.password.now')}}"
                           class="link mt-2">{{ __('Forget Password?') }}</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
