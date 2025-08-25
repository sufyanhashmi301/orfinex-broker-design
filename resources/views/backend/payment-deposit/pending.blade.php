@extends('backend.layouts.app')
@section('title')
    {{ __('Pending Custom Payment Account Requests') }}
@endsection

@section('filters')
    <form id="filter-form" method="GET">
        <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full" placeholder="Search by Name, Username, Email">
                </div>
                <div class="flex-1 input-area relative">
                    <select id="date-filter" name="date_filter" class="form-control h-full">
                        <option value="">{{ __('Select Days') }}</option>
                        <option value="3_days">{{ __('Last 3 Days') }}</option>
                        <option value="5_days">{{ __('Last 5 Days') }}</option>
                        <option value="15_days">{{ __('Last 15 Days') }}</option>
                        <option value="1_month">{{ __('Last 1 Month') }}</option>
                        <option value="3_months">{{ __('Last 3 Months') }}</option>
                    </select>
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="created_at" id="created-at" class="form-control flatpickr-created-at h-full w-full" placeholder="Created At Range" readonly>
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="bank_details" id="bank-details" class="form-control h-full w-full" placeholder="Search Bank Details (Name, Account, etc.)">
                </div>
            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                <div class="input-area relative">
                    <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                        <iconify-icon class="text-base font-light" icon="lucide:settings"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Pending Custom Payment Account Requests') }}
        </h4>
    </div>

    @include('backend.payment-deposit.include.__menu')

    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:clock" class="text-2xl text-warning-600 dark:text-warning-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-warning-800 dark:text-warning-200">{{ __('Pending Requests') }}</p>
                            <p class="text-lg font-semibold text-warning-900 dark:text-warning-100" id="pending-count">{{ number_format($stats['total_pending']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-info-50 dark:bg-info-900/20 border border-info-200 dark:border-info-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:calendar" class="text-2xl text-info-600 dark:text-info-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-info-800 dark:text-info-200">{{ __('Today') }}</p>
                            <p class="text-lg font-semibold text-info-900 dark:text-info-100" id="today-count">{{ number_format($stats['today']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:calendar-check" class="text-2xl text-slate-600 dark:text-slate-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ __('This Month') }}</p>
                            <p class="text-lg font-semibold text-slate-700 dark:text-slate-200" id="month-count">{{ number_format($stats['this_month']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User Info') }}</th>
                                    <th scope="col" class="table-th">{{ __('Request Details') }}</th>
                                    <th scope="col" class="table-th">{{ __('Submitted At') }}</th>
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
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>

    @include('backend.payment-deposit.modal.__approve_modal')
    @include('backend.payment-deposit.modal.__reject_modal')
    @include('backend.payment-deposit.modal.__detail_modal')
@endsection

@section('script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#dataTable').DataTable();
            table.destroy();
            
            flatpickr(".flatpickr-created-at", {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: true
            });

            var table = $('#dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            })
            .on('draw.dt', function() {
                // Update stats after table loads
                updateStats();
            })
            .DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                processing: true,
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
                    search: "Search:",
                    processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
                },
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.payment-deposit.pending.list') }}",
                    data: function (d) {
                        d.global_search = $('#global_search').val();
                        d.date_filter = $('#date-filter').val();
                        d.created_at = $('#created-at').val();
                        d.bank_details = $('#bank-details').val();
                    }
                },
                columns: [
                    {data: 'user_info', name: 'username', orderable: false},
                    {data: 'request_details', name: 'request_details', orderable: false},
                    {data: 'submitted_at', name: 'submitted_at'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Filter button click event
            $('#filter').click(function () {
                table.draw();
            });

            // Input field change events (same as IB system)
            $('#global_search, #date-filter, #created-at, #bank-details').on('change keyup', function() {
                table.draw();
            });

            // View details
            $('#dataTable').on('click', '.detail-btn', function () {
                let requestId = $(this).data('request-id');

                $.ajax({
                    url: "{{ route('admin.payment-deposit.request.view', ['request' => ':requestId']) }}".replace(':requestId', requestId),
                    method: 'GET',
                    success: function (response) {
                        $('#detailContent').html(response);
                        $('#detailModal').modal('show');
                    },
                    error: function (error) {
                        console.error('Error fetching request data:', error);
                    }
                });
            });

            // Approve request
            $('#dataTable').on('click', '.approve-btn', function() {
                $('#approveModal').modal('show');
                var rowData = table.row($(this).closest('tr')).data();
                $('#approveRequestId').val(rowData.id);
            });

            // Reject request
            $('#dataTable').on('click', '.reject-btn', function() {
                $('#rejectModal').modal('show');
                var rowData = table.row($(this).closest('tr')).data();
                $('#rejectRequestId').val(rowData.id);
            });

            // Handle approve form submission
            $('#approveForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                // Show loading state
                $('#approveSubmitBtn').prop('disabled', true);
                $('#approveIcon').addClass('hidden');
                $('#approveLoader').removeClass('hidden');
                $('#approveText').text('{{ __("Processing...") }}');

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.payment-deposit.approve") }}',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // Reset loading state
                        $('#approveSubmitBtn').prop('disabled', false);
                        $('#approveIcon').removeClass('hidden');
                        $('#approveLoader').addClass('hidden');
                        $('#approveText').text('{{ __("Approve & Send Bank Details") }}');

                        if(res.success){
                            tNotify('success', res.success);
                            $('#approveModal').modal('hide');
                            if(res.reload) {
                                setTimeout(function(){ location.reload(); }, 900);
                            }
                        }
                        else if(res.error){
                            tNotify('warning', res.error);
                        }
                    },
                    error: function(error) {
                        // Reset loading state
                        $('#approveSubmitBtn').prop('disabled', false);
                        $('#approveIcon').removeClass('hidden');
                        $('#approveLoader').addClass('hidden');
                        $('#approveText').text('{{ __("Approve & Send Bank Details") }}');

                        tNotify('warning', error.responseJSON.message);
                    }
                });
            });

            // Handle reject form submission
            $('#rejectForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                // Show loading state
                $('#rejectSubmitBtn').prop('disabled', true);
                $('#rejectIcon').addClass('hidden');
                $('#rejectLoader').removeClass('hidden');
                $('#rejectText').text('{{ __("Processing...") }}');

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.payment-deposit.reject") }}',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // Reset loading state
                        $('#rejectSubmitBtn').prop('disabled', false);
                        $('#rejectIcon').removeClass('hidden');
                        $('#rejectLoader').addClass('hidden');
                        $('#rejectText').text('{{ __("Reject Request") }}');

                        if(res.success){
                            tNotify('success', res.success);
                            $('#rejectModal').modal('hide');
                            if(res.reload) {
                                setTimeout(function(){ location.reload(); }, 900);
                            }
                        }
                        else if(res.error){
                            tNotify('warning', res.error);
                        }
                    },
                    error: function(error) {
                        // Reset loading state
                        $('#rejectSubmitBtn').prop('disabled', false);
                        $('#rejectIcon').removeClass('hidden');
                        $('#rejectLoader').addClass('hidden');
                        $('#rejectText').text('{{ __("Reject Request") }}');

                        tNotify('warning', error.responseJSON.message);
                    }
                });
            });

            // Reset loading states when modals are hidden
            $('#approveModal').on('hidden.bs.modal', function () {
                $('#approveSubmitBtn').prop('disabled', false);
                $('#approveIcon').removeClass('hidden');
                $('#approveLoader').addClass('hidden');
                $('#approveText').text('{{ __("Approve & Send Bank Details") }}');
            });

            $('#rejectModal').on('hidden.bs.modal', function () {
                $('#rejectSubmitBtn').prop('disabled', false);
                $('#rejectIcon').removeClass('hidden');
                $('#rejectLoader').addClass('hidden');
                $('#rejectText').text('{{ __("Reject Request") }}');
            });

            // Function to update stats cards
            function updateStats() {
                // Note: Stats are now loaded from server-side data on page load
                // DataTables filtering doesn't affect the overall stats, only the visible table data
                // The stats represent the total counts, not filtered counts
                
                // Keep the original stats values (they are already populated from backend)
                // Only update if you want to show filtered counts instead of total counts
            }

            // Initialize stats on page load
            $(document).ready(function() {
                // Initialize tooltips
                if (typeof tippy !== 'undefined') {
                    tippy('.toolTip', {
                        theme: 'dark',
                        placement: 'top'
                    });
                }

                // Filter toggle functionality (same as IB system)
                $('.filter-toggle-btn').click(function() {
                    const $content = $('#filters_div');

                    if ($content.hasClass('hidden')) {
                        $content.removeClass('hidden').slideDown();
                    } else {
                        $content.slideUp(function() {
                            $content.addClass('hidden');
                        });
                    }
                });
            });

        })(jQuery);
    </script>
@endsection
