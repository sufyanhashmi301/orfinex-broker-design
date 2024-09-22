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
                                {{ auth()->user()->full_name }}
                            </h4>
                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                {{ data_get($invest->forexSchemaPhaseRule->forexSchemaPhase,'funded_type') }}
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
                                    {{ $invest->amount}} {{base_currency()}}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Plan Type:') }}
                                </span>
                                <span class="text-right text-slate-900">
                                     {{ ucfirst(data_get($invest->forexSchemaPhaseRule->forexSchemaPhase->forexSchema,'title')) }} | {{ $invest->amount }} {{base_currency()}}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Account Type:') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ ucfirst(data_get($invest,'account_type')) }}
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
                                    <span class="">{{data_get($invest,'term_start')}}</span>
                                </div>
{{--                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 space-x-3">--}}
{{--                                    <span class="text-slate-900 font-medium">{{ __('End Date:') }}</span>--}}
{{--                                    <span class="">{{ __('Dec 5, 2022') }}</span>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" value="{{data_get($invest,'login')}}" id="copyLogin" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200" data-target="copyLogin">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" type="password" id="password" value="{{data_get($invest,'main_password')}}" readonly>
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
                            <input class="form-control !pr-9" type="text" value="{{data_get($invest->forexSchemaPhaseRule->forexSchemaPhase,'server')}}" id="copyServer" readonly>
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
                        {{ $invest->current_balance}} {{base_currency()}}
                        <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-
3">
<iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
</div>
</div>
<div class="flex-1 text-start">
    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
        {{ __('Profit/Loss') }}
    </div>
    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
        {{ $invest->profit }} {{base_currency()}}
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
                {{ $invest->max_drawdown_limit }} {{base_currency()}}
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
                {{ \Carbon\Carbon::parse($invest->term_start)->diffInDays(\Carbon\Carbon::now()) }}
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
                            {{ $invest->daily_drawdown_limit }} {{base_currency()}}
                        </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>{{ __('Today’s Permitted Loss:') }}</span>
                    <span class="text-slate-900 font-medium">
                            {{ $invest->max_drawdown_limit}} {{base_currency()}}
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
                            {{ $invest->max_drawdown_limit }} {{base_currency()}}
                        </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>{{ __('Today’s Permitted Loss:') }}</span>
                    <span class="text-slate-900 font-medium">
                            {{ $invest->max_drawdown_limit}} {{base_currency()}}
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
                    <span class="text-slate-900 font-medium">{{ $invest->max_drawdown_limit }} {{base_currency()}}</span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>{{ __('Today’s Permitted Loss:') }}</span>
                    <span class="text-slate-900 font-medium">{{ $invest->max_drawdown_limit }} {{base_currency()}}</span>
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
                            {{ $invest->max_drawdown_limit }} {{base_currency()}}
                        </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>{{ __('Today’s Permitted Loss:') }}</span>
                    <span class="text-slate-900 font-medium">
                            {{ $invest->max_drawdown_limit }} {{base_currency()}}
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
                <h5>Today's Trades</h5>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Trades') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ $todayScore['result']['total_Trades'] ?? 'N/A' }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Profit Trades') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ $todayScore['result']['total_Profit'] ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Loss Trades') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ $todayScore['result']['total_Losses'] ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Highest Profit Trade') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['highest_Profit_Trade'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Highest Loss Trade') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['highest_Lost_Trade'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Net Profit') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['net_Profit'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Win Rate') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['win_Rate'] * 100, 2) ?? 'N/A' }}%
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Loss Rate') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['loss_Rate'] * 100, 2) ?? 'N/A' }}%
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Average Holding Time') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['avg_Holding_Time'], 2) ?? 'N/A' }} seconds
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Deposits') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['total_Deposits'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Withdrawals') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['total_Withdrawals'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Withdrawal Rate') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['withdrawal_Rate'], 2) ?? 'N/A' }}%
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Risk-Reward Ratio') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['risk_Reward_Ratio'], 2) ?? 'N/A' }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Capital Retention Ratio') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($todayScore['result']['captial_Retention_Ratio'], 2) ?? 'N/A' }}%
            </span>
                    </li>
                </ul>
            </div>

            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <h5>Weekly Trades</h5>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Best Profit (Highest Profit Trade)') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['highest_Profit_Trade'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Biggest Loss (Highest Lost Trade)') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['highest_Lost_Trade'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Trades') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ $weeklyScore['result']['total_Trades'] ?? 'N/A' }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Profit') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['total_Profit'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Losses') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['total_Losses'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('PnL Ratio') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['pnL_Ratio'], 2) ?? 'N/A' }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Avg. Trade Profit per Loss') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['avg_Trade_Profit_Per_Loss'], 2) ?? 'N/A' }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Win Rate') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['win_Rate'] * 100, 2) ?? 'N/A' }}%
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Loss Rate') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['loss_Rate'] * 100, 2) ?? 'N/A' }}%
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Average Holding Time') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['avg_Holding_Time'], 2) ?? 'N/A' }} seconds
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Deposits') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['total_Deposits'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Withdrawals') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['total_Withdrawals'], 2) ?? 'N/A' }} {{ base_currency() }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Withdrawal Rate') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['withdrawal_Rate'], 2) ?? 'N/A' }}%
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Risk-Reward Ratio') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['risk_Reward_Ratio'], 2) ?? 'N/A' }}
            </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Capital Retention Ratio') }}</span>
                        <span class="text-slate-900 font-medium">
                {{ number_format($weeklyScore['result']['captial_Retention_Ratio'], 2) ?? 'N/A' }}%
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
                    {{ __('Total Allotted Fund') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{ $invest->amount_allotted }} {{base_currency()}}
                    <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Max Draw Down') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{ $invest->max_drawdown_limit }} {{base_currency()}}
                    <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Daily Max Draw Down') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{ $invest->daily_drawdown_limit }} {{base_currency()}}
                    <span class="text-sm text-success-500">{{ __('+452%') }}</span>
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Profit Split') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{data_get($invest,'profit_share_user')}} / {{data_get($invest,'profit_share_admin')}}
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
                    {{ $invest->max_balance}} {{base_currency()}}
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Profit') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{ $invest->profit}} {{base_currency()}}
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Growth') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{$growthPercentage}}%
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Days') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{\Carbon\Carbon::parse($invest->term_start)->diffInDays(\Carbon\Carbon::now())}}
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
                    {{$invest->snap_balance}} {{ base_currency()}}
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Current Equity') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{ $invest->current_equity}} {{ base_currency()}}
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Today’s Draw Down') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{ $todayDrawddown }} {{ base_currency()}}
                </h4>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700 p-6">
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                    {{ __('Remaining Draw Down') }}
                </div>
                <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                    {{ $remainingLoss}} {{base_currency()}}
                </h4>
            </div>
        </div>
    </div>
</div>

@endsection
