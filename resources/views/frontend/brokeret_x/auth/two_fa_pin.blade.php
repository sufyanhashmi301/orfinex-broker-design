@extends('frontend::layouts.auth')
@section('title')
    {{ __('2FA Security') }}
@endsection
@section('content')
    <div class="mb-5 sm:mb-8">
        <h1 class="mb-2 text-title-sm font-semibold text-gray-800 dark:text-white/90">
            {{ __('Welcome to :site_title', ['site_title' => setting('site_title')]) }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Sign in to continue with') }} {{ setting('site_title','global') }} {{ __('User Panel') }}
        </p>
    </div>

    @if ($errors->any())
        <x-frontend::alert type="error" class="mb-3">
            @foreach($errors->all() as $error)
                {{ __('You entered') }} {{ $error }}<br>
            @endforeach
        </x-frontend::alert>
    @endif
    
    <div class="site-auth-form">
        <form method="POST" action="{{ route('user.setting.2fa.verify') }}" class="space-y-5">
            @csrf
        
            <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                {{ __('Please enter the') }}
                <strong>{{ __('OTP') }}</strong> {{ __('generated on your Authenticator App.') }}
                <br> {{ __('Ensure you submit the current one because it refreshes every 30 seconds.') }}
            </p>

            <x-frontend::forms.password-field 
                :fieldId="'one_time_password'" 
                :fieldLabel="'One Time Password'" 
                :fieldPlaceholder="'Enter your Pin'" 
                :fieldRequired="true" 
                :fieldName="'one_time_password'" 
                :fieldValue="old('one_time_password')" />

            <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary">
                {{ __('Authenticate Now') }}
            </x-forms.button>
        </form>
    </div>
@endsection
