@extends('frontend::layouts.user')
@section('title')
    {{ __('Transfer') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>
    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
        @if(setting('is_internal_transfer', 'transfer_internal'))
            <a href="{{ route('user.send-money.internal-view') }}" class="flex gap-3 rounded-lg border border-gray-200 p-5 hover:shadow-lg dark:border-gray-800 md:p-6">
                <div class="shrink-0 h-11 w-11 overflow-hidden rounded-full">
                    <img src="{{ asset('common/images/internal_transfer.svg') }}" class="h-10" alt="{{ __('Internal Transfer') }}" />
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                            {{ __('Internal Transfer') }}
                        </h4>
                        <x-badge variant="success" style="light" size="sm">
                            {{ __('Verification required') }}
                        </x-badge>
                    </div>
                    
                    <ul class="space-y-1">
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Processing Time') }}</span>
                            <span class="capitalize">{{ __('Instant - 1 day') }}</span>
                        </li>
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Fee') }}</span>
                            <span>{{ __('Free of Cost') }}</span>
                        </li>
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Limits') }}</span>
                            <span>{{ __('Unlimited') }}</span>
                        </li>
                    </ul>
                </div>
            </a>
        @endif
        @if(setting('is_external_transfer', 'transfer_external'))
            <a href="{{ route('user.send-money.view') }}" class="flex gap-3 rounded-lg border border-gray-200 p-5 hover:shadow-lg dark:border-gray-800 md:p-6">
                <div class="shrink-0 h-11 w-11 overflow-hidden rounded-full">
                    <img src="{{ asset('common/images/external_transfer.svg') }}" class="h-10" alt="{{ __('External Transfer') }}" />
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                            {{ __('External Transfer') }}
                        </h4>
                        <x-badge variant="success" style="light" size="sm">
                            {{ __('Verification required') }}
                        </x-badge>
                    </div>
                    
                    <ul class="space-y-1">
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Processing Time') }}</span>
                            <span class="capitalize">{{ __('Semi Instant') }}</span>
                        </li>
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Fee') }}</span>
                            <span>{{ __('1% Fee Applied') }}</span>
                        </li>
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Limits') }}</span>
                            <span>{{ __('500 - 1,000,000 USD') }}</span>
                        </li>
                    </ul>
                </div>
            </a>
        @endif
    </div>
@endsection
