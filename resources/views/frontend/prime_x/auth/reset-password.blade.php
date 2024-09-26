@extends('frontend::layouts.auth')
@section('title')
    {{ __('Reset Password') }}
@endsection
@section('description')
    {{  __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
@endsection
@section('content')
    <!-- Login Section -->
    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            @foreach($errors->all() as $error)
                <strong>{{$error}}</strong>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('status') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="fromGroup">
            <label class="block capitalize form-label">{{ __('Email') }}</label>
            <div class="relative">
                <input
                    class="form-control !text-lg"
                    type="text"
                    name="email"
                    placeholder="Enter your email address"
                    required
                    value="{{ old('email',$request->email) }}"
                />
            </div>
        </div>
        <div class="fromGroup">
            <label class="block capitalize form-label" for="email">{{ __('New Password') }}</label>
            <div class="relative">
                <input
                    class="form-control !text-lg"
                    type="password"
                    name="password"
                    required
                />
            </div>
        </div>

        <div class="fromGroup">
            <label class="block capitalize form-label" for="email">{{ __('Confirm Password') }}</label>
            <div class="relative">
                <input
                    class="form-control !text-lg"
                    type="password"
                    name="password_confirmation"
                    required
                />
            </div>
        </div>

        <button type="submit" class="btn btn-primary block w-full text-center">
            {{ __('Reset Password') }}
        </button>
    </form>
    <!-- Login Section End -->
@endsection

