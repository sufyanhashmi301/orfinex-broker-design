@extends('frontend::layouts.user')

@section('title', __('Active Plan Dashboard'))
@push('style')
    <style>
        #account_credentials_card {
            width: 21rem;
        }
  
        .badge-success {
            background-color: rgba(0, 236, 66, 0.29);
            color: #008133;
        }

        .badge-danger {
            background-color: rgba(193, 65, 65, 0.29);
            color: #C14141;
        }

        .badge {
            display: inline-flex;
            white-space: nowrap;
            border-radius: .358rem;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            vertical-align: baseline;
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 600;
            text-transform: capitalize;
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
                                <img src="@if (auth()->user()->avatar && file_exists('assets/' . auth()->user()->avatar)) {{ asset($user->avatar) }} @else {{ asset('frontend/images/all-img/user.png') }} @endif"
                                    alt="" class="w-full h-full object-cover rounded-full">
                            </div>
                        </div>
                        <div class="flex-1 text-start">
                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                {{ auth()->user()->full_name }}
                            </h4>
                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mt-1">
                                {{ isset($investment->getAccountTypeSnapshotData()['type']) ? ucfirst($investment->getAccountTypeSnapshotData()['type']) . ' Account' : 'N/A' }} | ID: <b>{{ $investment->unique_id }}</b>
                            </div>
                        </div>
                    </div>
                    <ul>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Account Size') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ number_format($investment->getRuleSnapshotData()['allotted_funds'], 2) ?? 0.00 }} {{ base_currency() }}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Plan Details') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ isset($investment->getAccountTypeSnapshotData()['title']) ? ucfirst($investment->getAccountTypeSnapshotData()['title']) : 'N/A' }}
                                    @if ($investment->getPhaseSnapshotData()['phase_step'] == 1)
                                    | {{ number_format($investment->getRuleSnapshotData()['amount'], 2) ?? 0.0 }} {{ base_currency() }}    
                                    @endif
                                    | Phase {{ $investment->getPhaseSnapshotData()['phase_step']}}
                                    
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Account Status') }}
                                </span>
                                @php
                                    $status_btn_style = '';
                                    if ($investment->status == \App\Enums\InvestmentStatus::VIOLATED) {
                                        $status_btn_style = 'btn-danger';
                                    }
                                    if ($investment->status == \App\Enums\InvestmentStatus::ACTIVE) {
                                        $status_btn_style = 'btn-primary';
                                    }
                                    if ($investment->status == \App\Enums\InvestmentStatus::PASSED) {
                                        $status_btn_style = 'btn-success';
                                    }
                                @endphp
                                <button class="text-right text-slate-900 btn btn-sm {{ $status_btn_style }}">

                                       {{ $investment->is_trial == 1 ? 'Trial ' : '' }} {{ $investment->status }}
                                  
                                </button>
                            </div>
                        </li>
                        @if ($investment->is_trial == 1)
                            <li class="text-sm block py-[8px]">
                                <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                    <span class="text-left text-slate-700">
                                        Trial Expiry Date
                                    </span>
                                    <span class="text-right text-slate-900">
                                        {{ date('jS F, Y', strtotime($investment->accountTrial->trial_expiry_at)) }}
                                    </span>
                                </div>
                            </li>
                        @endif
                        @if ($investment->status == \App\Enums\InvestmentStatus::VIOLATED)
                            <li class="text-sm block py-[8px]">
                                <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                    <span class="text-left text-slate-700">
                                        {{ __('Violation Reason') }}
                                    </span>
                                    <span class="text-right text-slate-900 " style="text-transform: capitalize">
                                        {{ str_replace('_', ' ', $investment->violation_reason ?? 'N/A') }}
                                    </span>
                                </div>
                            </li> 
                        @endif
                        
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
                                <div
                                    class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                                    <iconify-icon class="text-2xl" icon="lucide:calendar-check-2"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-1 text-start">
                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                    <span class="text-slate-900 font-medium">{{ __('Phase Start Date') }}</span>
                                </h4>
                                <div class="text-xs mt-1 font-normal text-slate-600 dark:text-slate-400 space-x-3 mb-1">
                                    <span class="">{{ date('h:i A | jS F, Y', strtotime($investment->phase_started_at)) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center mb-5">
                            <div class="flex-none">
                                <div
                                    class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                                    <iconify-icon class="text-2xl" icon="lucide:calendar-minus"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-1 text-start">
                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                    <span class="text-slate-900 font-medium">{{ __('Phase End Date') }}</span>
                                </h4>
                                <div class="text-xs mt-1 font-normal text-slate-600 dark:text-slate-400 space-x-3 mb-1">
                                    @if ($investment->phase_ended_at == null)
                                        -
                                    @else
                                        <span class="">{{ date('h:i A | jS F, Y', strtotime($investment->phase_ended_at)) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="input-area relative">
                            <input class="form-control !pr-9"
                                value="{{ isset($investment->login) ? $investment->login : '0.00' }}" id="copyLogin"
                                readonly>
                            <button
                                class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200"
                                data-target="copyLogin">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" type="text"
                                value="{{ $investment->getPhaseSnapshotData()['server'] }}" id="copyServer" readonly>
                            <button
                                class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200"
                                data-target="copyServer">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <button type="button" class="w-full btn btn-light inline-flex items-center justify-center"
                                data-bs-toggle="modal" data-bs-target="#loginCredentialsModal">
                                {{ __('Login Credentials') }}
                            </button>
                        </div>
                        <div class="input-area relative">
                            <button type="button" class="w-full btn btn-light inline-flex items-center justify-center"
                                data-bs-toggle="modal" data-bs-target="#tradingPlatformsModal">
                                {{ __('Trading Platform') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4
            class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Stats') }}
        </h4>
    </div>
    <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-5">
        @if ($trading_objectives['payout_pending'] != 0)
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="flex-none">
                        <div
                            class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                            <iconify-icon class="text-2xl" icon="lucide:lock"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1 text-start">
                        <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                            {{ __('Locked Balance') }}
                        </div>
                        <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                            {{ number_format($trading_objectives['payout_pending'], 2) }} {{ base_currency() }}
                        </h4>
                    </div>
                </div>
            </div>
        @endif
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div
                        class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="lucide:wallet-minimal"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Balance') }}
                    </div>
                    @php
                        $current_balance = $investment->accountTypeInvestmentStat->balance ?? '0.00';
                    @endphp
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ number_format($current_balance - $trading_objectives['payout_pending'], 2) }} {{ base_currency() }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div
                        class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="lucide:piggy-bank"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Equity') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ number_format($investment->accountTypeInvestmentStat->current_equity - $trading_objectives['payout_pending'], 2) ?? '0.00' }}
                        {{ base_currency() }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div
                        class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="lucide:chart-candlestick"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Total PnL') }}
                    </div>
                    @if ($trading_objectives['total_pnl'] < 0)
                        <h4 class="text-base font-medium badge badge-danger whitespace-nowrap" style="font-size: 16px">{{ number_format(($trading_objectives['total_pnl']), 2) }}
                            {{ base_currency() }}</h4>
                    @else
                        <h4 class="text-base font-medium badge badge-success whitespace-nowrap" style="font-size: 16px">{{ number_format(($trading_objectives['total_pnl']), 2) }}
                            {{ base_currency() }}</h4>
                    @endif
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div
                        class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="lucide:equal-not"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Floating Profit') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ number_format($investment->accountTypeInvestmentStat->current_equity - $investment->accountTypeInvestmentStat->balance, 2) ?? '0.00' }}
                        {{ base_currency() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Trading Objectives --}}
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4
            class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Trading Objective') }}
        </h4>
        <p class="text-xl capitalize text-slate-900" id="timer">
            {{ __('Refreshing in 02:00') }}
        </p>
    </div>
    <div class="card p-6 mb-6">
        @if (
                $trading_objectives['daily_drawdown_status'] != 'violated' &&
                $trading_objectives['max_drawdown_status'] != 'violated' &&
                $trading_objectives['profit_target_status'] == 'passed' &&
                $trading_objectives['minimum_trading_days_status'] == 'passed')
            <div class="pb-2 ">
                    @if (!$kyc_verified)
                        <b><span class="text-sm"> 
                            <iconify-icon icon="lucide:message-circle-warning" style="font-size: 18px; position: relative; top: 3px" class="mr-1"></iconify-icon> 
                            Complete KYC Verification to get next phase account access.
                        </span></b>
                    @else
                    @if($current_balance != $investment->accountTypeInvestmentStat->current_equity) 
                        <b><span class="text-sm"> 
                            @if ($investment->getPhaseSnapshotData()['type'] != \App\Enums\AccountTypePhase::FUNDED)
                                <iconify-icon icon="lucide:info" style="position: relative; top:1px"></iconify-icon> 
                                Close all active trades to get promoted to next phase!
                            @endif
                            @if ($investment->getPhaseSnapshotData()['type'] == \App\Enums\AccountTypePhase::FUNDED)
                                <iconify-icon icon="lucide:info" style="position: relative; top:1px"></iconify-icon> 
                                Close all active trades to create a payout request!
                            @endif
                        </span></b>
                    @endif
                    
                @endif

            </div>
        @endif
        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">

            {{-- Daily DrawDown --}}
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span
                            class="inline-flex h-2 w-2 {{ $trading_objectives['daily_drawdown_status'] == 'violated' ? 'bg-danger-500' : 'bg-slate-500' }} rounded-full"></span>
                        <span
                            class="{{ $trading_objectives['daily_drawdown_status'] == 'violated' ? 'text-danger-500' : 'text-slate-500' }}  text-sm"
                            style="text-transform: capitalize">{{ $trading_objectives['daily_drawdown_status'] }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Daily Drawdown') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    {{-- <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Max Loss Limit') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ number_format($investment->getRuleSnapshotData()['daily_drawdown_limit'], 2) }}
                            {{ base_currency() }}
                        </span>
                    </li> --}}
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Today’s PnL') }}</span>
                        <span class="text-slate-900 font-medium badge {{ $trading_objectives['daily_drawdown_pnl'] < 0 ? 'badge-danger' : 'badge-success' }}">
                            {{ number_format( ($trading_objectives['daily_drawdown_pnl']) , 2) }} {{ base_currency() }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Remaining Loss Limit') }}</span>
                        <span class="text-slate-900 font-medium">
                            @if($trading_objectives['daily_drawdown_remaining_loss_limit'] == "Limit Over")
                                Limit Over
                            @else
                                {{ number_format($trading_objectives['daily_drawdown_remaining_loss_limit'] ?? 0.00, 2) }} {{ base_currency() }}
                            @endif
                            
                        </span>
                    </li>
                </ul>
            </div>

            {{-- Maximum DrawDown --}}
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span
                            class="inline-flex h-2 w-2 {{ $trading_objectives['max_drawdown_status'] == 'violated' ? 'bg-danger-500' : 'bg-slate-500' }} rounded-full"></span>
                        <span
                            class="{{ $trading_objectives['max_drawdown_status'] == 'violated' ? 'text-danger-500' : 'text-slate-500' }}  text-sm"
                            style="text-transform: capitalize">{{ $trading_objectives['max_drawdown_status'] }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Maximum Drawdown') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    {{-- <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Loss Limit') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ number_format($investment->getRuleSnapshotData()['max_drawdown_limit'], 2) ?? '0.00' }}
                            {{ base_currency() }}
                        </span>
                    </li> --}}
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        
                        {{-- @php
                            $max_dd = $trading_objectives['max_drawdown_loss'];
                            $max_dd_badge = 'badge-danger';
                            if ($trading_objectives['current_profit_target'] >= 0){
                                $max_dd = $trading_objectives['max_drawdown_loss'] + $trading_objectives['current_profit_target'];
                                $max_dd_badge = 'badge-success';
                            }
                        @endphp --}}
                        <span>{{ __('Overall PnL') }}</span>
                        <span class="text-slate-900 font-medium badge {{ $trading_objectives['max_drawdown_pnl'] < 0 ? 'badge-danger' : 'badge-success' }}">

                            {{ number_format( ($trading_objectives['max_drawdown_pnl']) , 2) }} {{ base_currency() }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Remaining Loss Limit') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{-- {{ $trading_objectives['max_drawdown_remaining_loss_limit'] }} --}}
                            @if($trading_objectives['max_drawdown_remaining_loss_limit'] == "Limit Over")
                                Limit Over
                            @else
                                {{ number_format($trading_objectives['max_drawdown_remaining_loss_limit'], 2) }} {{ base_currency() }}
                            @endif

                        </span>
                    </li>
                </ul>
            </div>

            {{-- Profit Target --}}
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span
                            class="inline-flex h-2 w-2 {{ $trading_objectives['profit_target_status'] == 'passed' ? 'bg-success-500' : 'bg-slate-500' }} rounded-full"></span>
                        <span
                            class="{{ $trading_objectives['profit_target_status'] == 'passed' ? 'text-success-600' : 'text-slate-600' }}  text-sm"
                            style="text-transform: capitalize">{{ $trading_objectives['profit_target_status'] }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Profit Target') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    {{-- <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Max Profit Target') }}</span>
                        <span class="text-slate-900 font-medium">

                            {{ number_format($trading_objectives['profit_target'], 2) ?? 0.0 }} {{ base_currency() }}
                        </span>
                    </li> --}}
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Achieved PnL') }}</span>
                        <span class="text-slate-900 font-medium badge {{ $trading_objectives['current_profit_target'] < 0 ? 'badge-danger' : 'badge-success' }}">
                            {{ number_format(($trading_objectives['current_profit_target']), 2) ?? '0.00' }}
                            {{ base_currency() }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Remaining Profit Target') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ number_format($trading_objectives['remaining_profit_target'], 2) }} {{ base_currency() }}
                        </span>
                    </li>
                </ul>
            </div>

            {{-- Trading Days --}}
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span
                            class="inline-flex h-2 w-2 {{ $trading_objectives['minimum_trading_days_status'] == 'passing' ? 'bg-slate-400' : 'bg-success-400' }} rounded-full"></span>
                        <span
                            class="{{ $trading_objectives['minimum_trading_days_status'] == 'passing' ? 'text-slate-600' : 'text-success-600' }}  text-sm"
                            style="text-transform: capitalize">{{ $trading_objectives['minimum_trading_days_status'] }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Minimum Trading Days') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Current Trading Days') }}</span>
                        <span
                            class="text-slate-900 font-medium">{{ $investment->accountTypeInvestmentStat->trading_days }}
                        </span>
                    </li>
                    {{-- <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Minimum Trading Days') }}</span>

                        <span class="text-slate-900 font-medium">{{ $trading_objectives['minimum_trading_days'] }}</span>
                    </li> --}}
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Remaining Trading Days') }}</span>
                        <span
                            class="text-slate-900 font-medium">{{ $trading_objectives['remaining_trading_days'] < 0 ? 0 : $trading_objectives['remaining_trading_days'] }}</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Open trade positions details --}}
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4
            class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Open Trades Positions') }}
        </h4>
        <p class="text-xl capitalize text-slate-900">
            Automatically Updates Hourly
        </p>
    </div>
    <div class="card mb-6">

        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="positions-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">#</th>
                                    <th scope="col" class="table-th">Symbol</th>
                                    <th scope="col" class="table-th">Price Open</th>
                                    <th scope="col" class="table-th">Price Current</th>
                                    <th scope="col" class="table-th">Profit/Loss (+/- 1hr)</th>
                                    <th scope="col" class="table-th">Trade Opened at</th>
                                </tr>
                            </thead>
                            <tbody id="open-trades-tbody">
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($account_open_positions as $item)
                                    
                                    @php
                                        if($investment->login != $item['login']) {
                                            continue;
                                        } else {
                                            $i++;
                                        }
                                    @endphp

                                    <tr class="item-row open-trades-row" data-trade-status="{{ $item['profit'] > 0 ? 'profit' : 'loss' }}">
                                        <td class="table-td">{{ $i }}</td>
                                        <td class="table-td">{{ $item['login'] }}</td>
                                        <td class="table-td">{{ $item['symbol'] }}</td>
                                        <td class="table-td">{{ $item['priceOpen'] }}</td>
                                        <td class="table-td">{{ $item['priceCurrent'] }}</td>
                                        <td class="table-td"> <span class="badge badge-{{ $item['profit'] < 0 ? 'danger' : 'success' }}">{{ $item['profit'] < 0 ? $item['profit'] * -1 : $item['profit'] }} {{ $currency }}</span> </td>
                                        <td class="table-td">{{ \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $item['positionCreateTime'])->format('h:i:s A, d M Y') }}</td>
                                    </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="processingIndicator" class="text-center hidden">
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100"
                    icon="lucide:loader"></iconify-icon>
            </div>
        </div>
            
    </div>

    

    @include('frontend::account.includes.__login_credentials')

    @include('frontend::account.includes.__trading_platform')

