@extends('frontend::layouts.user')

@section('title', __('Active Plan Dashboard'))
@push('style')
    <style>
        #account_credentials_card {
            width: 21rem;
        }
    </style>
@endpush
@section('content')

    <div class="grid grid-cols-12 gap-5 mb-5">
        <div class="lg:col-span-5 col-span-12">
            <div class="card h-full">
                <div class="card-body p-6">
                    <div class="flex items-center mb-5">
                        <div class="flex-none">
                            <div class="w-12 h-12 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                <img src="@if(auth()->user()->avatar && file_exists('assets/'.auth()->user()->avatar)) {{asset($user->avatar)}} @else {{ asset('frontend/images/all-img/user.png') }}@endif" alt="" class="w-full h-full object-cover rounded-full">
                            </div>
                        </div>
                        <div class="flex-1 text-start">
                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                {{ __('John Dae') }}
                            </h4>
                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                {{ __('Express Account') }}
                            </div>
                        </div>
                    </div>
                    <ul>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Initial Balance:') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ __('$5,000') }}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Plan Type:') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ __('Express Demo | 50K') }}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Account Type:') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ __('Swap') }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="lg:col-span-7 col-span-12">
            <div class="card h-full">
                <div class="card-body p-6">
                    <div class="grid md:grid-cols-2 col-span-1 gap-5">
                        <div class="flex items-center mb-5">
                            <div class="flex-none">
                                <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                                    <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-1 text-start">
                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                    {{ __('44%') }}
                                </h4>
                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                    {{ __('Account Growth') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center mb-5">
                            <div class="flex-none">
                                <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                                    <iconify-icon class="text-2xl" icon="solar:pie-chart-outline"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-1 text-start">
                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 space-x-3 mb-1">
                                    <span class="text-slate-900 font-medium">{{ __('Start Date:') }}</span>
                                    <span class="">{{ __('Dec 5, 2022')}}</span>
                                </div>
                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 space-x-3">
                                    <span class="text-slate-900 font-medium">{{ __('End Date:') }}</span>
                                    <span class="">{{ __('Dec 5, 2022') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" value="12312" id="copyLogin" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200" data-target="copyLogin">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" type="password" id="password" value="FN2343" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password dark:text-slate-200">
                                <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" type="master_pass" id="master_pass" value="" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password dark:text-slate-200">
                                <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" type="text" value="{{ __('OrfinexPrime-MT5') }}" id="copyServer" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200" data-target="copyServer">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Stats') }}
        </h4>
    </div>
    <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-5">
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Balance') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$34,643') }}
                        <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Profit/Loss') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$88,000') }}
                        <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Drawdown') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$7,123') }}
                        <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Trading Days') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('10') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Trading Objective') }}
        </h4>
        <p class="text-xl capitalize text-slate-900" id="timer">
            {{ __('Refreshing in 05:00') }}
        </p>
    </div>
    <div class="card p-6 mb-6">
        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span class="inline-flex h-2 w-2 bg-success-500 rounded-full"></span>
                        <span class="text-success-500 text-sm">{{ __('Ongoing') }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Daily Loss Limit') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Max Loss Limit:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ __('$5,000')  }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Today’s Permitted Loss:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ __('$25,000') }}
                        </span>
                    </li>
                </ul>
            </div>
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span class="inline-flex h-2 w-2 bg-success-500 rounded-full"></span>
                        <span class="text-success-500 text-sm">{{ __('Ongoing') }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Overall Loss Limit') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Max Loss Limit:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ __('$5,000') }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Today’s Permitted Loss:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ __('$25,000') }}
                        </span>
                    </li>
                </ul>
            </div>
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span class="inline-flex h-2 w-2 bg-slate-400 rounded-full"></span>
                        <span class="text-slate-600 text-sm">{{ __('Passed') }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Minimum Trading Days') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Max Loss Limit:') }}</span>
                        <span class="text-slate-900 font-medium">{{ __('$5000') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Today’s Permitted Loss:') }}</span>
                        <span class="text-slate-900 font-medium">{{ __('$25,000') }}</span>
                    </li>
                </ul>
            </div>
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span class="inline-flex h-2 w-2 bg-slate-400 rounded-full"></span>
                        <span class="text-slate-600 text-sm">{{ __('Passed') }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Profit Target') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Max Loss Limit:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ __('$5000') }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Today’s Permitted Loss:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ __('$25,000') }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Details Stats') }}
        </h4>
    </div>
    <div class="card mb-6">
        <div class="card-body p-6">
            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Total Trades') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('75') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Profit Trades') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('12') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Loss Trades') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('23') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Long Trades') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('1521') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Short Trades') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('24') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Gross Profit') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('$525,000') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Gross Loss') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('$231,000') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Total Net Profit') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('$25,000') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Profit Factor') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('1.6') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Win Rate') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('24.5%') }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Best Profit') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('$231,000') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Biggest Loss') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('-42.3') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Largest Pip Win') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('12.22') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Largest Pip Loss') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('-2.3') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Avg. Profit') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('$12,000') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Avg. Loss') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('$5,000') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Expectancy') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('$231,000') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Avg. Trade Size') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('2.32') }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Avg. Trade Diratopm') }}</span>
                            <span class="text-slate-900 font-medium">
                                {{ __('00:23:22') }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Fund Matrics') }}
        </h4>
    </div>
    <div class="card mb-6">
        <div class="card-body p-6">
            <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5">
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Total Alloted Fund') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$34,643') }}
                        <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Max Draw Down') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$88,000') }}
                        <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Daily Max Draw Down') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$7,123') }}
                        <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Profit Split') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('60/40') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Overall Performance') }}
        </h4>
    </div>
    <div class="card mb-6">
        <div class="card-body p-6">
            <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5">
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Balance') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$0.00') }}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Profit') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$0.00') }}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Growth') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('0%') }}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Days') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('345') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __("Today’s Performance") }}
        </h4>
    </div>
    <div class="card mb-6">
        <div class="card-body p-6">
            <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5">
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Previous Day Balance') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$0.00') }}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Current Equity') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('10,000.00') }}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Today’s Draw Down') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$0') }}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Remaining Draw Down') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ __('$500.00') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
@endsection
