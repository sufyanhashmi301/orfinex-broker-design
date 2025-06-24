@extends('backend.layouts.app')
@section('title')
    {{ __('Transactions') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('All Transactions') }}
        </h4>
        <div class="sm:space-x-4 space-x-2 rtl:space-x-reverse">
            <a href="{{ route('admin.transactions.report') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="iconoir:stats-report"></iconify-icon>
                {{ __('Detailed Report') }}
            </a>
        </div>
    </div>

    <div class="innerMenu card p-6 mb-5">
        <form id="filter-form" method="POST" action="{{ route('admin.transactions.export') }}">
            @csrf
            <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
                <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                    <div class="flex-1 input-area relative">
                        <input type="text" name="email" id="email" class="form-control h-full" placeholder="Search User By Email">
                    </div>
                    <div class="flex-1 input-area relative">
                        <select name="status" class="form-control h-full" id="status">
                            <option value="">{{ __('Status') }}</option>
                            <option value="success">{{ __('Success') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="failed">{{ __('Cancelled') }}</option>
                        </select>
                    </div>
                    <div class="flex-1 input-area relative">
                        <select name="type" class="form-control h-full" id="type">
                            <option value="">Transaction Type</option>
                            @foreach (getFilteredTxnTypes() as $txnType)
                                <option value="{{ $txnType->value }}">{{ $txnType->label() }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="flex-1 input-area relative">
                        <input type="date" name="created_at" id="created_at" class="form-control h-full flatpickr flatpickr-input active" data-mode="range" placeholder="Created At">
                    </div>

                </div>
                <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                    <div class="input-area relative">
                        <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                            {{ __('Apply Filter') }}
                        </button>
                    </div>
                    {{-- @can('transaction-export') --}}
                    <div class="input-area relative">
                        <form method="POST" action="{{ route('user.history.transactions.export') }}">
                            @csrf
                            <input type="hidden" name="query" value="{{ request('query') }}">
                            <input type="hidden" name="date" value="{{ request('transaction_date') }}">
                            <input type="hidden" name="status" value="{{ request('transaction_status') }}">
                            <input type="hidden" name="type" value="{{ request('transaction_type') }}">
                            <input type="hidden" name="forex_account" value="{{ request('forex_account') }}">
                            <button type="submit" class="btn btn-sm btn-white inline-flex items-center justify-center min-w-max">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                                {{ __('Export') }}
                            </button>
                        </form>

                    </div>
                    {{-- @endcan --}}
                    <div class="input-area relative">
                        <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                            <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="flex justify-end">
            <div id="txn-summary" class="flex flex-wrap flex-1 max-w-[516px] gap-3 mt-8 hidden">
                <div class="flex-1 bg-info-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                    <h4 class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                        {{ __('Total') }}
                    </h4>
                    <div class="block text-xl text-slate-900 dark:text-white font-medium">
                        {{ $currencySymbol }}
                        <span id="summary-total">0</span>
                    </div>
                </div>
                <div class="flex-1 bg-success-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                    <h4 class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                        {{ __('Completed') }}
                    </h4>
                    <div class="block text-xl text-slate-900 dark:text-white font-medium">
                        {{ $currencySymbol }}
                        <span id="summary-success">0</span>
                    </div>
                </div>
                <div class="flex-1 bg-warning-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                    <h4 class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                        {{ __('Pending') }}
                    </h4>
                    <div class="block text-xl text-slate-900 dark:text-white font-medium">
                        {{ $currencySymbol }}
                        <span id="summary-pending">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Detail') }}</th>
                                    <th scope="col" class="table-th">{{ __('Transaction ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action By') }}</th>
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
    @can('transaction-action')
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="transaction-action-modal" tabindex="-1" aria-labelledby="deposit-action-modal" aria-hidden="true">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
              <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                    <div class="modal-body popup-body">
                        <div class="popup-body-text deposit-action">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
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
                ajax: {
                    url: "{{ route('admin.transactions') }}",
                    data: function (d) {
                        d.email = $('#email').val();
                        d.status = $('#status').val();
                        d.type = $('#type').val();
                        d.status = $('#status').val();
                        d.created_at = $('#created_at').val();

                    },
                    dataSrc: function (json) {
                        if (json.summary) {
                            $('#summary-total').text(json.summary.total ?? 0);
                            $('#summary-success').text(json.summary.success ?? 0);
                            $('#summary-pending').text(json.summary.pending ?? 0);
                            $('#txn-summary').removeClass('hidden');
                        }
                        return json.data;
                    }
                },
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'username', name: 'username'},
                    {data: 'description', name: 'description'},
                    {data: 'tnx', name: 'tnx'},
                    {data: 'type', name: 'type'},
                    {data: 'target_id', name: 'target_id'},
                    {data: 'final_amount', name: 'final_amount'},
                    {data: 'method', name: 'method'},
                    {data: 'action_by', name: 'action_by'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });

            $('#filter').click(function () {
                table.draw();
            });
            $('#filter-form').on('keypress', function(e) {
                if (e.which === 13) { // 13 is the Enter key code
                    e.preventDefault(); // Prevent form submission
                    table.draw(); // Trigger filtering only
                    return false;
                }
            });
            $('body').on('click', '#deposit-action', function () {
                $('.deposit-action').empty();

                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route("admin.transactions.view", ":id") }}'.replace(':id', id),
                    method: 'GET',
                    success: function(response) {
                        $('.deposit-action').append(response);
                        imagePreview();
                        $('#transaction-action-modal').modal('show');

                        tippy(".shift-Away", {
                            placement: "top",
                            animation: "shift-away"
                        });

                    }
                });
            });
        })(jQuery);

    </script>
@endsection
