@extends('backend.layouts.app')
@section('title')
    {{ __('Approved Custom Payment Account Requests') }}
@endsection

@section('filters')
    <form id="filter-form" method="POST" action="{{ route('admin.payment-deposit.approved.list') }}">
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
                        class="form-control flatpickr-created-at h-full w-full" placeholder="Approved At Range" readonly>
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="bank_details" id="bank-details" class="form-control h-full w-full"
                        placeholder="Search Bank Details (Name, Account, etc.)">
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
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-success-100 text-success-700 dark:bg-success-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:download"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="button"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white"
                        data-bs-toggle="modal" data-bs-target="#configureModal">
                        <iconify-icon class="text-base font-light" icon="lucide:settings"></iconify-icon>
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
            {{ __('Approved Custom Payment Account Requests') }}
        </h4>
    </div>

    @include('backend.payment-deposit.include.__menu')

    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div
                    class="bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:check-circle"
                                class="text-2xl text-success-600 dark:text-success-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-success-800 dark:text-success-200">
                                {{ __('Total Approved') }}</p>
                            <p class="text-lg font-semibold text-success-900 dark:text-success-100" id="approved-count">
                                {{ number_format($stats['total_approved']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-info-50 dark:bg-info-900/20 border border-info-200 dark:border-info-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:calendar-check"
                                class="text-2xl text-info-600 dark:text-info-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-info-800 dark:text-info-200">{{ __('This Month') }}</p>
                            <p class="text-lg font-semibold text-info-900 dark:text-info-100" id="month-count">
                                {{ number_format($stats['this_month']) }}</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:calendar"
                                class="text-2xl text-purple-600 dark:text-purple-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-purple-800 dark:text-purple-200">{{ __('This Year') }}</p>
                            <p class="text-lg font-semibold text-purple-900 dark:text-purple-100" id="year-count">
                                {{ number_format($stats['this_year']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:users"
                                class="text-2xl text-slate-600 dark:text-slate-400"></iconify-icon>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ __('Total Users') }}</p>
                            <p class="text-lg font-semibold text-slate-700 dark:text-slate-200" id="total-users-count">
                                {{ number_format($stats['total_users']) }}</p>
                        </div>
                    </div>
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
                                    <th scope="col" class="table-th">{{ __('Bank Details') }}</th>
                                    <th scope="col" class="table-th">{{ __('Approved At') }}</th>
                                    <th scope="col" class="table-th">{{ __('Approved By') }}</th>
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

    @include('backend.payment-deposit.modal.__detail_modal')
    @include('backend.payment-deposit.modal.__update_bank_modal')
    @include('backend.payment-deposit.modal.__reject_approved_modal')
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
                        url: "{{ route('admin.payment-deposit.approved.list') }}",
                        data: function(d) {
                            d.global_search = $('#global_search').val();
                            d.date_filter = $('#date-filter').val();
                            d.created_at = $('#created-at').val();
                            d.bank_details = $('#bank-details').val();
                        }
                    },
                    columns: [{
                            data: 'user_info',
                            name: 'username',
                            orderable: false
                        },
                        {
                            data: 'bank_details',
                            name: 'bank_details',
                            orderable: false
                        },
                        {
                            data: 'approved_at',
                            name: 'approved_at'
                        },
                        {
                            data: 'approved_by_name',
                            name: 'approved_by_name'
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

            // Input field change events (same as IB system)
            $('#global_search, #date-filter, #created-at, #bank-details').on('change keyup', function() {
                table.draw();
            });

            // View details
            $('#dataTable').on('click', '.detail-btn', function() {
                let requestId = $(this).data('request-id');

                // Show loading state
                $('#detailContent').html(
                    '<div class="text-center py-8"><iconify-icon icon="lucide:loader" class="text-2xl animate-spin"></iconify-icon><p class="mt-2 text-slate-500">Loading...</p></div>'
                );

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
                        $('#detailContent').html(
                            '<div class="text-center py-8 text-red-500"><iconify-icon icon="lucide:alert-circle" class="text-2xl"></iconify-icon><p class="mt-2">Failed to load request details</p></div>'
                        );
                        tNotify('warning', 'Failed to load request details');
                    }
                });
            });

            // Function to update stats cards
            function updateStats() {
                // Note: Stats are now loaded from server-side data on page load
                // DataTables filtering doesn't affect the overall stats, only the visible table data
                // The stats represent the total counts, not filtered counts

                // Keep the original stats values (they are already populated from backend)
                // Only update if you want to show filtered counts instead of total counts
            }

            // Initialize stats and tooltips on page load
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

            // Function to reset update bank modal
            function resetUpdateBankModal() {
                const submitBtn = $('#updateBankSubmitBtn');
                const icon = $('#updateBankIcon');
                const loader = $('#updateBankLoader');
                const text = $('#updateBankText');

                // Reset button state
                submitBtn.prop('disabled', false);
                icon.removeClass('hidden');
                loader.addClass('hidden');
                text.text('{{ __('Update Bank Details') }}');

                // Clear form
                $('#updateBankForm')[0].reset();
            }

            // Update Bank Details functionality
            $('#dataTable').on('click', '.update-bank-btn', function() {
                let requestId = $(this).data('request-id');

                // Reset modal and button states
                resetUpdateBankModal();

                // Get current bank details
                $.ajax({
                    url: "{{ route('admin.payment-deposit.get.bank.details', ['request' => ':requestId']) }}"
                        .replace(':requestId', requestId),
                    method: 'GET',
                    success: function(response) {
                        console.log('Response received:', response);
                        const bankDetails = response.bank_details || {};
                        console.log('Bank details:', bankDetails);

                        // Populate form with current data
                        $('#updateRequestId').val(requestId);
                        $('#updateBankName').val(bankDetails.bank_name || '');
                        $('#updateAccountName').val(bankDetails.account_name || '');
                        $('#updateAccountNumber').val(bankDetails.account_number || '');
                        $('#updateRoutingNumber').val(bankDetails.routing_number || '');
                        $('#updateSwiftCode').val(bankDetails.swift_code || '');
                        $('#updateBankAddress').val(bankDetails.bank_address || '');
                        $('#updateAdditionalInstructions').val(bankDetails
                            .additional_instructions || '');

                        // Show modal
                        $('#updateBankModal').modal('show');
                    },
                    error: function() {
                        tNotify('warning', 'Failed to load bank details');
                    }
                });
            });

            // Handle update bank details form submission
            $('#updateBankForm').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#updateBankSubmitBtn');
                const icon = $('#updateBankIcon');
                const loader = $('#updateBankLoader');
                const text = $('#updateBankText');

                // Show loading state
                submitBtn.prop('disabled', true);
                icon.addClass('hidden');
                loader.removeClass('hidden');
                text.text('Updating...');

                $.ajax({
                    url: "{{ route('admin.payment-deposit.update.bank.details') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#updateBankModal').modal('hide');
                        tNotify('success', response.message);

                        // Immediate reload attempt
                        console.log('Immediate table reload attempt...');
                        try {
                            if (table && table.draw) {
                                table.draw();
                                console.log('Immediate table.draw() executed');
                            }
                        } catch (e) {
                            console.log('Immediate reload failed:', e);
                        }

                        // Force table reload with fresh data from server
                        try {
                            console.log('Attempting to reload table after update...');
                            if (typeof table !== 'undefined' && table.ajax && typeof table.ajax
                                .reload === 'function') {
                                console.log('Reloading table via ajax.reload');
                                table.ajax.reload(null, false); // false = keep current page
                            } else {
                                console.log('Table ajax not available, trying alternative reload');
                                // Try global variable first
                                if (typeof window.dataTable !== 'undefined' && window.dataTable
                                    .ajax && typeof window.dataTable.ajax.reload === 'function') {
                                    console.log('Reloading via global dataTable');
                                    window.dataTable.ajax.reload(null, false);
                                } else {
                                    // Alternative: Get DataTable instance and reload
                                    var dt = $('#dataTable').DataTable();
                                    if (dt && dt.ajax && typeof dt.ajax.reload === 'function') {
                                        console.log('Reloading via DataTable instance');
                                        dt.ajax.reload(null, false);
                                    } else {
                                        console.log(
                                            'DataTable reload not available, reloading page');
                                        location.reload();
                                    }
                                }
                            }
                        } catch (error) {
                            console.error('Error reloading table:', error);
                            location.reload();
                        }
                        updateStats(); // Update stats

                        // Additional simple reload as backup
                        setTimeout(function() {
                            console.log('Executing timeout table reload backup');
                            try {
                                // Try multiple direct reload methods
                                var dt = $('#dataTable').DataTable();
                                if (dt && dt.ajax && dt.ajax.reload) {
                                    dt.ajax.reload();
                                    console.log('Backup ajax.reload successful');
                                } else if (dt && dt.draw) {
                                    dt.draw();
                                    console.log('Backup draw() successful');
                                } else {
                                    console.log(
                                        'No DataTable methods available, forcing page reload'
                                    );
                                    location.reload();
                                }
                            } catch (e) {
                                console.log('All backup methods failed:', e);
                                location.reload();
                            }
                        }, 500);
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        tNotify('warning', response?.error || 'Failed to update bank details');
                    },
                    complete: function() {
                        // Always reset button state regardless of success or error
                        setTimeout(function() {
                            const submitBtn = $('#updateBankSubmitBtn');
                            const icon = $('#updateBankIcon');
                            const loader = $('#updateBankLoader');
                            const text = $('#updateBankText');

                            submitBtn.prop('disabled', false);
                            icon.removeClass('hidden');
                            loader.addClass('hidden');
                            text.text('{{ __('Update Bank Details') }}');
                        }, 100); // Small delay to ensure success actions complete first
                    }
                });
            });

            // Function to reset reject approved modal
            function resetRejectApprovedModal() {
                const submitBtn = $('#rejectApprovedSubmitBtn');
                const icon = $('#rejectApprovedIcon');
                const loader = $('#rejectApprovedLoader');
                const text = $('#rejectApprovedText');

                // Reset button state
                submitBtn.prop('disabled', false);
                icon.removeClass('hidden');
                loader.addClass('hidden');
                text.text('{{ __('Reject Request') }}');

                // Clear form
                $('#rejectApprovedForm')[0].reset();
            }

            // Reject Approved Request functionality
            $('#dataTable').on('click', '.reject-approved-btn', function() {
                let requestId = $(this).data('request-id');

                // Reset modal and button states
                resetRejectApprovedModal();

                // Populate request ID
                $('#rejectApprovedRequestId').val(requestId);

                // Show modal
                $('#rejectApprovedModal').modal('show');
            });

            // Handle reject approved form submission
            $('#rejectApprovedForm').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#rejectApprovedSubmitBtn');
                const icon = $('#rejectApprovedIcon');
                const loader = $('#rejectApprovedLoader');
                const text = $('#rejectApprovedText');

                // Show loading state
                submitBtn.prop('disabled', true);
                icon.addClass('hidden');
                loader.removeClass('hidden');
                text.text('Rejecting...');

                $.ajax({
                    url: "{{ route('admin.payment-deposit.reject.approved') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#rejectApprovedModal').modal('hide');
                        tNotify('success', response.message);

                        // Immediate reload attempt
                        console.log('Immediate table reload attempt after reject...');
                        try {
                            if (table && table.draw) {
                                table.draw();
                                console.log('Immediate table.draw() executed for reject');
                            }
                        } catch (e) {
                            console.log('Immediate reload failed for reject:', e);
                        }

                        // Force table reload with fresh data from server
                        try {
                            console.log('Attempting to reload table after reject...');
                            if (typeof table !== 'undefined' && table.ajax && typeof table.ajax
                                .reload === 'function') {
                                console.log('Reloading table via ajax.reload');
                                table.ajax.reload(null, false); // false = keep current page
                            } else {
                                console.log('Table ajax not available, trying alternative reload');
                                // Try global variable first
                                if (typeof window.dataTable !== 'undefined' && window.dataTable
                                    .ajax && typeof window.dataTable.ajax.reload === 'function') {
                                    console.log('Reloading via global dataTable');
                                    window.dataTable.ajax.reload(null, false);
                                } else {
                                    // Alternative: Get DataTable instance and reload
                                    var dt = $('#dataTable').DataTable();
                                    if (dt && dt.ajax && typeof dt.ajax.reload === 'function') {
                                        console.log('Reloading via DataTable instance');
                                        dt.ajax.reload(null, false);
                                    } else {
                                        console.log(
                                            'DataTable reload not available, reloading page');
                                        location.reload();
                                    }
                                }
                            }
                        } catch (error) {
                            console.error('Error reloading table:', error);
                            location.reload();
                        }
                        updateStats(); // Update stats

                        // Additional simple reload as backup
                        setTimeout(function() {
                            console.log('Executing timeout table reload backup');
                            try {
                                // Try multiple direct reload methods
                                var dt = $('#dataTable').DataTable();
                                if (dt && dt.ajax && dt.ajax.reload) {
                                    dt.ajax.reload();
                                    console.log('Backup ajax.reload successful');
                                } else if (dt && dt.draw) {
                                    dt.draw();
                                    console.log('Backup draw() successful');
                                } else {
                                    console.log(
                                        'No DataTable methods available, forcing page reload'
                                    );
                                    location.reload();
                                }
                            } catch (e) {
                                console.log('All backup methods failed:', e);
                                location.reload();
                            }
                        }, 500);
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        tNotify('warning', response?.error || 'Failed to reject request');
                    },
                    complete: function() {
                        // Always reset button state regardless of success or error
                        setTimeout(function() {
                            const submitBtn = $('#rejectApprovedSubmitBtn');
                            const icon = $('#rejectApprovedIcon');
                            const loader = $('#rejectApprovedLoader');
                            const text = $('#rejectApprovedText');

                            submitBtn.prop('disabled', false);
                            icon.removeClass('hidden');
                            loader.addClass('hidden');
                            text.text('{{ __('Reject Request') }}');
                        }, 100); // Small delay to ensure success actions complete first
                    }
                });
            });

            // Reset modals when closed
            $('#updateBankModal').on('hidden.bs.modal', function() {
                resetUpdateBankModal();
            });

            $('#rejectApprovedModal').on('hidden.bs.modal', function() {
                resetRejectApprovedModal();
            });

        })(jQuery);
    </script>
@endsection
