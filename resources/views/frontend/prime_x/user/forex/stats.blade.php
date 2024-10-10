@extends('frontend::layouts.user')
@section('title')
    {{ __('Account Stats') }}
@endsection
@section('content')
    <div class="mb-5">
    <ul class="m-0 p-0 list-none">
        <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
            <a href="{{route('user.dashboard')}}">
                <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
            </a>
        </li>
        <li class="inline-block relative text-sm text-primary font-Inter ">
            {{ __('My Accounts') }}
            <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
        </li>
        <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
            {{ __('Stats') }}
        </li>
    </ul>
</div>
<div class="card mb-5">
    <div class="card-header !items-end noborder">
        <div class="flex items-end flex-wrap gap-3 w-full md:w-1/2">
            <div class="flex-1">
                <label for="" class="form-label">{{ __('Account') }}</label>
                <select name="" class="select2 form-control w-full py-2">
                    <option value="43741242">
                        {{ __('Naeem Ali') }}
                        <span class="text-slate-300">{{ __('#43741242') }}</span>
                    </option>
                </select>
            </div>
            <div class="">
                <input class="form-control py-2 h-[40px] flatpickr flatpickr-input" id="range-picker" data-mode="range" value="" type="text">
            </div>
        </div>
    </div>
    <div class="card-body p-6 pt-0">
        <div class="grid xl:grid-cols-4 lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 place-content-center">
            <div class="p-4">
                <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                    {{ __('Net Profit') }}
                </div>
                <div class="text-slate-900 dark:text-white text-lg font-medium">
                    {{ __('0 USD') }}
                </div>
            </div>
            <div class="p-4">
                <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                    {{ __('Closed orders') }}
                </div>
                <div class="text-slate-900 dark:text-white text-lg font-medium">
                    {{ __('0') }}
                </div>
            </div>
            <div class="p-4">
                <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                    {{ __('Trading volume') }}
                </div>
                <div class="text-slate-900 dark:text-white text-lg font-medium">
                    {{ __('0 USD') }}
                </div>
            </div>
            <div class="p-4">
                <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                    {{ __('Equity') }}
                </div>
                <div class="text-slate-900 dark:text-white text-lg font-medium">
                    {{ __('-1,875.16 USD') }}
                </div>
                <div class="text-xs font-medium text-danger">
                    <iconify-icon icon="ph:arrow-down-right"></iconify-icon>
                    {{ __('100%') }}
                </div>
            </div>
        </div>
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-3 " id="pills-tabHorizontal" role="tablist">
            <li class="nav-item text-center" role="presentation">
                <a href="#pills-runingOrdersHorizontal" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 active dark:bg-slate-900 dark:text-slate-300" id="pills-runingOrders-tabHorizontal" data-bs-toggle="pill" data-bs-target="#pills-runingOrdersHorizontal" role="tab" aria-controls="pills-runingOrdersHorizontal" aria-selected="true">
                    {{ __('Running Orders') }}
                </a>
            </li>
            <li class="nav-item text-center" role="presentation">
                <a href="#pills-ordersHistoryHorizontal" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300" id="pills-ordersHistory-tabHorizontal" data-bs-toggle="pill" data-bs-target="#pills-ordersHistoryHorizontal" role="tab" aria-controls="pills-ordersHistoryHorizontal" aria-selected="false">
                    {{ __('Orders History') }}
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="pills-tabContentHorizontal">
    <div class="tab-pane fade show active" id="pills-runingOrdersHorizontal" role="tabpanel" aria-labelledby="pills-runingOrders-tabHorizontal">
        <div class="card">
            <div class="card-header border-none">
                <h4 class="card-title">{{ __('Running Orders') }}</h4>
            </div>
            <div class="card-body flex flex-col p-6 pt-0">
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                        <th scope="col" class="table-th">{{ __('ContractSize') }}</th>
                                        <th scope="col" class="table-th">{{ __('Order') }}</th>
                                        <th scope="col" class="table-th">{{ __('PriceOrder') }}</th>
                                        <th scope="col" class="table-th">{{ __('PriceCurrent') }}</th>
                                        <th scope="col" class="table-th">{{ __('PriceSL') }}</th>
                                        <th scope="col" class="table-th">{{ __('PriceTP') }}</th>
                                        <th scope="col" class="table-th">{{ __('TimeSetup') }}</th>
                                        <th scope="col" class="table-th">{{ __('TimeSetupMsc') }}</th>
                                        <th scope="col" class="table-th">{{ __('Type') }}</th>
                                        <th scope="col" class="table-th">{{ __('TypeFill') }}</th>
                                        <th scope="col" class="table-th">{{ __('VolumeCurrent') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    <tr>
                                        <td class="table-td">
                                            {{ __('USOil') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('100.0') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('3617723') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('69.0') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('73.35') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('0.0') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('74.0') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('1704918935') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('1704918935519') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('2') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('2') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('100') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-ordersHistoryHorizontal" role="tabpanel" aria-labelledby="pills-ordersHistory-tabHorizontal">
        <div class="card">
            <div class="card-header border-none">
                <h4 class="card-title">{{ __('Orders History') }}</h4>
            </div>
            <div class="card-body flex flex-col p-6 pt-0">
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Deal') }}</th>
                                        <th scope="col" class="table-th">{{ __('Entry') }}</th>
                                        <th scope="col" class="table-th">{{ __('Login') }}</th>
                                        <th scope="col" class="table-th">{{ __('Order') }}</th>
                                        <th scope="col" class="table-th">{{ __('PositionId') }}</th>
                                        <th scope="col" class="table-th">{{ __('Price') }}</th>
                                        <th scope="col" class="table-th">{{ __('PricePosition') }}</th>
                                        <th scope="col" class="table-th">{{ __('PriceSL') }}</th>
                                        <th scope="col" class="table-th">{{ __('PriceTP') }}</th>
                                        <th scope="col" class="table-th">{{ __('Profit') }}</th>
                                        <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                        <th scope="col" class="table-th">{{ __('Time') }}</th>
                                        <th scope="col" class="table-th">{{ __('TimeMsc') }}</th>
                                        <th scope="col" class="table-th">{{ __('Volume') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    <tr>
                                        <td class="table-td">
                                            {{ __('4617191') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('1') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('6735') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('3733223') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('3023239') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('2028.51') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('1986.0') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('0.0') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('0.0') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('21255.0') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('XAUUSD') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('1705927239') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('1705927239678') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('50000') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
