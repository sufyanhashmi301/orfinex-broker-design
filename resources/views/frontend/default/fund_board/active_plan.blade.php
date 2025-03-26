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
                                {{ $invest->user->full_name }}
                            </h4>
                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                {{ $invest->pricing_scheme->name }}
                            </div>
                        </div>
                    </div>
                    <ul>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Initial Balance') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ amount_z($invest->total, base_currency()) }}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Plan Type') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ fst2n(data_get($invest->pricing_scheme,'type')) }}
                                </span>
                            </div>
                        </li>
                        <li class="text-sm block py-[8px]">
                            <div class="flex justify-between space-x-2 rtl:space-x-reverse">
                                <span class="text-left text-slate-700">
                                    {{ __('Account Type') }}
                                </span>
                                <span class="text-right text-slate-900">
                                    {{ fsst2n(data_get($invest->pricing_scheme,'sub_type')) }}
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
                                    {{ amount($growthPercentage, base_currency()) }}%
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
                                    <span class="text-slate-900">{{ __('Start Date') }}</span>
                                    <span class="">{{ \Carbon\Carbon::parse($invest->term_start)->format('d M, Y H:i')}}</span>
                                </div>
                                {{--                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400 space-x-3">--}}
                                {{--                                    <span class="text-slate-900">{{ __('End Date') }}</span>--}}
                                {{--                                    <span class="">{{ show_date($invest->term_end, true) }}</span>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" value="{{ data_get($invest, 'login') }}" id="copyLogin" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200" data-target="copyLogin">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" type="password" id="password" value="{{ data_get($invest, 'main_password') }}" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password dark:text-slate-200">
                                <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                            </button>
                        </div>
                        <div class="input-area relative">
                            <input class="form-control !pr-9" type="master_pass" id="master_pass" value="{{ data_get($invest, 'main_password') }}" readonly>
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
                        {{ amount_z($invest->amount_allotted, base_currency()) }}
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
                        {{ amount_z($invest->profit, base_currency()) }}
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
                        {{ amount_z($invest->daily_drawdown_limit, base_currency()) }}
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
                        <span class="text-sm text-success-500">{{ __('+452%') }}</span>
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
    <div class="card p-6 mb-6" id="trading-objective">
        @include('frontend.default.investment.__trading_objective')
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Consistency History') }}
        </h4>
    </div>
    <div class="card mb-6">
        <div class="card-body p-6 pt-3">
            <!-- BEGIN: Company Table -->
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Consistency') }}</th>
                                <th scope="col" class="table-th">{{ __('Current Avg.') }}</th>
                                <th scope="col" class="table-th">{{ __('Overall Avg.') }}</th>
                                <th scope="col" class="table-th">{{ __('Upper Limit') }}</th>
                                <th scope="col" class="table-th">{{ __('Lower Limit') }}</th>
                                <th scope="col" class="table-th">{{ __('Standard Deviation ') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="table-td">{{ __('Trade') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('2') }}</td>
                            </tr>
                            <tr>
                                <td class="table-td">{{ __('Trade') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('2') }}</td>
                            </tr>
                            <tr>
                                <td class="table-td">{{ __('Trade') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('2') }}</td>
                            </tr>
                            <tr>
                                <td class="table-td">{{ __('Lot') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('2') }}</td>
                            </tr>
                            <tr>
                                <td class="table-td">{{ __('Withdrawal') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('N/A') }}</td>
                                <td class="table-td">{{ __('2') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
