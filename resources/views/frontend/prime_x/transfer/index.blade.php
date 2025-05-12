@extends('frontend::layouts.user')
@section('title')
    {{ __('Transfer') }}
@endsection
@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @if(setting('is_internal_transfer', 'transfer_internal'))
            <div class="card price-table shadow-base p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-white">
                <header class="mb-6">
                    <h4 class="text-xl text-slate-500 dark:text-slate-300 mb-3">
                        {{ __('Internal Transfer') }}
                    </h4>
                    <div class="space-x-4 relative flex items-center justify-between mb-5 rtl:space-x-reverse text-slate-900 dark:text-white">
                        <span class="text-3xl font-medium">
                            {{ __('Unlimited') }}
                        </span>
                        <span class="badge badge-primary capitalize h-auto">
                            {{ __('Instant') }}
                        </span>
                    </div>
                    <p class="text-base text-success">
                        {{ __('Free of Cost') }}
                    </p>
                </header>
                <div class="price-body space-y-8">
                    <p class="text-base leading-5 text-slate-500">
                        {{ __('Instantly transfer funds between your accounts, seamlessly and without limits.') }}
                    </p>
                    <div class="bg-slate-50 dark:bg-body rounded p-4">
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('For sending funds to') }}</span>
                            <span class="text-slate-900 dark:text-slate-300">{{ __('your accounts') }}</span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('user.send-money.internal-view') }}" class="btn block-btn btn-primary loaderBtn inline-flex items-center">
                            {{ __('Transfer Now') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
        @if(setting('is_external_transfer', 'transfer_external'))
            <div class="card price-table shadow-base p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-white">
                <header class="mb-6">
                    <h4 class="text-xl text-slate-500 dark:text-slate-300 mb-3">
                        {{ __('External Transfer') }}
                    </h4>
                    <div class="space-x-4 relative flex items-center justify-between mb-5 rtl:space-x-reverse text-slate-900 dark:text-white">
                        <span class="text-3xl font-medium">
                            {{ __('$500') }}
                        </span>
                        <span class="badge badge-primary capitalize h-auto">
                            {{ __('Semi Instant') }}
                        </span>
                    </div>
                    <p class="text-base text-success">
                        {{ __('1% Fee Applied') }}
                    </p>
                </header>
                <div class="price-body space-y-8">
                    <p class="text-base leading-5 text-slate-500">
                        {{ __('Safely send money to friends and family with easy-to-use External Transfer feature.') }}
                    </p>
                    <div class="bg-slate-50 dark:bg-body rounded p-4">
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('For sending funds to') }}</span>
                            <span class="text-slate-900 dark:text-slate-300">{{ __('your accounts') }}</span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('user.send-money.view') }}" class="btn block-btn btn-primary loaderBtn inline-flex items-center">
                            {{ __('Transfer Now') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
