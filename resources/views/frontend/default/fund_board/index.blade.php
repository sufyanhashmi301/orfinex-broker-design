@extends('frontend::layouts.user')
@section('title')
    {{ __('Fund Board') }}
@endsection
@section('content')
    <div class="md:flex justify-between items-center mb-5">
        <div class="">
            <ul class="m-0 p-0 list-none">
                <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                    <a href="{{route('user.dashboard')}}">
                        <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                        <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                    </a>
                </li>
                <li class="inline-block relative text-sm text-primary font-Inter ">
                    Dashboard
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                </li>
                <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                    Fund Board
                </li>
            </ul>
        </div>
        <div class="flex flex-wrap ">
            <a href="" class="btn btn-sm inline-flex justify-center btn-white dark:bg-slate-700 dark:text-slate-300 m-1">
                <span class="flex items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus"></iconify-icon>
                    <span>Deposit Funds</span>
                </span>
            </a>
            <a href="" class="btn btn-sm inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <span class="flex items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:hand-coins-light"></iconify-icon>
                    <span>Get Funded</span>
                </span>
            </a>
        </div>
    </div>

    <div class="grid xl:grid-cols-2 grid-cols-1 gap-5">
        <div class="card">
            <div class="card-body p-6">
                <div class="text-slate-600 dark:text-slate-400 text-sm mb-2 font-medium">
                    Total Funded Balance
                </div>
                <div class="flex items-end flex-nowrap space-x-4">
                    <div class="text-slate-400">
                        <div class="text-2xl font-medium">
                            10,000.00
                            <small class="text-base text-slate-600">USD</small>
                        </div>
                        <div class="text-sm text-slate-500 mt-1">Allotted Fund</div>
                    </div>
                    <div class="text-slate-400">
                        <span class="absolute">
                            <iconify-icon class="text-xl" icon="ph:plus-bold"></iconify-icon>
                        </span>
                        <div class="pl-8">
                            <div class="text-xl font-medium">
                                0.00
                                <small class="text-base text-slate-600">USD</small>
                            </div>
                        </div>
                        <div class="text-sm text-slate-500 pl-8 mt-1">Profit Earned</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <div class="text-slate-600 dark:text-slate-400 text-sm mb-2 font-medium">
                    Profit Share
                </div>
                <div class="flex items-end flex-nowrap space-x-4">
                    <div class="text-slate-400">
                        <div class="text-2xl font-medium">
                            10,000.00
                            <small class="text-base text-slate-600">USD</small>
                        </div>
                        <div class="text-sm text-slate-500 mt-1">User</div>
                    </div>
                    <div class="text-slate-400">
                        <span class="absolute">
                            <iconify-icon class="text-xl" icon="ph:plus-bold"></iconify-icon>
                        </span>
                        <div class="pl-8">
                            <div class="text-xl font-medium">
                                0.00
                                <small class="text-base text-slate-600">USD</small>
                            </div>
                        </div>
                        <div class="text-sm text-slate-500 pl-8 mt-1">Orfinex</div>
                    </div>
                </div>
            </div>
            <div class="card-footer py-4">
                <a href="" class="inline-flex leading-5 text-slate-600 dark:text-slate-400 text-sm font-normal hover:text-slate-900">
                    <iconify-icon icon="ph:file-text-light" class="text-secondary-600 ltr:mr-2 rtl:ml-2 text-lg"></iconify-icon>
                    History
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <header class="card-header noborder">
            <h4 class="card-title">Active Fund</h4>
        </header>
        <div class="card-body p-6 pt-0">
            {{-- <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class=" table-th ">
                                        Fund Title
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Balance
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Equity
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Profit
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Detail
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <iconify-icon icon="ph:check-circle-fill" class="text-4xl text-success mr-2"></iconify-icon>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    Beginner - 10000 USD
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <span class="font-medium">$1203</span>
                                    </td>
                                    <td class="table-td">
                                        $1200
                                    </td>
                                    <td class="table-td">
                                        <span class="text-success">
                                            $230
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <a href="" class="btn btn-dark btn-sm inline-felx items-center justify-center">
                                            Matrics
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
            <div class="flex flex-col items-center justify-center">
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    You do not have any challenges yet.
                </p>
                <a href="" class="btn btn-outline-dark inline-flex items-center justify-center">
                    Start a New Challenge
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <header class="card-header noborder">
            <h4 class="card-title">Active Challenge (1)</h4>
        </header>
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class=" table-th ">
                                        Challenge Title
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Stage / Phase
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Activation Date
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Current Status
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Detail
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <iconify-icon icon="ph:check-circle-fill" class="text-4xl text-warning mr-2"></iconify-icon>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    Beginner - 10000 USD
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <span class="font-medium">Phase 1</span>
                                    </td>
                                    <td class="table-td">
                                        16 Jan, 2024 03:20 PM
                                    </td>
                                    <td class="table-td">
                                        <span class="text-success">Active</span>
                                    </td>
                                    <td class="table-td">
                                        <a href="" class="btn btn-dark btn-sm inline-felx items-center justify-center">
                                            Matrics
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

    <div class="card mt-5">
        <header class="card-header noborder">
            <h4 class="card-title">Violated Challenge (1)</h4>
        </header>
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class=" table-th ">
                                        Challenge Title
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Stage / Phase
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Activation Date
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Violation Date
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Detail
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <iconify-icon icon="mdi:close-circle" class="text-4xl text-danger mr-2"></iconify-icon>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    Specialist - 50000 USD
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <span class="font-medium">Phase 2</span>
                                    </td>
                                    <td class="table-td">
                                        16 Jan, 2024 03:20 PM
                                    </td>
                                    <td class="table-td">
                                        16 Jan, 2024 03:20 PM
                                    </td>
                                    <td class="table-td">
                                        <a href="" class="btn btn-dark btn-sm inline-felx items-center justify-center">
                                            Matrics
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
