@extends('backend.layouts.app')
@section('title')
    {{ __('Fraud Protection') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="space-y-5">
        <div class="card">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">{{ __('Copy Traders') }}</h4>
                    <p class="card-text">{{ __('Risk Management') }}</p>
                </div>
                <a href="#" class="inline-flex items-center space-x-3 text-sm capitalize font-medium text-slate-600 dark:text-slate-300">
                    <span>{{ __('View result') }}</span>
                    <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                </a>
            </div>
            <div class="card-body p-6 pt-3">
                <div class="flex flex-wrap items-start gap-5">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xl font-medium">{{ __('33540') }}</span>
                            <span class="rounded-xl text-sm bg-slate-50 dark:bg-slate-900 px-2 py-2">
                                {{ __('Accounts Scanned') }}
                            </span>
                        </div>
                        <div class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            <iconify-icon class="text-success" icon="lucide:arrow-up"></iconify-icon>
                            {{ __('12.74% higher than last week') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xl font-medium">{{ __('128') }}</span>
                        <span class="rounded-xl text-sm bg-slate-50 dark:bg-slate-900 px-2 py-2">
                            {{ __('Accounts Flagged') }}
                        </span>
                    </div>
                    <div class="font-normal text-xs text-slate-600 dark:text-slate-300 ml-auto">
                        {{ __('Last scan 3 minutes ago') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">{{ __('Hedge Traders') }}</h4>
                    <p class="card-text">{{ __('Risk Management') }}</p>
                </div>
                <a href="#" class="inline-flex items-center space-x-3 text-sm capitalize font-medium text-slate-600 dark:text-slate-300">
                    <span>{{ __('View result') }}</span>
                    <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                </a>
            </div>
            <div class="card-body p-6 pt-3">
                <div class="flex flex-wrap items-start gap-5">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xl font-medium">{{ __('33540') }}</span>
                            <span class="rounded-xl text-sm bg-slate-50 dark:bg-slate-900 px-2 py-2">
                                {{ __('Accounts Scanned') }}
                            </span>
                        </div>
                        <div class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            <iconify-icon class="text-success" icon="lucide:arrow-up"></iconify-icon>
                            {{ __('12.74% higher than last week') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xl font-medium">{{ __('128') }}</span>
                        <span class="rounded-xl text-sm bg-slate-50 dark:bg-slate-900 px-2 py-2">
                            {{ __('Accounts Flagged') }}
                        </span>
                    </div>
                    <div class="font-normal text-xs text-slate-600 dark:text-slate-300 ml-auto">
                        {{ __('Last scan 3 minutes ago') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">{{ __('IP Analysis') }}</h4>
                    <p class="card-text">{{ __('Risk Management') }}</p>
                </div>
                <a href="#" class="inline-flex items-center space-x-3 text-sm capitalize font-medium text-slate-600 dark:text-slate-300">
                    <span>{{ __('View result') }}</span>
                    <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                </a>
            </div>
            <div class="card-body p-6 pt-3">
                <div class="flex flex-wrap items-start gap-5">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xl font-medium">{{ __('33540') }}</span>
                            <span class="rounded-xl text-sm bg-slate-50 dark:bg-slate-900 px-2 py-2">
                                {{ __('Accounts Scanned') }}
                            </span>
                        </div>
                        <div class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            <iconify-icon class="text-success" icon="lucide:arrow-up"></iconify-icon>
                            {{ __('12.74% higher than last week') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xl font-medium">{{ __('128') }}</span>
                        <span class="rounded-xl text-sm bg-slate-50 dark:bg-slate-900 px-2 py-2">
                            {{ __('Accounts Flagged') }}
                        </span>
                    </div>
                    <div class="font-normal text-xs text-slate-600 dark:text-slate-300 ml-auto">
                        {{ __('Last scan 3 minutes ago') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
