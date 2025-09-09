@extends('frontend::layouts.auth')
@section('title')
    {{ __('Reset Password') }}
@endsection
@section('content')
    <div class="shadow-xl rounded-xl border p-8">
        <div class="text-center 2xl:mb-10 mb-5">
            <h4 class="font-medium">👋 {{ __('Reset Password') }}</h4>
            <div class="text-slate-500 dark:text-slate-400 text-base">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                    <strong>{{ $error }}</strong>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>{{ session('status') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
            </div>
        @endif
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <x-frontend::forms.field
                type="email"
                fieldId="email"
                fieldLabel="{{ __('Email') }}"
                fieldName="email"
                fieldPlaceholder="{{ __('Enter your email address') }}"
                fieldValue="{{ old('email',$request->email) }}"
            />
            <x-frontend::forms.password-field
                fieldId="password"
                fieldLabel="{{ __('New Password') }}"
                fieldName="password"
                fieldPlaceholder="{{ __('Enter your new password') }}"
                fieldValue="{{ old('password') }}"
            />
            <x-frontend::forms.password-field
                fieldId="password_confirmation"
                fieldLabel="{{ __('Confirm Password') }}"
                fieldName="password_confirmation"
                fieldPlaceholder="{{ __('Confirm your new password') }}"
                fieldValue="{{ old('password_confirmation') }}"
            />
            <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary">
                {{ __('Reset Password') }}
            </x-frontend::forms.button>
        </form>
    </div>
@endsection
