@extends('frontend::layouts.user')
@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-7 py-12">
        <div class="flex flex-col items-center">
            <div class="relative mb-10">
                <img src="{{ asset('frontend/images/canceled-page__img.svg') }}" alt="{{ __('Canceled Page Image') }}">
                <div class="absolute left-0 right-0 bottom-0 icon h-16 w-16 bg-danger-500 rounded-full flex flex-col items-center justify-center mx-auto">
                    <i data-lucide="x" class="text-white text-4xl"></i>
                </div>
            </div>
            <h5 class="text-lg text-gray-500 dark:text-gray-400 mb-2">
                {{ __('Payment Canceled') }}
            </h5>
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                {{ __('Your transaction has been canceled') }}
            </h2>
            <p class="max-w-xl text-center text-gray-500 text-sm mb-3 dark:text-gray-400">
                {{ __('If this was a mistake or you wish to proceed with your payment, you can always try again. Let us know if you need any assistance, or feel free to retry the payment at your convenience.') }}
            </p>
            <blockquote class="border-0 text-gray-500 dark:text-gray-400 text-base my-5">
                {{ __('The only limit to our realization of tomorrow is our doubts of today.') }}
                <span class="text-sm text-gray-400 text-right block mt-3">
                    {{ __('– Franklin D. Roosevelt') }}
                </span>
            </blockquote>
            <div class="flex items-center gap-3 mt-5">
                <x-link-button href="" size="lg" variant="primary">
                    {{ __('Retry Payment') }}
                </x-link-button>
                <x-link-button href="{{ route('user.dashboard') }}" size="lg" variant="primary">
                    {{ __('Go to Dashboard') }}
                </x-link-button>
            </div>
        </div>
    </div>
@endsection
