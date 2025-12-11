@extends('backend.layouts.app')

@section('title')
    {{ __('Forex Statement Logs') }}
@endsection

@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('forex-statement-logs-clear')
                <button type="button" id="clearLogsBtn" class="btn btn-sm btn-danger inline-flex items-center justify-center @if($existLog == 0) disabled opacity-50 cursor-not-allowed @endif">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:trash-2"></iconify-icon>
                    {{ __('Clear Logs') }}
                </button>
            @endcan
        </div>
    </div>

    <div class="card">
        <!-- Data Table -->
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="logsTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Account Login') }}</th>
                                    <th scope="col" class="table-th">{{ __('User Email') }}</th>
                                    <th scope="col" class="table-th">{{ __('Statement Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('PDF Size') }}</th>
                                    <th scope="col" class="table-th">{{ __('Sent At') }}</th>
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

    <!-- Clear Logs Modal -->
    @include('backend.investment.modal.__clear_logs_modal')

@endsection

@section('script')
    <script>
        (function($) {
            "use strict";

            var table = $('#logsTable')
                .on('processing.dt', function(e, settings, processing) {
                    $('#processingIndicator').css('display', processing ? 'block' : 'none');
                }).DataTable({
                    dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                    processing: true,
                    searching: false,
                    lengthChange: false,
                    info: true,
                    order: [[5, 'desc']], // Sort by sent_at column
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
                        url: '{{ route("admin.forex.statement-logs") }}',
                    },
                    columns: [
                        { data: 'account_login', name: 'account_login' },
                        { data: 'user_email', name: 'user_email' },
                        { data: 'statement_date', name: 'statement_date' },
                        { data: 'status_badge', name: 'status', orderable: false },
                        { data: 'file_size', name: 'pdf_size' },
                        { data: 'sent_at', name: 'sent_at' }
                    ],

                });

            // Clear logs button handler
            $('#clearLogsBtn').on('click', function() {
                // Check if button is disabled
                if ($(this).hasClass('disabled') || $(this).prop('disabled')) {
                    return false;
                }
                $('#deleteLogConfirmation').modal('show');
            });

            // Handle clear logs confirmation
            $(document).on('click', '.confirm-clear-logs', function() {
                var $btn = $(this);
                
                // Disable button
                $btn.prop('disabled', true).html('<iconify-icon class="animate-spin ltr:mr-2 rtl:ml-2" icon="lucide:loader"></iconify-icon>{{ __("Clearing...") }}').css('cursor', 'not-allowed');
                
                $.ajax({
                    url: '{{ route("admin.forex.statement-logs.clear") }}',
                    type: 'POST',
                    data: {
                        clear_option: 'all',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            tNotify('success', response.message);
                            
                            $('#deleteLogConfirmation').modal('hide');
                            table.ajax.reload();
                        } else {
                            tNotify('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        tNotify('error', xhr.responseJSON.message || '{{ __("Failed to clear logs") }}');
                    },
                    complete: function() {
                        // Re-enable button
                        $btn.prop('disabled', false).html('<iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>{{ __("Cleared") }}');
                        $btn.css('cursor', 'pointer');
                    }
                });
            });

            // Auto-refresh every 30 seconds
            setInterval(function() {
                table.ajax.reload(null, false); // Don't reset pagination
            }, 30000);

        })(jQuery);
    </script>
@endsection