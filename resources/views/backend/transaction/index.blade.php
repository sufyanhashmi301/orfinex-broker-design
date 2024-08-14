@extends('backend.layouts.app')
@section('title')
    {{ __('Transactions') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('All Transactions') }}
        </h4>
    </div>
    
    <div class="card p-6 mb-5">
        <form id="filter-form" method="POST" action="{{ route('admin.transactions.export') }}">
            @csrf
            <div class="flex justify-between flex-wrap items-center">
                <div class="flex-1 inline-flex sm:space-x-3 space-x-2 ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                    <div class="flex-1 input-area relative">
                        <input type="text" name="email" id="email" class="form-control h-full" placeholder="Search User By Email">
                    </div>
                    <div class="flex-1 input-area relative">
                        <select name="status" class="form-control h-full" id="status">
                            <option value="">Status</option>
                            <option value="success">Success</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Cancelled</option>
                        </select>
                    </div>
                    <div class="flex-1 input-area relative">
                        <select name="type" class="form-control h-full" id="type">
                            <option value="">Transaction Type</option>
                            <option value="deposit">Deposit</option>
                            <option value="forex_deposit">Demo Deposit</option>
                            <option value="subtract">Subtract</option>
                            <option value="manual_deposit">Manual Deposit</option>
                            <option value="send_money">Send Money </option>
                            <option value="send_money_internal">Send Money Internal</option>
                            <option value="exchange">Exchange</option>
                            <option value="referral">Referral</option>
                            <option value="bonus">Signup Bonus</option>
                          
                            <option value="withdraw">Withdraw</option>
                            <option value="withdraw_auto">Withdraw Auto</option>
                            <option value="receive_money">Receive Money</option>
                            <option value="investment">Investment</option>
                            <option value="interest">Interest</option>
                            <option value="refund">Refund</option>
                            <option value="multi_ib">Multi IB</option>
                            <option value="ib">IB</option>
                        </select>
                    </div>
                
                    <div class="flex-1 input-area relative">
                        <input type="date" name="created_at" id="created_at" class="form-control h-full" placeholder="Created At">
                    </div>
                
                </div>
                <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <div class="input-area relative">
                        <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                            {{ __('Apply Filter') }}
                        </button>
                    </div>
                    <div class="input-area relative">
                        <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                            {{ __('Export') }}
                        </button>
                    </div>
                    <div class="input-area relative">
                        <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                            <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Transaction ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
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
                autoWidth: false,
               
                ajax: {
                    url: "{{ route('admin.transactions') }}",
                    data: function (d) {
                        d.email = $('#email').val();
                        d.status = $('#status').val();
                        d.type = $('#type').val();
                        d.status = $('#status').val();
                        d.created_at = $('#created_at').val();
                       
                    }
                },
                columns: [
                    {"class": "table-td", data: 'created_at', name: 'created_at'},
                    {"class": "table-td", data: 'username', name: 'username'},
                    {"class": "table-td", data: 'tnx', name: 'tnx'},
                    {"class": "table-td", data: 'type', name: 'type'},
                    {"class": "table-td", data: 'target_id', name: 'target_id'},
                    {"class": "table-td", data: 'final_amount', name: 'final_amount'},
                    {"class": "table-td", data: 'method', name: 'method'},
                    {"class": "table-td", data: 'status', name: 'status'},
                ]
            });

            $('#filter').click(function () {
                table.draw();
            });
        })(jQuery);
       
    </script>
@endsection
