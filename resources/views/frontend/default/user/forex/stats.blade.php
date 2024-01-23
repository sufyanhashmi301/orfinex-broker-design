@extends('frontend::layouts.user')
@section('title')
    {{ __('Account Stats') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('My Accounts') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Stats') }}
            </li>
        </ul>
    </div>
    <div class="card mb-5">
        <div class="card-header noborder">
            <div class="flex items-end flex-wrap gap-3 w-full md:w-1/2">
                <div class="flex-auto">
                    <label for="" class="form-label">Account</label>
                    <select name="" class="select2 form-control w-full py-2">
                        <option value="43741242">
                            Naeem Ali
                            <span class="text-slate-300">#43741242</span>
                        </option>
                    </select>
                </div>
                <div class="flex-auto md:flex-none">
                    <input class="form-control py-2 flatpickr flatpickr-input" id="range-picker" data-mode="range" value="" type="text">
                </div>
            </div>
        </div>
        <div class="card-body p-6 pt-0">
            <div class="grid xl:grid-cols-4 lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 place-content-center">
                <div class="p-4">
                    <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                        Net Profit
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        0 USD
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                        Closed orders
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        0
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                        Trading volume
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        0 USD
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                        Equity
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        -1,875.16 USD
                    </div>
                    <div class="text-xs font-medium text-danger-500">
                        <iconify-icon icon="ph:arrow-down-right"></iconify-icon>
                        100%
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header border-none">
            <h4 class="card-title">Orders History</h4>
        </div>
        <div class="card-body flex flex-col p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th">Deal</th>
                                    <th scope="col" class="table-th">Entry</th>
                                    <th scope="col" class="table-th">Login</th>
                                    <th scope="col" class="table-th">Order</th>
                                    <th scope="col" class="table-th">Position Id</th>
                                    <th scope="col" class="table-th">Price</th>
                                    <th scope="col" class="table-th">Price Position</th>
                                    <th scope="col" class="table-th">Price SL</th>
                                    <th scope="col" class="table-th">Price TP</th>
                                    <th scope="col" class="table-th">Profit</th>
                                    <th scope="col" class="table-th">Symbol</th>
                                    <th scope="col" class="table-th">Time</th>
                                    <th scope="col" class="table-th">Time Msc</th>
                                    <th scope="col" class="table-th">Volume</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        4617191
                                    </td>
                                    <td class="table-td">
                                        1
                                    </td>
                                    <td class="table-td">
                                        6735
                                    </td>
                                    <td class="table-td">
                                        3733223
                                    </td>
                                    <td class="table-td">
                                        3023239
                                    </td>
                                    <td class="table-td">
                                        2028.51
                                    </td>
                                    <td class="table-td">
                                        1986.0
                                    </td>
                                    <td class="table-td">
                                        0.0
                                    </td>
                                    <td class="table-td">
                                        0.0
                                    </td>
                                    <td class="table-td">
                                        21255.0
                                    </td>
                                    <td class="table-td">
                                        XAUUSD
                                    </td>
                                    <td class="table-td">
                                        1705927239
                                    </td>
                                    <td class="table-td">
                                        1705927239678
                                    </td>
                                    <td class="table-td">
                                        50000
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
