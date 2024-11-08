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
                                {{ isset($investment->forexSchemaPhaseRule->forexSchemaPhase->funded_type) ? $investment->forexSchemaPhaseRule->forexSchemaPhase->funded_type : '0.00' }}
                            </div>
                        </div>
                    </div>
                    <ul>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Account Size:') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ $investment_snapshot->account_types_phases_rules_data['allotted_funds'] ?? 0.00 }} {{base_currency()}}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Plan Type:') }}
                                </span>
                                <span class="text-right text-slate-900">
                                     {{ isset($investment_snapshot->account_types_data['title']) ? ucfirst($investment_snapshot->account_types_data['title']) : '0.00' }} | {{ $investment_snapshot->account_types_phases_rules_data['amount'] ?? 0.00 }} {{base_currency()}}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Account Status:') }}
                                </span>
                                <button class="text-right text-slate-900 btn btn-sm btn-primary">
                                    {{ $investment->status }}
                                </button>
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
                                    {{ __('N/A') }}
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
                                    <span class="">{{ $investment->phase_started_at }}</span>
                                </div>
                                {{--                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 space-x-3">--}}
                                {{--                                    <span class="text-slate-900 font-medium">{{ __('End Date:') }}</span>--}}
                                {{--                                    <span class="">{{ __('Dec 5, 2022') }}</span>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" value="{{ isset($investment->login) ? $investment->login : '0.00' }}" id="copyLogin" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200" data-target="copyLogin">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" type="text" value="{{ $investment_snapshot->account_types_phases_data['server'] }}" id="copyServer" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200" data-target="copyServer">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <button type="button" class="w-full btn btn-light inline-flex items-center justify-center" data-bs-toggle="modal" data-bs-target="#loginCredentialsModal">
                                {{ __('Login Credentials') }}
                            </button>
                        </div>
                        <div class="input-area relative">
                            <button type="button" class="w-full btn btn-light inline-flex items-center justify-center" data-bs-toggle="modal" data-bs-target="#tradingPlatformsModal">
                                {{ __('Trading Platform') }}
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
    <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5 mb-5">
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
                    @php
                        $current_balance = $investment->accountTypeInvestmentStat->balance ?? '0.00';
                    @endphp
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ $current_balance }} {{base_currency()}}
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
                        {{ __('Equity') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ $investment->accountTypeInvestmentStat->current_equity ?? '0.00' }} {{base_currency()}}
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
                    @php
                        $profit = $current_balance - ( $investment_snapshot->account_types_phases_rules_data['allotted_funds'] );
                        $profit = number_format($profit, 2);

                    @endphp
                    @if ($profit < 0)
                        <h4 class="text-base font-medium text-danger-500 whitespace-nowrap">{{ $profit }} {{ base_currency() }}</h4>
                    @else
                        <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">{{ $profit }} {{ base_currency() }}</h4>
                    @endif
                </div>
            </div>
        </div>
        {{-- <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Floating Profit') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ isset($totalBalance['result']['floating']) ? $totalBalance['result']['floating'] : '0.00' }} {{base_currency()}}
                    </h4>
                </div>
            </div>
        </div> --}}
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

            {{-- Daily DrawDown --}}
            @php
                $ddd_status = 'passing';
                
                $ddd_loss = ($investment->accountTypeInvestmentStat->current_equity - $first_record_after_midnight->current_equity) * -1;
                if($ddd_loss <= 0){
                    $ddd_loss = 0;
                }
                

                $remaining_loss_limit = ($investment_snapshot->account_types_phases_rules_data['daily_drawdown_limit'] - $ddd_loss);
                if($remaining_loss_limit < 0){
                    $remaining_loss_limit = 'Limit Over';
                    $ddd_status = 'violated';
                }else{
                    $remaining_loss_limit = number_format($remaining_loss_limit, 2) . ' ' . base_currency();
                }
            @endphp
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span class="inline-flex h-2 w-2 {{ $ddd_status == 'violated' ? 'bg-danger-500' : 'bg-slate-500' }} rounded-full"></span>
                        <span class="{{ $ddd_status == 'violated' ? 'text-danger-500' : 'text-slate-500' }}  text-sm" style="text-transform: capitalize">{{ $ddd_status }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Daily Draw Down') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Max Loss Limit:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ number_format( $investment_snapshot->account_types_phases_rules_data['daily_drawdown_limit'], 2 )  }} {{ base_currency() }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Today’s Loss:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ number_format( $ddd_loss, 2 )  }} {{ base_currency() }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Remaining Loss Limit:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ $remaining_loss_limit  }} 
                        </span>
                    </li>
                </ul>
            </div>

            {{-- Maximum DrawDown --}}
            @php
                $mdd_status = 'passing';
                $mdd_loss =  ($investment->accountTypeInvestmentStat->current_equity - $investment_snapshot->account_types_phases_rules_data['allotted_funds']) * -1;
                if($mdd_loss <= 0){
                    $mdd_loss = 0;
                }
                $remaining_overall_loss_limit = ($investment_snapshot->account_types_phases_rules_data['max_drawdown_limit'] - $mdd_loss);
                if($remaining_overall_loss_limit <= 0){
                    $remaining_overall_loss_limit = 'Limit Over';
                    $mdd_status = 'violated';
                }else{
                    $remaining_overall_loss_limit = number_format($remaining_overall_loss_limit, 2) . ' ' . base_currency();
                }

            @endphp
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span class="inline-flex h-2 w-2 {{ $mdd_status == 'violated' ? 'bg-danger-500' : 'bg-slate-500' }} rounded-full"></span>
                        <span class="{{ $mdd_status == 'violated' ? 'text-danger-500' : 'text-slate-500' }}  text-sm" style="text-transform: capitalize">{{ $mdd_status }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Maximum Draw Down') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Total Loss Limit:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ number_format($investment_snapshot->account_types_phases_rules_data['max_drawdown_limit'], 2) ?? '0.00' }} {{base_currency()}}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Overall Loss:') }}</span>
                        <span class="text-slate-900 font-medium">
                            
                            {{ number_format($mdd_loss, 2)  }} {{ base_currency() }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Remaining Loss Limit:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ $remaining_overall_loss_limit }} 
                        </span>
                    </li>
                </ul>
            </div>
            
            {{-- Profit Target --}}
            @php
                $pt_status = 'passing';
                $profit_target = $investment_snapshot->account_types_phases_rules_data['profit_target'];

                // Achievied Profit
                $current_pt = $investment->accountTypeInvestmentStat->current_equity - ($investment_snapshot->account_types_phases_rules_data['allotted_funds']);
                if($current_pt < 0) {
                    $current_pt = 0;
                }

                if($current_pt >= $profit_target){
                    $pt_status = 'passed';
                }

                // remaining profit target
                $remaining_profit_target = $profit_target - $current_pt;
                if( ($profit_target - $current_pt) < 0 ) {
                    $remaining_profit_target = 0;
                }
            @endphp
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span class="inline-flex h-2 w-2 {{ $pt_status == 'passed' ? 'bg-success-500' : 'bg-slate-500' }} rounded-full"></span>
                        <span class="{{ $pt_status == 'passed' ? 'text-success-600' : 'text-slate-600' }}  text-sm" style="text-transform: capitalize">{{ $pt_status }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Profit Target') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Max Profit Target:') }}</span>
                        <span class="text-slate-900 font-medium">
                            
                            {{ number_format($profit_target, 2) ?? 0.00 }} {{base_currency()}}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Achieved Profit:') }}</span>
                        <span class="text-slate-900 font-medium">
                            @php
                                
                            @endphp
                            {{ number_format($current_pt, 2) ?? '0.00' }} {{base_currency()}}
                        </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Remaining Profit Target:') }}</span>
                        <span class="text-slate-900 font-medium">
                            {{ number_format($remaining_profit_target, 2) }} {{base_currency()}}
                        </span>
                    </li>
                </ul>
            </div>

            {{-- Trading Days --}}
            @php
                $mtd_status = 'passing';
                $minimum_trading_days = $investment_snapshot->account_types_data['trading_days'];
                $remaining_trading_days = $minimum_trading_days - $investment->accountTypeInvestmentStat->trading_days;

                if($investment->accountTypeInvestmentStat->trading_days >= $minimum_trading_days){
                    $mtd_status = 'passed';
                }
            @endphp
            <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="mb-5">
                    <span class="flex space-x-2 rtl:space-x-reverse items-center mb-1">
                        <span class="inline-flex h-2 w-2 {{ $mtd_status == 'passing' ? 'bg-slate-400' : 'bg-success-400' }} rounded-full"></span>
                        <span class="{{ $mtd_status == 'passing' ? 'text-slate-600' : 'text-success-600' }}  text-sm" style="text-transform: capitalize">{{ $mtd_status }}</span>
                    </span>
                    <h5 class="text-slate-900 dark:text-slate-300 text-base">
                        {{ __('Minimum Trades') }}
                    </h5>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __("Current Trading Days:") }}</span>
                        <span class="text-slate-900 font-medium">{{ $investment->accountTypeInvestmentStat->trading_days }} </span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Minimum Trading Days:') }}</span>
                        
                        <span class="text-slate-900 font-medium">{{ $minimum_trading_days }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                        <span>{{ __('Remaining Trading Days:') }}</span>
                        <span class="text-slate-900 font-medium">{{ $remaining_trading_days }}</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Detailed Trading Statistics') }}
        </h4>
    </div>
    <div class="card mb-6">
        <div class="card-body p-6">
            <div class="grid md:grid-cols-3 grid-cols-1 gap-5">
                <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">
                    <h5 class="dark:text-white mb-3">{{ __("Today’s Trading Performance") }}</h5>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Total Trades') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['total_Trades']) ? $todayScore['result']['total_Trades']/2 : '0.00' }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Profitable Trades') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['total_Profit']) ? $todayScore['result']['total_Profit'] : '0.00' }} {{ base_currency() }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Losing Trades') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['total_Losses']) ? $todayScore['result']['total_Losses'] : '0.00' }} {{ base_currency() }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Highest Profit on a Trade') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['highest_Profit_Trade']) ? number_format($todayScore['result']['highest_Profit_Trade'], 2) : '0.00' }} {{ base_currency() }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Largest Loss on a Trade') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['highest_Lost_Trade']) ? number_format($todayScore['result']['highest_Lost_Trade'], 2) : '0.00' }} {{ base_currency() }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Total Net Profit') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['net_Profit']) ? number_format($todayScore['result']['net_Profit'], 2) : '0.00' }} {{ base_currency() }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Win Rate') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['win_Rate']) ? number_format($todayScore['result']['win_Rate'] * 100, 2) : '0.00' }}%
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Loss Rate') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['loss_Rate']) ? number_format($todayScore['result']['loss_Rate'] * 100, 2) : '0.00' }}%
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Average Holding Time') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['avg_Holding_Time']) ? number_format(abs($todayScore['result']['avg_Holding_Time']), 2) : '0.00' }} seconds
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Risk-Reward Ratio') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['risk_Reward_Ratio']) ? number_format($todayScore['result']['risk_Reward_Ratio'], 2) : '0.00' }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                            <span>{{ __('Capital Preservation Ratio') }}</span>
                            <span class="text-slate-900 font-medium text-right">
                                {{ isset($todayScore['result']['captial_Retention_Ratio']) ? number_format($todayScore['result']['captial_Retention_Ratio'], 2) : '0.00' }}%
                            </span>
                        </li>
                    </ul>
                </div>

                {{--                <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">--}}
                {{--                    <h5 class="dark:text-white mb-3">{{ __("Last Week’s Trading Summary") }}</h5>--}}
                {{--                    <ul class="space-y-3">--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Total Trades Executed') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ $weeklyScore['result']['total_Trades'] ?? 'N/A' }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Best Trade Profit') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['highest_Profit_Trade'], 2) ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Biggest Trade Loss') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['highest_Lost_Trade'], 2) ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Total Profit') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['total_Profit'], 2) ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Total Losses') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['total_Losses'], 2) ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Profit-Loss Ratio') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['pnL_Ratio'], 2) ?? 'N/A' }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Average Profit per Loss Trade') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['avg_Trade_Profit_Per_Loss'], 2) ?? 'N/A' }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Win Rate') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['win_Rate'] * 100, 2) ?? 'N/A' }}%--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Loss Rate') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['loss_Rate'] * 100, 2) ?? 'N/A' }}%--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Average Holding Time') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['avg_Holding_Time'], 2) ?? 'N/A' }} seconds--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Risk-Reward Ratio') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['risk_Reward_Ratio'], 2) ?? 'N/A' }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Capital Retention Ratio') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($weeklyScore['result']['captial_Retention_Ratio'], 2) ?? 'N/A' }}%--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </div>--}}

                {{--                <div class="border border-slate-100 dark:border-slate-700 p-3 rounded">--}}
                {{--                    <h5 class="dark:text-white mb-3">{{ __('Total Trading Performance') }}</h5>--}}
                {{--                    <ul class="space-y-3">--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Total Trades Executed') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ $totalScore['result']['total_Trades'] ?? 'N/A' }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Total Profitable Trades') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ $totalScore['result']['total_Profit'] ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Total Losing Trades') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ $totalScore['result']['total_Losses'] ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Highest Profit on a Single Trade') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($totalScore['result']['highest_Profit_Trade'], 2) ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Largest Loss on a Single Trade') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($totalScore['result']['highest_Lost_Trade'], 2) ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Cumulative Net Profit') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($totalScore['result']['net_Profit'], 2) ?? 'N/A' }} {{ base_currency() }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Win Rate') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($totalScore['result']['win_Rate'] * 100, 2) ?? 'N/A' }}%--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Loss Rate') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($totalScore['result']['loss_Rate'] * 100, 2) ?? 'N/A' }}%--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Average Holding Time') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format(abs($totalScore['result']['avg_Holding_Time']), 2) ?? 'N/A' }} seconds--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Risk-Reward Ratio') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($totalScore['result']['risk_Reward_Ratio'], 2) ?? 'N/A' }}--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                        <li class="flex items-center justify-between text-sm text-slate-500 gap-2">--}}
                {{--                            <span>{{ __('Capital Retention Ratio') }}</span>--}}
                {{--                            <span class="text-slate-900 font-medium text-right">--}}
                {{--                                {{ number_format($totalScore['result']['captial_Retention_Ratio'], 2) ?? 'N/A' }}%--}}
                {{--                            </span>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </div>--}}

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
                        {{ $investment_snapshot->account_types_phases_rules_data['allotted_funds'] }} {{base_currency()}}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Max Draw Down') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ $investment_snapshot->account_types_phases_rules_data['max_drawdown_limit'] }} {{base_currency()}}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Daily Max Draw Down') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{ $investment_snapshot->account_types_phases_rules_data['daily_drawdown_limit'] }} {{base_currency()}}
                    </h4>
                </div>
                <div class="card border border-slate-100 dark:border-slate-700 p-6">
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Profit Split') }}
                    </div>
                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">
                        {{--                        {{isset($investment->profit_share_user)}} / {{isset($investment->profit_share_admin)}}--}}
                        80 / 20
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{--    <div class="flex justify-between flex-wrap items-center mb-3">--}}
    {{--        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">--}}
    {{--            {{ __('Overall Performance') }}--}}
    {{--        </h4>--}}
    {{--    </div>--}}
    {{--    <div class="card mb-6">--}}
    {{--        <div class="card-body p-6">--}}
    {{--            <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5">--}}
    {{--                <div class="card border border-slate-100 dark:border-slate-700 p-6">--}}
    {{--                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">--}}
    {{--                        {{ __('Balance') }}--}}
    {{--                    </div>--}}
    {{--                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">--}}
    {{--                        {{ isset($investment->max_balance) ? $investment->max_balance : '0.00' }} {{base_currency()}}--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--                <div class="card border border-slate-100 dark:border-slate-700 p-6">--}}
    {{--                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">--}}
    {{--                        {{ __('Profit') }}--}}
    {{--                    </div>--}}
    {{--                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">--}}
    {{--                        {{ isset($investment->profit) ? $investment->profit : '0.00' }} {{base_currency()}}--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--                <div class="card border border-slate-100 dark:border-slate-700 p-6">--}}
    {{--                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">--}}
    {{--                        {{ __('Growth') }}--}}
    {{--                    </div>--}}
    {{--                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">--}}
    {{--                        {{isset($growthPercentage) ? $growthPercentage : '0.00'}}%--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--                <div class="card border border-slate-100 dark:border-slate-700 p-6">--}}
    {{--                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">--}}
    {{--                        {{ __('Days') }}--}}
    {{--                    </div>--}}
    {{--                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">--}}
    {{--                        {{\Carbon\Carbon::parse($investment->term_start)->diffInDays(\Carbon\Carbon::now())}}--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{--    <div class="flex justify-between flex-wrap items-center mb-3">--}}
    {{--        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">--}}
    {{--            {{ __("Today’s Performance") }}--}}
    {{--        </h4>--}}
    {{--    </div>--}}
    {{--    <div class="card mb-6">--}}
    {{--        <div class="card-body p-6">--}}
    {{--            <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5">--}}
    {{--                <div class="card border border-slate-100 dark:border-slate-700 p-6">--}}
    {{--                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">--}}
    {{--                        {{ __('Previous Day Balance') }}--}}
    {{--                    </div>--}}
    {{--                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">--}}
    {{--                        {{ isset($investment->snap_balance) ? $investment->snap_balance : '0.00' }} {{ base_currency()}}--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--                <div class="card border border-slate-100 dark:border-slate-700 p-6">--}}
    {{--                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">--}}
    {{--                        {{ __('Current Equity') }}--}}
    {{--                    </div>--}}
    {{--                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">--}}
    {{--                        {{ isset($investment->current_equity) ? $investment->current_equity : '0.00' }} {{ base_currency()}}--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--                <div class="card border border-slate-100 dark:border-slate-700 p-6">--}}
    {{--                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">--}}
    {{--                        {{ __('Today’s Draw Down') }}--}}
    {{--                    </div>--}}
    {{--                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">--}}
    {{--                        {{ isset($todayDrawddown) ? $todayDrawddown : '0.00' }} {{ base_currency()}}--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--                <div class="card border border-slate-100 dark:border-slate-700 p-6">--}}
    {{--                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400 mb-1">--}}
    {{--                        {{ __('Remaining Draw Down') }}--}}
    {{--                    </div>--}}
    {{--                    <h4 class="text-base font-medium text-slate-600 whitespace-nowrap">--}}
    {{--                        {{ isset($remainingLoss) ? $remainingLoss : '0.00' }} {{base_currency()}}--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    @include('frontend::fund_board.modal.__login_credentials')

    @include('frontend::fund_board.modal.__trading_platform')

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
            let countdownTime = 300; // 5 minutes in seconds
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
                        countdownTime = 300; // Reset countdown
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
