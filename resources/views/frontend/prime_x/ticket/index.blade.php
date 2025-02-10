@extends('frontend::layouts.user')
@section('title')
    {{ __('Support Tickets') }}
@endsection
@section('content')
    <div class="pageTitle grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-5">
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
                    <a href="javascript:;" data-status="all" class="widget-filter-status inline-flex items-center justify-center ml-auto">
                        <iconify-icon class="text-xl" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
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
                    <a href="javascript:;" data-status="closed" class="widget-filter-status inline-flex items-center justify-center ml-auto">
                        <iconify-icon class="text-xl" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
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
                    <a href="javascript:;" data-status="open" class="widget-filter-status inline-flex items-center justify-center ml-auto">
                        <iconify-icon class="text-xl" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
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
                    <a href="javascript:;" data-status="resolved" class="widget-filter-status inline-flex items-center justify-center ml-auto">
                        <iconify-icon class="text-xl" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="innerMenu flex justify-between flex-wrap items-center mb-5">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Tickets') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#newTicketModal" class="btn btn-primary inline-flex items-center justify-center">
                {{ __('Create Ticket') }}
            </a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-body relative px-6 pt-0">
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
                                        <th scope="col" class="table-th">{{ __('Requested On') }}</th>
                                        <th scope="col" class="table-th">{{ __('Priority') }}</th>
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
        </div>
    </div>

    {{-- Modal for new ticket--}}
    @include('frontend::ticket.modal.__new_ticket')
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
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('user.ticket.index') }}",
                        data: function (d) {
                            // Get the selected status filter value
                            var status = $('a.widget-filter-status.active').data('status');
                            if (status) {
                                d.status = status;
                            }
                        }
                    },
                    columns: [
                        {data: 'uuid', name: 'uuid'},
                        {data: 'title', name: 'title'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'priority', name: 'priority'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });

            // Handle filter click
            $('a.widget-filter-status').on('click', function () {
                var status = $(this).data('status');

                $('a.widget-filter-status').removeClass('active');
                $(this).addClass('active');

                table.ajax.reload();
            });

        })(jQuery);

        $(document).ready(function() {
            $('#attach-input').change(function() {
                var fileName = this.files[0] ? this.files[0].name : 'Choose a file or drop it here...';
                $('#fileTile').text(fileName);
            });
        });
    </script>
@endsection
