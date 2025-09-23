@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Successful') }}
@endsection
@section('content')
    <div class="transaction-status text-center flex flex-col sm:items-center px-7 py-12 ">
        <div class="relative mb-10">
            <img src="{{ asset('frontend/images/success-page__img.svg') }}" alt="{{ __('Success Image') }}">
            <div class="absolute left-0 right-0 bottom-0 icon h-16 w-16 bg-primary rounded-full flex flex-col items-center justify-center mx-auto">
                <i data-lucide="check" class="w-12 h-12"></i>
            </div>
        </div>
        <h5 class="text-lg text-gray-500 dark:text-gray-400 mb-2">
            {{ $notify['card-header'] ?? __('Payment Successful!') }}
        </h5>
        <h2 class="text-3xl font-semibold text-gray-800 dark:text-white/90 mb-3">
            {{ $notify['title'] ?? __('Your transaction was processed successfully!') }}
        </h2>
        <p class="max-w-xl text-center text-gray-500 text-sm mb-3 dark:text-gray-400">
            {{ $notify['p'] ?? __('Thank you for your trust in '. setting('site_title','global') .'.') }}
        </p>
        @if(isset($notify['strong']))
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                {{ $notify['strong'] }}
            </p>
        @endif
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mt-5">
            @if(isset($notify['action']) && isset($notify['a']))
                <x-frontend::link-button href="{{ $notify['action'] }}" class="w-full sm:w-auto" size="lg" variant="primary" icon="upload">
                    {{ $notify['a'] }}
                </x-frontend::link-button>
            @endif
            <x-frontend::link-button href="{{ route('user.dashboard') }}" class="w-full sm:w-auto" size="lg" variant="secondary" icon="home">
                {{ __('Go to Dashboard') }}
            </x-frontend::link-button>
        </div>
    </div>
@endsection
