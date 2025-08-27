@extends('frontend::deposit.index')
@section('deposit_content')
    <div class="progress-steps-form rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="transaction-status flex flex-col items-center px-7 py-12">
            <div class="relative mb-10">
                <img src="{{ asset('frontend/images/success-page__img.svg') }}" alt="{{ __('Success Image') }}">
                <div class="absolute left-0 right-0 bottom-0 icon h-16 w-16 bg-primary rounded-full flex flex-col items-center justify-center mx-auto">
                    <iconify-icon icon="heroicons:check-16-solid" class="text-white text-4xl"></iconify-icon>
                </div>
            </div>
            <h5 class="text-lg text-gray-500 dark:text-gray-400 mb-2">
                {{ __('Payment Successful!') }}
            </h5>
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                {{ __('Your transaction was processed successfully!') }}
            </h2>
            <p class="max-w-xl text-center text-gray-500 text-sm mb-3 dark:text-gray-400">
                <span class="block mb-1">
                    {{ __('Thank you for your trust in '. setting('site_title','global') .'.') }}
                </span>
                {{ __('Feel free to explore more of our services or check your account for the updated balance.') }}
            </p>
            <div class="flex items-center gap-3 mt-5">
                <x-link-button href="{{ route('user.dashboard') }}" size="lg" variant="primary" icon="home">
                    {{ __('Go to Dashboard') }}
                </x-link-button>
                @php
                    $trustpilot = plugin_active('Trustpilot');
                @endphp
                @if($trustpilot && $trustpilot->status)
                    @php
                        $trustpilotData = json_decode($trustpilot->data, true);
                    @endphp
                    <x-link-button href="{{ $trustpilotData['link'] }}" target="_blank" size="lg" variant="secondary" icon="star">
                        {{ __('Review us on Trustpilot') }}
                    </x-link-button>
                @endif
            </div>
        </div>
    </div>
@endsection
