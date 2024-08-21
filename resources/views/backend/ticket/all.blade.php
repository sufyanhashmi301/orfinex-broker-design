@extends('backend.ticket.index')
@section('title')
    {{ __('All Support Tickets') }}
@endsection
@section('header-btn')
    <div class="input-area relative w-1/5" style="padding-left: 3rem;">
        <label for="" class="inline-inputLabel text-sm">{{ __('Filter:') }}</label>
        <select class="form-control !bg-transparent">
            <option selected="">All</option>
            <option>...</option>
        </select>
    </div>
    <div class="input-area relative w-1/5" style="padding-left: 3rem;">
        <label for="" class="inline-inputLabel text-sm">{{ __('Sort:') }}</label>
        <select class="form-control !bg-transparent">
            <option selected="">Choose...</option>
            <option>...</option>
        </select>
    </div>
@endsection
@section('ticket-content')
    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Ticket Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Ticket Priority') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
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
                ajax: "{{ route('admin.ticket.index') }}",
                columns: [
                    {"class": "table-td", data: 'name', name: 'name'},
                    {"class": "table-td", data: 'priority', name: 'priority'},
                    {"class": "table-td", data: 'status', name: 'status'},
                    {"class": "table-td", data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });


        })(jQuery);
    </script>
@endsection
