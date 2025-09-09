@extends('frontend::layouts.auth')
@section('title')
    {{ __('Forgot password') }}
@endsection
@section('content')
    <div class="mb-5 sm:mb-8">
        <h1 class="mb-2 text-title-sm font-semibold text-gray-800 dark:text-white/90">
            {{ __('Password Reset') }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Enter your Email and instructions will be sent to you!') }}
        </p>
    </div>
    @if ($errors->any())
        <x-frontend::alert type="error" class="mb-3">
            @foreach($errors->all() as $error)
                {{ __($error) }}<br>
            @endforeach
        </x-frontend::alert>
    @endif
    <div>
        <!-- BEGIN: Login Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <x-frontend::forms.field
                type="email"
                fieldId="email"
                fieldLabel="{{ __('Email') }}"
                fieldName="email"
                fieldPlaceholder="{{ __('Enter your email address') }}"
                fieldValue="{{ old('email') }}"
            />
            <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary">
                {{ __('Email Password Reset Link') }}
            </x-frontend::forms.button>
        </form>
        <!-- END: Login Form -->
        <div class="mt-5">
            <p class="text-center text-sm font-normal text-gray-700 dark:text-gray-400">
                {{ __("Or Back To") }}
                <x-frontend::text-link href="{{ route('login') }}" variant="text">
                    {{ __('Sign In.') }}
                </x-frontend::text-link>
            </p>
        </div>
    </div>
@endsection
