@extends('backend.layouts.app')
@section('title')
    {{ __('Rejected Custom Payment Account Requests') }}
@endsection

@section('filters')
    <form id="filter-form" method="POST" action="{{ route('admin.payment-deposit.rejected.list') }}">
        @csrf
        <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full"
                        placeholder="Search by Name, Username, Email">
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
                    <input type="text" name="created_at" id="created-at"
                        class="form-control flatpickr-created-at h-full w-full" placeholder="Rejected At Range" readonly>
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="rejection_reason" id="rejection-reason" class="form-control h-full w-full"
                        placeholder="Search Rejection Reason">
                </div>
            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                <div class="input-area relative">
                    <button type="button" id="filter"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="button"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-danger-100 text-danger-700 dark:bg-danger-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:download"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </div>

            </div>
        </div>
    </form>
@endsection

@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4
            class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Rejected Custom Payment Account Requests') }}
        </h4>
    </div>

    @include('backend.payment-deposit.include.__menu')

    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div
                    class="bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:x-circle"
                                class="text-2xl text-danger-600 dark:text-danger-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-danger-800 dark:text-danger-200">
                                {{ __('Total Rejected') }}</p>
                            <p class="text-lg font-semibold text-danger-900 dark:text-danger-100" id="rejected-count">
                                {{ number_format($stats['total_rejected']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-info-50 dark:bg-info-900/20 border border-info-200 dark:border-info-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:calendar-x"
                                class="text-2xl text-info-600 dark:text-info-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-info-800 dark:text-info-200">{{ __('This Month') }}</p>
                            <p class="text-lg font-semibold text-info-900 dark:text-info-100" id="month-count">
                                {{ number_format($stats['this_month']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900/20 border border-slate-200 dark:border-slate-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:users"
                                class="text-2xl text-slate-600 dark:text-slate-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ __('Total Users') }}</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100" id="users-count">
                                {{ number_format($stats['total_users']) }}</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:calendar-days"
                                class="text-2xl text-warning-600 dark:text-warning-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-warning-800 dark:text-warning-200">{{ __('This Year') }}</p>
                            <p class="text-lg font-semibold text-warning-900 dark:text-warning-100" id="year-count">
                                {{ number_format($stats['this_year']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Stats Indicator -->
            <div id="stats-loading" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @for ($i = 0; $i < 4; $i++)
                        <div
                            class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4 animate-pulse">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-slate-300 dark:bg-slate-600 rounded"></div>
                                </div>
                                <div class="ltr:ml-3 rtl:mr-3 flex-1">
                                    <div class="h-4 bg-slate-300 dark:bg-slate-600 rounded mb-2"></div>
                                    <div class="h-6 bg-slate-300 dark:bg-slate-600 rounded w-16"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                            id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User Info') }}</th>
                                    <th scope="col" class="table-th">{{ __('Request Details') }}</th>
                                    <th scope="col" class="table-th">{{ __('Rejected At') }}</th>
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

    <!-- Detail Modal -->
    @include('backend.payment-deposit.modal.__detail_modal')

    <!-- Reset Status Modal -->
    @include('backend.payment-deposit.modal.__reset_status_modal')

    <!-- Re-approve Modal -->
    @include('backend.payment-deposit.modal.__re_approve_modal')
@endsection

@section('script')
    <script>
        (function($) {
            "use strict";
            var table = $('#dataTable').DataTable();
            table.destroy();

            flatpickr(".flatpickr-created-at", {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: true
            });

            var table = window.dataTable = $('#dataTable')
                .on('processing.dt', function(e, settings, processing) {
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
                        url: "{{ route('admin.payment-deposit.rejected.list') }}",
                        data: function(d) {
                            d.global_search = $('#global_search').val();
                            d.date_filter = $('#date-filter').val();
                            d.created_at = $('#created-at').val();
                            d.rejection_reason = $('#rejection-reason').val();
                        }
                    },
                    columns: [{
                            data: 'user_info',
                            name: 'username',
                            orderable: false
                        },
                        {
                            data: 'request_details',
                            name: 'request_details',
                            orderable: false
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

            // Filter button click event
            $('#filter').click(function() {
                table.draw();
            });

            // View details
            $('#dataTable').on('click', '.detail-btn', function() {
                let requestId = $(this).data('request-id');

                $.ajax({
                    url: "{{ route('admin.payment-deposit.request.view', ['request' => ':requestId']) }}"
                        .replace(':requestId', requestId),
                    method: 'GET',
                    success: function(response) {
                        $('#detailContent').html(response);
                        $('#detailModal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error fetching request data:', error);
                        tNotify('warning', 'Failed to load request details');
                    }
                });
            });

            // Reset Status functionality
            $('#dataTable').on('click', '.reset-status-btn', function() {
                var requestId = $(this).data('request-id');
                $('#resetStatusRequestId').val(requestId);
                $('#resetStatusModal').modal('show');
            });

            // Re-approve functionality
            $('#dataTable').on('click', '.re-approve-btn', function() {
                var requestId = $(this).data('request-id');
                $('#reApproveRequestId').val(requestId);
                resetReApproveModal();
                $('#reApproveModal').modal('show');
            });

            // Handle reset status form submission
            $('#resetStatusSubmitBtn').on('click', function() {
                var submitBtn = $(this);
                var originalText = submitBtn.text();
                var requestId = $('#resetStatusRequestId').val();

                // Show loading state
                submitBtn.prop('disabled', true);
                $('#resetStatusIcon').addClass('hidden');
                $('#resetStatusLoader').removeClass('hidden');
                $('#resetStatusText').text('Processing...');

                $.ajax({
                    url: "{{ route('admin.payment-deposit.reset.status') }}",
                    method: 'POST',
                    data: {
                        request_id: requestId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#resetStatusModal').modal('hide');
                        tNotify('success', response.message);
                        table.draw();
                        updateStats();
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        tNotify('warning', response?.error || 'Failed to reset status');
                    },
                    complete: function() {
                        setTimeout(function() {
                            submitBtn.prop('disabled', false);
                            $('#resetStatusIcon').removeClass('hidden');
                            $('#resetStatusLoader').addClass('hidden');
                            $('#resetStatusText').text(originalText);
                        }, 100);
                    }
                });
            });

            // Handle re-approve form submission
            $('#reApproveSubmitBtn').on('click', function() {
                var submitBtn = $(this);
                var originalText = submitBtn.text();
                var formData = {
                    request_id: $('#reApproveRequestId').val(),
                    bank_name: $('#reApproveBankName').val(),
                    account_name: $('#reApproveAccountName').val(),
                    account_number: $('#reApproveAccountNumber').val(),
                    routing_number: $('#reApproveRoutingNumber').val(),
                    swift_code: $('#reApproveSwiftCode').val(),
                    bank_address: $('#reApproveBankAddress').val(),
                    additional_instructions: $('#reApproveAdditionalInstructions').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                // Show loading state
                submitBtn.prop('disabled', true);
                $('#reApproveIcon').addClass('hidden');
                $('#reApproveLoader').removeClass('hidden');
                $('#reApproveText').text('Processing...');

                $.ajax({
                    url: "{{ route('admin.payment-deposit.re.approve') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#reApproveModal').modal('hide');
                        tNotify('success', response.message);
                        table.draw();
                        updateStats();
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        tNotify('warning', response?.error || 'Failed to approve request');
                    },
                    complete: function() {
                        setTimeout(function() {
                            submitBtn.prop('disabled', false);
                            $('#reApproveIcon').removeClass('hidden');
                            $('#reApproveLoader').addClass('hidden');
                            $('#reApproveText').text(originalText);
                        }, 100);
                    }
                });
            });

            // Function to update stats
            function updateStats() {
                $('#stats-loading').removeClass('hidden');

                $.ajax({
                    url: "{{ route('admin.payment-deposit.rejected.list') }}",
                    method: 'GET',
                    data: {
                        stats_only: true,
                        global_search: $('#global_search').val(),
                        date_filter: $('#date-filter').val(),
                        created_at: $('#created-at').val(),
                        rejection_reason: $('#rejection-reason').val()
                    },
                    success: function(response) {
                        if (response.stats) {
                            $('#rejected-count').text(response.stats.total_rejected.toLocaleString());
                            $('#month-count').text(response.stats.this_month.toLocaleString());
                            $('#users-count').text(response.stats.total_users.toLocaleString());
                            $('#year-count').text(response.stats.this_year.toLocaleString());
                        }
                    },
                    complete: function() {
                        $('#stats-loading').addClass('hidden');
                    }
                });
            }

            // Function to reset re-approve modal
            function resetReApproveModal() {
                $('#reApproveForm')[0].reset();
                $('#reApproveSubmitBtn').prop('disabled', false);
                $('#reApproveIcon').removeClass('hidden');
                $('#reApproveLoader').addClass('hidden');
                $('#reApproveText').text('{{ __('Re-approve Request') }}');
            }

            // Reset modals when they are closed
            $('#resetStatusModal').on('hidden.bs.modal', function() {
                $('#resetStatusSubmitBtn').prop('disabled', false);
                $('#resetStatusIcon').removeClass('hidden');
                $('#resetStatusLoader').addClass('hidden');
                $('#resetStatusText').text('{{ __('Reset Status') }}');
            });

            $('#reApproveModal').on('hidden.bs.modal', function() {
                resetReApproveModal();
            });

        })(jQuery);
    </script>
@endsection
