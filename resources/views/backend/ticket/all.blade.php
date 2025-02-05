@extends('backend.layouts.app')
@section('title')
    {{ __('All Support Tickets') }}
@endsection

@section('content')
    <div class="pageTitle grid md:grid-cols-4 grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-body p-5">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger">
                            <iconify-icon icon="lucide:ticket"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            {{ __('Total Tickets') }}
                        </div>
                        <div class="count text-slate-900 dark:text-white text-xl font-medium">
                            {{ $totalTickets }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-5">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger">
                            <iconify-icon icon="lucide:ticket"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            {{ __('Closed Tickets') }}
                        </div>
                        <div class="count text-slate-900 dark:text-white text-xl font-medium">
                            {{ $closedTickets }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-5">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger">
                            <iconify-icon icon="lucide:ticket"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            {{ __('Open Tickets') }}
                        </div>
                        <div class="count text-slate-900 dark:text-white text-xl font-medium">
                            {{ $openTickets }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-5">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger">
                            <iconify-icon icon="lucide:ticket"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            {{ __('Resolved Tickets') }}
                        </div>
                        <div class="count text-slate-900 dark:text-white text-xl font-medium">
                            {{ $resolvedTickets }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Ticket #') }}</th>
                                    <th scope="col" class="table-th">{{ __('Ticket Subject') }}</th>
                                    <th scope="col" class="table-th">{{ __('Requester Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Requested On') }}</th>
                                    <th scope="col" class="table-th">{{ __('Others') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

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

@section('style')
    <style>
        .dashcode-data-table .table-td select{
            --tw-bg-opacity: 1;
            background-color: rgb(241 245 249 / var(--tw-bg-opacity));
            border: none;
            outline: none;
            box-shadow: none;
        }
        .dark .dashcode-data-table .table-td select {
            --tw-bg-opacity: 1;
            background-color: rgb(15 23 42 / var(--tw-bg-opacity));
        }
    </style>
@endsection

@section('script')

    <script>
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
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.ticket.index') }}",
                columns: [
                    {data: 'uuid', name: 'uuid'},
                    {data: 'title', name: 'title'},
                    {data: 'user', name: 'user'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'priority', name: 'priority'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });


        })(jQuery);
    </script>
@endsection
