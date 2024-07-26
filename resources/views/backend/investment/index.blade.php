@extends('backend.layouts.app')
@section('title')
    {{ __('Accounts') }}
@endsection
@section('style')
    <style>
        .data-card {
            flex-direction: column !important;
        }
    </style>
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('All :type Accounts',['type'=>ucfirst($type)]) }}
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3 gap-x-2">
            <div class="position-relative bg-slate-50 dark:bg-slate-900 rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm my-2">Total Accounts</p>
                        <h4 class="count text-2xl font-bold mb-0">{{$data['TotalAccounts']}}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-slate-900 rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm my-2">With Balance</p>
                        <h4 class="count text-2xl font-bold mb-0">{{$data['withBalance']}}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-slate-900 rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm my-2">With Bonus</p>
                        <h4 class="count text-2xl font-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-slate-900 rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm my-2">Without Balance</p>
                        <h4 class="count text-2xl font-bold mb-0">{{$data['withoutBalance']}}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-slate-900 rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm my-2">Without Bonus</p>
                        <h4 class="count text-2xl font-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-900 rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-check"><path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm my-2">Inactive Accounts</p>
                        <h4 class="count text-2xl font-bold mb-0">{{$data['unActiveAccounts']}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Account Number') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Currency') }}</th>
                                    <th scope="col" class="table-th">{{ __('Leverage') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Agent/IB Number') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Created At') }}</th>
                                    <th scope="col" class="table-th">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

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
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],
                language: {
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
                search: "Search:"
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.forex-accounts',['type'=>'real']) }}",
                columns: [
                    {"class": "table-td", data: 'login', name: 'login'},
                    {"class": "table-td", data: 'username', name: 'username'},
                    {"class": "table-td", data: 'schema', name: 'schema'},
                    // {"class": "table-td", data: 'login', name: 'login'},
                    {"class": "table-td", data: 'group', name: 'group'},
                    {"class": "table-td", data: 'currency', name: 'currency'},
                    {"class": "table-td", data: 'leverage', name: 'leverage'},
                    {"class": "table-td", data: 'balance', name: 'balance'},
                    {"class": "table-td", data: 'ib_number', name: 'ib_number'},
                    {"class": "table-td", data: 'status', name: 'status'},
                    {"class": "table-td", data: 'created_at', name: 'created_at'},
                    {"class": "table-td", data: 'action', name: 'action'},
                ]
            });

        })(jQuery);
    </script>
@endsection
