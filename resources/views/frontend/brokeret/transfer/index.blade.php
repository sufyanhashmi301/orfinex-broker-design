@extends('frontend::layouts.user')
@section('title')
    {{ __('Transfer') }}
@endsection
@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @if(setting('is_internal_transfer', 'transfer_internal'))
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <span class="mb-3 block text-theme-xl font-semibold text-gray-800 dark:text-white/90">
                    {{ __('Internal Transfer') }}
                </span>
                <div class="mb-1 flex items-center justify-between">
                    <h2 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        {{ __('Unlimited') }}
                    </h2>
                    <x-badge variant="light" style="light" size="sm">
                        {{ __('Instant') }}
                    </x-badge>
                </div>
                <p class="text-theme-sm text-success-500 mb-2">
                    {{ __('Free of Cost') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Instantly transfer funds between your accounts, seamlessly and without limits.') }}
                </p>
                <div class="my-6 h-px w-full bg-gray-200 dark:bg-gray-800"></div>
                <div class="price-body space-y-8">
                    <div class="bg-gray-50 dark:bg-gray-900 rounded p-3">
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('For sending funds to') }}</span>
                            <span class="text-slate-900 dark:text-slate-300">{{ __('your accounts') }}</span>
                        </div>
                    </div>
                    <div>
                        <x-link-button href="{{ route('user.send-money.internal-view') }}" class="w-full mt-auto" size="lg" variant="primary" icon="arrow-right" icon-position="right">
                            {{ __('Transfer Now') }}
                        </x-link-button>
                    </div>
                </div>
            </div>
        @endif
        @if(setting('is_external_transfer', 'transfer_external'))
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <span class="mb-3 block text-theme-xl font-semibold text-gray-800 dark:text-white/90">
                    {{ __('External Transfer') }}
                </span>
                <div class="mb-1 flex items-center justify-between">
                    <h2 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        {{ __('$500') }}
                    </h2>
                    <x-badge variant="light" style="light" size="sm">
                        {{ __('Semi Instant') }}
                    </x-badge>
                </div>
                <p class="text-theme-sm text-success-500 mb-2">
                    {{ __('1% Fee Applied') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Safely send money to friends and family with easy-to-use External Transfer feature.') }}
                </p>
                <div class="my-6 h-px w-full bg-gray-200 dark:bg-gray-800"></div>
                <div class="price-body space-y-8">
                    <div class="bg-gray-50 dark:bg-gray-900 rounded p-3">
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('For sending funds to') }}</span>
                            <span class="text-slate-900 dark:text-slate-300">{{ __('your accounts') }}</span>
                        </div>
                    </div>
                    <div>
                        <x-link-button href="{{ route('user.send-money.view') }}" class="w-full mt-auto" size="lg" variant="primary" icon="arrow-right" icon-position="right">
                            {{ __('Transfer Now') }}
                        </x-link-button>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
