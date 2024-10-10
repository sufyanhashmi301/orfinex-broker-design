@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Now') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{ route('user.dashboard') }}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('Deposit') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Methods') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header noborder">
            <h4 class="card-title">
                {{ __('All payment methods') }}
            </h4>
        </div>
        <div class="card-body p-6 pt-0">
            <div class="grid xl:grid-cols-2 grid-cols-1 gap-5">
                <div class="card border hover:shadow-lg">
                    <div class="card-header items-center !p-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full flex-1 ltr:mr-[10px] rtl:ml-[10px]">
                                <img src="{{ asset('frontend/images/perfectMoney.svg') }}" alt="{{ __('Perfect Money') }}" class="block w-full h-full object-cover rounded-full">
                            </div>
                            <span class="flex-none text-slate-900 dark:text-white text-lg text-ellipsis whitespace-nowrap">
                                {{ __('Perfect Money') }}
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-success text-success bg-opacity-30 capitalize rounded-3xl">
                                {{ __('Recommended') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <ul class="space-y-3">
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Processing Time') }}</span>
                                <span>{{ __('Instant - 30 minutes') }}</span>
                            </li>
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Fee') }}</span>
                                <span>{{ __('0%') }}</span>
                            </li>
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Limits') }}</span>
                                <span>{{ __('10 - 20,000 USD') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card border hover:shadow-lg">
                    <div class="card-header items-center !p-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full flex-1 ltr:mr-[10px] rtl:ml-[10px]">
                                <img src="{{ asset('frontend/images/binancePay.svg') }}" alt="{{ __('BinancePay') }}" class="block w-full h-full object-cover rounded-full">
                            </div>
                            <span class="flex-none text-slate-900 dark:text-white text-lg text-ellipsis whitespace-nowrap">
                                {{ __('BinancePay') }}
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-success text-success bg-opacity-30 capitalize rounded-3xl">
                                {{ __('Recommended') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <ul class="space-y-3">
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Processing Time') }}</span>
                                <span>{{ __('Instant - 30 minutes') }}</span>
                            </li>
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Fee') }}</span>
                                <span>{{ __('0%') }}</span>
                            </li>
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Limits') }}</span>
                                <span>{{ __('10 - 20,000 USD') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
