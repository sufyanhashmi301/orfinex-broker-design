@extends('backend.activity_tracking.index')
@section('title')
    {{ __('Staff Activities') }}
@endsection

@section('filters')
    <form id="filter-form" method="GET" action="{{ route('admin.activity-logs.export') }}">
        <input type="hidden" name="actor_type" value="admin">
        <div class="flex flex-col sm:flex-row justify-between flex-wrap gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-9"
                        placeholder="Search by Name, Email, IP, Location, Description" value="{{ request('global_search') }}">
                </div>
                <div class="flex-1 input-area relative">
                    <select name="action" id="action" class="form-control w-full h-9">
                        <option value="" selected>{{ __('All Actions') }}</option>
                        <optgroup label="{{ __('Authentication') }}">
                        <option value="admin_login" {{ request('action') == 'admin_login' ? 'selected' : '' }}>{{ __('Admin Login') }}</option>
                        <option value="admin_logout" {{ request('action') == 'admin_logout' ? 'selected' : '' }}>{{ __('Admin Logout') }}</option>
                        <option value="login_failed" {{ request('action') == 'login_failed' ? 'selected' : '' }}>{{ __('Login Failed') }}</option>
                        </optgroup>
                        <optgroup label="{{ __('Financial') }}">
                            <option value="add_deposit" {{ request('action') == 'add_deposit' ? 'selected' : '' }}>{{ __('Add Deposit') }}</option>
                            <option value="deposit_approved" {{ request('action') == 'deposit_approved' ? 'selected' : '' }}>{{ __('Deposit Approved') }}</option>
                            <option value="deposit_rejected" {{ request('action') == 'deposit_rejected' ? 'selected' : '' }}>{{ __('Deposit Rejected') }}</option>
                            <option value="add_withdraw" {{ request('action') == 'add_withdraw' ? 'selected' : '' }}>{{ __('Add Withdraw') }}</option>
                            <option value="withdraw_approved" {{ request('action') == 'withdraw_approved' ? 'selected' : '' }}>{{ __('Withdraw Approved') }}</option>
                            <option value="withdraw_rejected" {{ request('action') == 'withdraw_rejected' ? 'selected' : '' }}>{{ __('Withdraw Rejected') }}</option>
                        </optgroup>
                        <optgroup label="{{ __('Forex Account') }}">
                            <option value="forex_account_create" {{ request('action') == 'forex_account_create' ? 'selected' : '' }}>{{ __('Forex Account Create') }}</option>
                            <option value="forex_account_update" {{ request('action') == 'forex_account_update' ? 'selected' : '' }}>{{ __('Forex Account Update') }}</option>
                            <option value="forex_account_delete" {{ request('action') == 'forex_account_delete' ? 'selected' : '' }}>{{ __('Forex Account Delete') }}</option>
                            <option value="forex_account_action" {{ request('action') == 'forex_account_action' ? 'selected' : '' }}>{{ __('Forex Account Action') }}</option>
                        </optgroup>
                        <optgroup label="{{ __('User Management') }}">
                            <option value="user_create" {{ request('action') == 'user_create' ? 'selected' : '' }}>{{ __('User Create') }}</option>
                            <option value="user_update" {{ request('action') == 'user_update' ? 'selected' : '' }}>{{ __('User Update') }}</option>
                            <option value="user_delete" {{ request('action') == 'user_delete' ? 'selected' : '' }}>{{ __('User Delete') }}</option>
                            <option value="profile_update" {{ request('action') == 'profile_update' ? 'selected' : '' }}>{{ __('Profile Update') }}</option>
                            <option value="password_update" {{ request('action') == 'password_update' ? 'selected' : '' }}>{{ __('Password Update') }}</option>
                        </optgroup>
                        <optgroup label="{{ __('KYC') }}">
                            <option value="kyc_submission" {{ request('action') == 'kyc_submission' ? 'selected' : '' }}>{{ __('KYC Submission') }}</option>
                            <option value="kyc_approve" {{ request('action') == 'kyc_approve' ? 'selected' : '' }}>{{ __('KYC Approve') }}</option>
                            <option value="kyc_reject" {{ request('action') == 'kyc_reject' ? 'selected' : '' }}>{{ __('KYC Reject') }}</option>
                        </optgroup>
                    </select>
                </div>
                <div class="flex-1 input-area relative">
                    <select id="rangeSelect" class="form-control w-full h-9">
                        <option value="" selected>{{ __('-- Select Range --') }}</option>
                        <option value="today">{{ __('Today') }}</option>
                        <option value="yesterday">{{ __('Yesterday') }}</option>
                        <option value="last7">{{ __('Last 7 Days') }}</option>
                        <option value="last30">{{ __('Last 30 Days') }}</option>
                        <option value="thisMonth">{{ __('This Month') }}</option>
                        <option value="lastMonth">{{ __('Last Month') }}</option>
                        <option value="ytd">{{ __('Year to Date') }}</option>
                        <option value="custom">{{ __('Custom Range') }}</option>
                    </select>
                </div>
                <div class="flex-1 input-area">
                    <div class="relative">
                        <input type="date" name="created_at" id="created_at" class="form-control h-9 !pr-9" data-mode="range" placeholder="Created At" value="{{ request('created_at') }}">
                        <button id="clearBtn" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                            <iconify-icon icon="mdi:window-close"></iconify-icon>
                        </button>
                    </div>
                    <span class="text-xs font-light dark:text-slate-200">
                        {{ __('Double click for a single date') }}
                    </span>
                </div>
            </div>

            <div class="flex sm:space-x-3 space-x-2">
                <div class="input-area relative">
                    <button type="button" id="filter"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max h-9 bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="submit"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max h-9 bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                            icon="lets-icons:export-fill"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('activity-content')
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
                                    <th scope="col" class="table-th">{{ __('Staff Member') }}</th>
                                    <th scope="col" class="table-th">{{ __('Activity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Location') }}</th>
                                    <th scope="col" class="table-th">{{ __('Time') }}</th>
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
@endsection

@section('activity-script')
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
                    url: "{{ route('admin.activity-logs.staff') }}",
                    data: function (d) {
                        // Add filter parameters
                        d.global_search = $('#global_search').val();
                        d.action = $('#action').val();
                        d.status = $('#status').val();
                        d.created_at = $('#created_at').val();
                    }
                },
                columns: [
                    {data: 'actor', name: 'actor'},
                    {data: 'action', name: 'action'},
                    {data: 'location', name: 'location'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action_btn', name: 'action_btn', orderable: false, searchable: false},
                ]
            });

            // Filter functionality
            $('#filter').on('click', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Auto-reload table when filters change
            $('#global_search, #action, #status').on('change keyup', function() {
                clearTimeout(window.filterTimeout);
                window.filterTimeout = setTimeout(function() {
                    table.ajax.reload();
                }, 500);
            });

            // Handle date range presets
            $('#rangeSelect').on('change', function() {
                var range = $(this).val();
                var dateInput = $('#created_at');
                
                if (range) {
                    dateInput.val(range);
                    table.ajax.reload();
                }
            });

            // Clear date filter
            $('#clearBtn').on('click', function() {
                $('#created_at').val('');
                $('#rangeSelect').val('');
                table.ajax.reload();
            });

            // Handle date input changes
            $('#created_at').on('change', function() {
                $('#rangeSelect').val(''); // Clear preset selection
                table.ajax.reload();
            });

        })(jQuery);

    </script>
@endsection