@extends('backend.layouts.app')
@section('title')
    {{ __('All Support Tickets') }}
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
        @can('support-ticket-create')
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="#" type="button" id="newTicketBtn" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Create Ticket') }}
            </a>
        </div>
        @endcan
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
                                    <th scope="col" class="table-th">{{ __('Assignee') }}</th>
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
@can('support-ticket-create')
    {{-- Modal for new ticket --}}
    @include('backend.ticket.modal.__new_ticket')
@endcan
@can('support-ticket-assign')
    {{-- Modal for assign ticket--}}
    @include('backend.ticket.modal.__assign_ticket')
@endcan
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
                order: [[3, 'desc']],
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
                    url: "{{ route('admin.ticket.index') }}",
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
                    {data: 'user', name: 'user'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'assigned_to', name: 'assigned_to'},
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

        function formatUser(data) {
            if (!data.id) {
                return data.text;
            }

            var avatar = $(data.element).data('avatar');
            var role = $(data.element).data('role');
            var text = data.text;

            var $container = $(
                `<div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-[100%]">
                        <img src="${avatar}" alt="${text}" class="w-full h-full rounded-[100%] object-cover">
                    </div>
                    <span>${text}</span>
                    <span class="badge badge-primary">${role}</span>
                </div>`
            );
            return $container;
        };

        $('body').on('click', '#newTicketBtn', function (e) {
            "use strict";
            e.preventDefault();

            var url = '{{ route("admin.ticket.create") }}';

            $.get(url , function (data) {
                $('#newTicketModal').modal('show');
                $('#new-ticket-body').append(data);
                $('.select2').select2({
                    dropdownParent: $('#newTicketModal')
                });
                $('#client_input').select2({
                    dropdownParent: $('#newTicketModal'),
                    ajax: {
                        url: '{{ route("admin.user.search") }}',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: data.results
                            };
                        },
                        cache: true
                    },
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text;
                        }

                        var avatar = data.avatar;
                        var email = data.email;
                        var text = data.text;

                        var $container = $(`
                            <div class="flex items-center">
                                <div class="flex-none">
                                    <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                        <img src="${avatar}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                    </div>
                                </div>
                                <div class="flex-1 text-start">
                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                        ${text}
                                    </h4>
                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                        ${email}
                                    </div>
                                </div>
                            </div>
                        `);
                        return $container;
                    },
                    templateSelection: function(data) {
                        return data.text;
                    }
                });
                $('#assigned_to').select2({
                    dropdownParent: $('#newTicketModal'),
                    templateResult: formatUser,
                    templateSelection: formatUser,
                });

                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });

            });
        });

        $('body').on('click', '#assignTicket', function (event) {
            "use strict";
            event.preventDefault();
            $('#assign-ticket-body').empty();
            var id = $(this).data('id');

            var url = '{{ route("admin.ticket.showAssignModal", ":id") }}';
            url = url.replace(':id', id);

            $.get(url , function (data) {
                $('#assignTicketModal').modal('show');
                $('#assign-ticket-body').append(data);
                $('.select2').select2();
                $('#assigned_to_select').select2({
                    templateResult: formatUser,
                    templateSelection: formatUser,
                });
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });
            });

        });

        $(document).on('change', '#attach-input', function() {
            var fileName = this.files[0] ? this.files[0].name : 'Choose a file or drop it here...';
            $('#fileTile').text(fileName);
        });

    </script>
@endsection
