@extends('frontend::layouts.user')
@section('title')
    {{ __('Certificates') }}
@endsection
@section('content')
    <div class="card mb-6">
        <div class="card-body p-6">
            <h4 class="card-title mb-2">
                {{ __('Leaderboard') }}
            </h4>
            <p class="card-text">
                {{ __('Overview of currently most profitable active :siteTitle Accounts.', ['siteTitle' => setting('site_title', 'global')]) }}
            </p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <img src="{{ asset('frontend/images/highest-payout__badge.png') }}" alt="">
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            {{ __('Highest Payout') }}
                        </div>
                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                            {{ __('Jagroop D') }}
                        </div>
                    </div>
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('$100,000') }}</span>
                        <span>
                            <span class="text-lg font-medium">{{ __('$123,00.23') }}</span>
                            <span class="text-success-500 ml-1">{{ __('+123%') }}</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <img src="{{ asset('frontend/images/best-ratio__badge.png') }}" alt="">
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            {{ __('Best Ratio') }}
                        </div>
                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                            {{ __('Diego C') }}
                        </div>
                    </div>
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('$5,000') }}</span>
                        <span class="text-lg font-medium">{{ __('100%') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <img src="{{ asset('frontend/images/fastest-evalution__badge.png') }}" alt="">
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            {{ __('Fastest Evalution') }}
                        </div>
                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                            {{ __('Mayank S') }}
                        </div>
                    </div>
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('$100,000') }}</span>
                        <span class="text-lg font-medium">{{ __('1d 0h 1m 10s') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1">{{ __('Kai S') }}</span>
                    <svg width="25" height="16" viewBox="0 0 25 16" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect y="0.5" width="25" height="15" rx="2" fill="url(#pattern0_1586_17403)"/>
                        <defs>
                            <pattern id="pattern0_1586_17403" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_1586_17403" transform="matrix(0.00576923 0 0 0.00961538 -0.0769231 0)"/>
                            </pattern>
                            <image id="image0_1586_17403" width="200" height="104" xlink:href="data:image/png;base64,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"/>
                        </defs>
                    </svg>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-1__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge bg-success-500 text-white capitalize">{{ __('Buy') }}</span>
                        <span class="text-base">{{ __('$100,000') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('XAUUSD') }}</span>
                        <span class="text-base text-success-500">{{ __('$12,000') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1">{{ __('Adib Z') }}</span>
                    <svg width="25" height="16" viewBox="0 0 25 16" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect y="0.5" width="25" height="15" rx="2" fill="url(#pattern0_1586_17403)"/>
                        <defs>
                            <pattern id="pattern0_1586_17403" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_1586_17403" transform="matrix(0.00576923 0 0 0.00961538 -0.0769231 0)"/>
                            </pattern>
                            <image id="image0_1586_17403" width="200" height="104" xlink:href="data:image/png;base64,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"/>
                        </defs>
                    </svg>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-2__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge bg-success-500 text-white capitalize">{{ __('Buy') }}</span>
                        <span class="text-base">{{ __('$100,000') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('XAUUSD') }}</span>
                        <span class="text-base text-success-500">{{ __('$12,000') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1">{{ __('Ranger A') }}</span>
                    <svg width="25" height="16" viewBox="0 0 25 16" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect y="0.5" width="25" height="15" rx="2" fill="url(#pattern0_1586_17403)"/>
                        <defs>
                            <pattern id="pattern0_1586_17403" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_1586_17403" transform="matrix(0.00576923 0 0 0.00961538 -0.0769231 0)"/>
                            </pattern>
                            <image id="image0_1586_17403" width="200" height="104" xlink:href="data:image/png;base64,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"/>
                        </defs>
                    </svg>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-3__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge bg-danger-500 text-white capitalize">{{ __('Sell') }}</span>
                        <span class="text-base">{{ __('$100,000') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('XAUUSD') }}</span>
                        <span class="text-base text-success-500">{{ __('$12,000') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between mb-3">
        <h4 class="text-lg text-slate-600 dark:text-white font-semibold">
            {{ __('Best account in profit') }}
        </h4>
        <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary active">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    10k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    25k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    50k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    100k
                </a>
            </li>
        </ul>
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
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account size') }}</th>
                                    <th scope="col" class="table-th">{{ __('Gain %') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend::utilities.__comingSoon_modal')
@endsection
@section('script')
    <script>
        $( document ).ready(function() {
            $('#comingSoonModal').modal('show');
        });
    </script>
@endsection
