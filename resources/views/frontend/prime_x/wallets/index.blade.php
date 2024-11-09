@extends('frontend::layouts.user')
@section('title')
    {{ __('My Wallet') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">{{ __('Account Details') }}</h4>
    </div>
    <div class="grid md:grid-cols-2 col-span-1 gap-5 mb-6">
        <div class="card h-full p-6 mb-6">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('Payout Wallet') }}
                        </span>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        E-{{ data_get($mainWallet,'wallet_id') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ data_get($mainWallet,'amount') }} {{$currency}}
                    </div>
                </div>
                <div class="flex space-x-2 items-center">
                    <a href="{{route('user.withdraw.view')}}" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mingcute:refund-dollar-line"></iconify-icon>
                            <span>{{ __('Withdraw') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card h-full p-6 mb-6">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('affiliate Wallet') }}
                        </span>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        IB-{{ data_get($ibWallet,'wallet_id') }}

                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ data_get($ibWallet,'amount') }} {{$currency}}

                    </div>
                </div>
                <div class="flex space-x-2 items-center">
                    <a href="{{route('user.withdraw.view')}}" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mingcute:refund-dollar-line"></iconify-icon>
                            <span>{{ __('Withdraw') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">{{ __('Recent Transactions') }}</h4>
    </div>
    <div class="card">
        <div class="card-body px-6 pb-6">
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
                                                <img src="{{ asset('frontend/images/logo/BinancePay.svg') }}" alt=""
                                                     class="w-full h-full rounded-[100%] object-cover">
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
                                        <span
                                            class="inline-block px-3 text-center mx-auto py-1 rounded-full bg-warning-500">
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
