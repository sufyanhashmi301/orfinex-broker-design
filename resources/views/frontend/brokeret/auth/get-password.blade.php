@extends('frontend::layouts.auth')
@section('title')
    {{ __('Get password') }}
@endsection
@section('content')
    <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
        <div class="mb-5 sm:mb-8">
            <h1 class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                {{ __('Get Password') }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Enter your Email and password will be sent to you!') }}
            </p>
        </div>
        <div>
            <!-- BEGIN: Login Form -->
            <form method="POST" action="{{ route('password.send') }}" class="space-y-5">
                @csrf
                <div class="fromGroup">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" for="email">
                        {{ __('Email') }}
                    </label>
                    <div class="relative">
                        <input
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            type="text"
                            name="email"
                            placeholder="{{ __('Enter your email address') }}"
                            required
                            value="{{ old('email') }}"
                        />
                    </div>
                </div>
                <x-forms.button type="submit" class="w-full" size="lg" variant="primary">
                    {{ __('Get Password') }}
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
