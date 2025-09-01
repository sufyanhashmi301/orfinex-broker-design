@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Successful') }}
@endsection
@section('content')
    <x-progress-steps step-one-title="{{ __('Withdraw Amount') }}" :current-step="2" />
    
    <div class="progress-steps-form rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="transaction-status flex flex-col items-center px-7 py-12">
            <div class="relative mb-10">
                <img src="{{ asset('frontend/images/success-page__img.svg') }}" alt="{{ __('Success Image') }}">
                <div class="absolute left-0 right-0 bottom-0 icon h-16 w-16 bg-primary rounded-full flex flex-col items-center justify-center mx-auto">
                    <iconify-icon icon="heroicons:check-16-solid" class="text-white text-4xl"></iconify-icon>
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
            <div class="flex items-center gap-3 mt-5">
                @if(isset($notify['action']) && isset($notify['a']))
                    <x-link-button href="{{ $notify['action'] }}" size="lg" variant="primary" icon="upload">
                        {{ $notify['a'] }}
                    </x-link-button>
                @endif
                <x-link-button href="{{ route('user.dashboard') }}" size="lg" variant="secondary" icon="home">
                    {{ __('Go to Dashboard') }}
                </x-link-button>
            </div>
        </div>
    </div>
@endsection
