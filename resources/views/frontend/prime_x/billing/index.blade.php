@extends('frontend::layouts.user')
@section('title')
    {{ __('Billing') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">
            @yield('title')
        </h4>
    </div>

    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Challenge') }}</th>
                                    <th scope="col" class="table-th">{{ __('Dates') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount to pay') }}</th>
                                    <th scope="col" class="table-th">{{ __('Order') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Invoice') }}</th>
                                    <th scope="col" class="table-th"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">
                                        <div class="text-start">
                                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                {{ __('Beginner') }}
                                            </h4>
                                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                {{ __('TID: TRXUF9CIVDJVN') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <div class="text-start">
                                            <span class="block">{{ __('2024-06-24') }}</span>
                                            <span class="block">{{ __('2024-06-24') }}</span>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <span class="font-semibold">
                                            {{ __('$ 592.15') }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <span class="font-semibold">
                                            {{ __('$ 592.15') }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <span class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                            {{ __('Paid') }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <a href="" class="action-btn">
                                            <iconify-icon icon="heroicons-outline:download"></iconify-icon>
                                        </a>
                                    </td>
                                    <td class="table-td">
                                        <a href="" class="action-btn">
                                            <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
