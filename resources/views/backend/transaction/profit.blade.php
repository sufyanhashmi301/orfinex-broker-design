@extends('backend.layouts.app')
@section('title')
    {{ __('Transactions') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('All Profits') }}
        </h4>
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
                                    <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit From') }}</th>
                                    <th scope="col" class="table-th">{{ __('Description') }}</th>
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
            var table = $('#dataTable').DataTable();
            table.destroy();

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
                ajax: "{{ route('admin.all-profits') }}",
                columns: [
                    {"class": "table-td", data: 'created_at', name: 'created_at'},
                    {"class": "table-td", data: 'username', name: 'username'},
                    {"class": "table-td", data: 'final_amount', name: 'final_amount'},
                    {"class": "table-td", data: 'type', name: 'type'},
                    {"class": "table-td", data: 'profit_from', name: 'profit_from'},
                    {"class": "table-td", data: 'description', name: 'description'},

                ]
            });
        })(jQuery);
    </script>
@endsection
