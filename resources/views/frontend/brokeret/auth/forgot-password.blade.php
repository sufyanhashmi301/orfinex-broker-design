@extends('frontend::layouts.auth')
@section('title')
    {{ __('Forgot password') }}
@endsection
@section('content')
    <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
        <div class="mb-5 sm:mb-8">
            <h1 class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                {{ __('Password Reset') }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Enter your Email and instructions will be sent to you!') }}
            </p>
        </div>
        @if ($errors->any())
            <x-alert type="error" class="mb-3">
                @foreach($errors->all() as $error)
                    {{ __($error) }}<br>
                @endforeach
            </x-alert>
        @endif
        <div>
            <!-- BEGIN: Login Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <x-forms.field
                    type="email"
                    fieldId="email"
                    fieldLabel="{{ __('Email') }}"
                    fieldName="email"
                    fieldPlaceholder="{{ __('Enter your email address') }}"
                    fieldValue="{{ old('email') }}"
                />
                <x-forms.button type="submit" class="w-full" size="lg" variant="primary">
                    {{ __('Email Password Reset Link') }}
                </x-forms.button>
            </form>
            <!-- END: Login Form -->
            <div class="mt-5">
                <p class="text-sm font-normal text-gray-700 dark:text-gray-400">
                    {{ __("Or Back To") }}
                    <x-text-link href="{{ route('login') }}" variant="text">
                        {{ __('Login.') }}
                    </x-text-link>
                </p>
            </div>
        </div>
    </div>
@endsection
