@extends('frontend::layouts.user')
@section('title')
    {{ __('My Wallet') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">{{ __('Account Details') }}</h4>
    </div>
    <div class="grid md:grid-cols-3 col-span-1 gap-5 mb-6">
        <div class="card h-full p-6 mb-6">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('USD') }}
                        </span>
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('Standard') }}
                        </span>
                    </div>
                    <div class="dropdown relative">
                        <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                <iconify-icon icon="bi:three-dots-vertical"></iconify-icon>
                            </span>
                        </button>
                        <ul class=" dropdown-menu w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                            <li>
                                <a href="" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-account-info">
                                    {{ __('Submenu') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('43623422') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ __('$34,643') }}
                    </div>
                </div>
                <div class="flex space-x-2 items-center">
                    <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="hugeicons:credit-card-pos"></iconify-icon>
                            <span>{{ __('Deposit') }}</span>
                        </span>
                    </button>
                    <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mingcute:refund-dollar-line"></iconify-icon>
                            <span>{{ __('Withdraw') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card h-full p-6 mb-6">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('USD') }}
                        </span>
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('Standard') }}
                        </span>
                    </div>
                    <div class="dropdown relative">
                        <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                <iconify-icon icon="bi:three-dots-vertical"></iconify-icon>
                            </span>
                        </button>
                        <ul class=" dropdown-menu w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                            <li>
                                <a href="" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-account-info">
                                    {{ __('Submenu') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('43623422') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ __('$34,643') }}
                    </div>
                </div>
                <div class="flex space-x-2 items-center">
                    <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="hugeicons:credit-card-pos"></iconify-icon>
                            <span>{{ __('Deposit') }}</span>
                        </span>
                    </button>
                    <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mingcute:refund-dollar-line"></iconify-icon>
                            <span>{{ __('Withdraw') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card border border-dashed h-full border-slate-200 dark:border-slate-700 p-6 mb-6">
            <div class="card-body h-full flex flex-col items-center justify-center gap-5">
                <iconify-icon class="text-2xl" icon="ic:outline-dashboard-customize"></iconify-icon>
                <a href="" class="btn-link">
                    {{ __('Open Additional Acocunt') }}
                </a>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">{{ __('Recent Transactions') }}</h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account') }}</th>
                                    <th scope="col" class="table-th">{{ __('Wallet') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">{{ __('Withdrawal') }}</td>
                                    <td class="table-td">{{ __('3252362465') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/logo/BinancePay.svg') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm text-slate-600 whitespace-nowrap">
                                                    {{ __('BinancePay') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <span class="inline-block px-3 text-center mx-auto py-1 rounded-full bg-warning-500">
                                            {{ __('Done') }}
                                        </span>
                                    </td>
                                    <td class="table-td">{{ __('1.5%') }}</td>
                                    <td class="table-td">{{ __('10,000 USD') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