@endsection
@section('script')
    <script !src="">


        

        function copyText() {
            $('.copy-button').on('click', function() {
                const targetId = $(this).data('target');
                const inputField = $('#' + targetId);
                const icon = $(this).find('iconify-icon');

                inputField.select(); // Select the input field

                // Use the Clipboard API if supported
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(inputField.val()).then(function() {
                        changeIconColor(icon); // Change icon color
                    }).catch(function(err) {
                        console.error('Error copying text: ', err);
                    });
                } else {
                    // Fallback for older browsers
                    document.execCommand('copy');
                    changeIconColor(icon); // Change icon color
                }
            });

            function changeIconColor(icon) {
                const originalColor = icon.style.color; // Store original color
                icon.style.color = 'green'; // Change to desired color
                setTimeout(() => {
                    icon.style.color = originalColor; // Revert back after 500ms
                }, 500);
            }
        }
        copyText();

        $(document).ready(function() {

            if($('.open-trades-row').length == 0) {
                $('#open-trades-tbody').append(
                    `
                    <tr>
                        <td colspan="8" style="padding: 10px"> <center><small>No Data Available!</small></center> </td>
                    </tr>
                    `
                )
            }

            let countdownTime = 120; // 5 minutes in seconds
            const timerElement = $('#timer');

            function startCountdown() {
                const timerInterval = setInterval(function() {
                    let minutes = Math.floor(countdownTime / 60);
                    let seconds = countdownTime % 60;

                    // Format time with leading zeros
                    let formattedTime =
                        (minutes < 10 ? "0" : "") + minutes + ":" +
                        (seconds < 10 ? "0" : "") + seconds;

                    timerElement.text(`Refreshing in ${formattedTime}`);

                    if (countdownTime <= 0) {
                        clearInterval(timerInterval);
                        countdownTime = 120; // Reset countdown
                        startCountdown(); // Restart the countdown
                    }

                    countdownTime--;
                }, 1000);
            }

            startCountdown(); // Start the countdown on page load
        });

        $('#loginCredentialsModal').on('shown.bs.modal', function() {
            copyText();
        });
    </script>
@endsection
