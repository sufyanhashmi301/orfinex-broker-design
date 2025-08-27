@extends('frontend::layouts.user')
@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-7 py-12">
        <div class="flex flex-col items-center">
            <div class="relative mb-10">
                <img src="{{ asset('frontend/images/error-page__img.svg') }}" alt="{{ __('Error Page Image') }}">
            </div>
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                {{ __('Oops, Something Went Wrong!') }}
            </h2>
            <p class="max-w-xl text-center text-gray-500 text-sm mb-3 dark:text-gray-400">
                {{ __('It seems like your transaction couldn’t be completed. Don’t worry—there might be a simple issue. Please try again, or contact our support team for assistance. We’re here to help if you need us! Feel free to try again or contact support.') }}
            </p>
            <blockquote class="border-0 text-gray-500 dark:text-gray-400 text-base my-5">
                {{ __('Our greatest glory is not in never falling, but in rising every time we fall.') }}
                <span class="text-sm text-gray-400 text-right block mt-3">
                    {{ __('– Confucius') }}
                </span>
            </blockquote>
            <div class="flex items-center gap-3 mt-5">
                <x-link-button href="" size="lg" variant="primary">
                    {{ __('Retry Payment') }}
                </x-link-button>
                <x-link-button href="" size="lg" variant="primary">
                    {{ __('Contact Support') }}
                </x-link-button>
            </div>
        </div>
    </div>
@endsection
