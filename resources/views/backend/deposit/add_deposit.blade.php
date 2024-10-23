@extends('backend.deposit.index')
@section('title')
    {{ __('Add Deposit') }}
@endsection
@section('page-title')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('deposit_content')
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-5 col-span-12">
            <div class="card h-full">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Add Deposit') }}</h4>
                </div>
                <div class="card-body p-6 pt-3">
                    <form action="" method="post">
                        <div class="grid grid-cols-12 items-center gap-5">
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Transaction By') }}</label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="customer wallet">{{ __('Customer Wallet') }}</option>
                                    <option value="trading account">{{ __('Trading Account') }}</option>
                                </select>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Account / Wallet') }}</label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="customer wallet">{{ __('Customer Wallet') }}</option>
                                    <option value="trading account">{{ __('Trading Account') }}</option>
                                </select>
                            </div>
                            <div class="input-area col-span-12">
                                <label for="" class="form-label">{{ __('Payment Method') }}</label>
                                <select name="gateway_code" class="select2 form-control">
                                    <option selected="">--Select Gateway--</option>
                                    <option value="perfectmoney-usd">Perfect Money</option>
                                    <option value="BANKPK">Bank Transfer - PKR</option>
                                    <option value="USDT-TRC20">USDT</option>
                                    <option value="BANKAED">Bank Transfer - AED</option>
                                </select>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Base Currency') }}</label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="usd">{{ __('USD') }}</option>
                                    <option value="eur">{{ __('EUR') }}</option>
                                    <option value="btc">{{ __('BTC') }}</option>
                                    <option value="bnb">{{ __('BNB') }}</option>
                                </select>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Account Currency') }}</label>
                                <input type="text" name="" class="form-control w-full" placeholder="PKR" readonly>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Amount') }}</label>
                                <input type="text" name="" class="form-control" placeholder="1000">
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label opacity-0">{{ __('Auto Approve') }}</label>
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto !mb-0">
                                        {{ __('Auto Approve') }}
                                    </label>
                                    <div class="form-switch" style="line-height: 0;">
                                        <input class="form-check-input" type="hidden" value="0" name=""/>
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="" value="1" class="sr-only peer" >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-area col-span-12">
                                <label for="" class="form-label">{{ __('Comments') }}</label>
                                <textarea name="" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Proceed to Payment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="lg:col-span-7 col-span-12">
            <div class="card h-full">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Recent Transactions') }}</h4>
                </div>
                <div class="card-body relative px-6 pt-3">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <span class=" col-span-8  hidden"></span>
                        <span class="  col-span-4 hidden"></span>
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('Date') }}</th>
                                            <th scope="col" class="table-th">{{ __('User') }}</th>
                                            <th scope="col" class="table-th">{{ __('Account') }}</th>
                                            <th scope="col" class="table-th">{{ __('Transaction') }}</th>
                                            <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                            <th scope="col" class="table-th">{{ __('Status') }}</th>
                                            <th scope="col" class="table-th">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        <tr>
                                            <td>Oct 08, 2024 08:54</td>
                                            <td>
                                                <a href="" class="flex">
                                                    <span class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
                                                        NA
                                                    </span>
                                                    <div>
                                                        <span class="text-sm text-slate-900 dark:text-white block capitalize">
                                                            Sufyan9079
                                                        </span>
                                                        <span class="text-xs text-slate-500 dark:text-slate-300">
                                                            sufyanhashmi301@gmail.com
                                                        </span>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>8187660384</td>
                                            <td>
                                                <span class="text-slate-500 dark:text-slate-400">
                                                    <span class="block font-medium text-slate-600 dark:text-slate-300">20 USD</span>
                                                    <span class="block text-slate-500 text-xs">TID: 8HG654Pk32</span>
                                                </span>
                                            </td>
                                            <td>USDT Test</td>
                                            <td>
                                                <div class="badge bg-warning text-warning bg-opacity-30 capitalize">
                                                    Pending
                                                </div>
                                            </td>
                                            <td>
                                                <span type="button" data-id="" id="deposit-action">
                                                    <button class="action-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Approval Process">
                                                        <iconify-icon icon="lucide:eye"></iconify-icon>
                                                    </button>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="processingIndicator" class="text-center">
                        {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                        <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script !src="">
        (function ($) {
            "use strict";
            var table = $('#dataTable')
                .on('processing.dt', function (e, settings, processing) {
                    $('#processingIndicator').css('display', processing ? 'block' : 'none');
                }).DataTable({
                    dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                    searching: false,
                    lengthChange: false,
                    info: true,
                    language: {
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                            next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                        },
                        search: "Search:"
                    },
                    processing: true,
                    autoWidth: false,
                })
        })(jQuery);
    </script>
@endsection
