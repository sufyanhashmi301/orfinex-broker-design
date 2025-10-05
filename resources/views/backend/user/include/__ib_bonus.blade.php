<div class="tab-pane space-y-5 fade" id="pills-bonus" role="tabpanel" aria-labelledby="pills-bonus-tab">
    <div class="card">
        <div class="card-header flex-col !items-start gap-5">
            <div class="flex justify-between w-full gap-3">
                <h4 class="card-title">{{ __('IB Bonus') }}</h4>
                @canany(['customer-master-ib-network-distribution', 'customer-child-ib-distribution'])
                    <div class="flex justify-end items-center gap-2">
                        @can('customer-master-ib-network-distribution')
                            @if ($user->ib_status == \App\Enums\IBStatus::APPROVED)
                                <button type="button" id="master-ib-distribution-btn"
                                    class="btn btn-dark btn-sm inline-flex items-center justify-center">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="carbon:network-4"></iconify-icon>
                                    {{ __('Master IB Network Distribution') }}
                                </button>
                            @endif
                        @endcan

                        @can('customer-child-ib-distribution')
                            @if ($user->ib_status !== \App\Enums\IBStatus::APPROVED && isset($user->ref_id))
                                <button type="button" id="child-ib-distribution-btn"
                                    class="btn btn-dark btn-sm inline-flex items-center justify-center">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="carbon:network-4"></iconify-icon>
                                    {{ __('Child IB Distribution') }}
                                </button>
                            @endif
                        @endcan
                    </div>
                @endcanany
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center w-full gap-3">
                <!-- Filter Inputs -->
                <form id="filter-form" class="w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                    <div class="flex-1 input-area relative">
                        <input type="text" id="ib-bonus-login" class="form-control h-full" placeholder="Login">
                    </div>
                    <div class="flex-1 input-area relative">
                        <input type="text" id="ib-bonus-deal" class="form-control h-full" placeholder="Deal">
                    </div>
                    <div class="flex-1 input-area relative">
                        <input type="text" id="ib-bonus-symbol" class="form-control h-full" placeholder="Symbol">
                    </div>
                    <div class="flex-1 input-area relative">
                        <select id="ib-bonus-date-filter" class="form-control">
                            <option value="">{{ __('Select Days') }}</option>
                            <option value="3_days">{{ __('Last 3 Days') }}</option>
                            <option value="5_days">{{ __('Last 5 Days') }}</option>
                            <option value="15_days">{{ __('Last 15 Days') }}</option>
                            <option value="1_month">{{ __('Last 1 Month') }}</option>
                            <option value="3_months">{{ __('Last 3 Months') }}</option>
                        </select>
                    </div>
                    <div class="flex-1 input-area relative">
                        <input type="text" id="ib-bonus-created-at"
                            class="form-control flatpickr-created-at h-full w-full" placeholder="Created At Range"
                            readonly>
                    </div>
                    <div class="input-area relative">
                        <button type="button" id="ib-bonus-filter-btn"
                            class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                icon="lucide:filter"></iconify-icon>
                            {{ __('Filter') }}
                        </button>
                    </div>
                </form>
                @can('customer-ib-bonus-export')
                    <form method="POST" id="ib-bonus-export-form"
                        action="{{ route('admin.user.export', ['type' => 'ibtransaction', 'user_id' => $user->id]) }}">
                        @csrf
                        <input type="hidden" name="login" id="export-ib-bonus-login">
                        <input type="hidden" name="deal" id="export-ib-bonus-deal">
                        <input type="hidden" name="symbol" id="export-ib-bonus-symbol">
                        <input type="hidden" name="date_filter" id="export-ib-bonus-date-filter">
                        <input type="hidden" name="created_at" id="export-ib-bonus-created-at">
                        <div class="input-area relative mb-1">
                            <button type="submit"
                                class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                    icon="lets-icons:export-fill"></iconify-icon>
                                {{ __('Export') }}
                            </button>
                        </div>
                    </form>
                @endcan
            </div>
        </div>
        <div class="card-body px-6 pt-3">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" id="ib-summary-cards" style="display: none;">
                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('Total IB Received') }}</div>
                    <div class="text-lg font-semibold text-slate-900 dark:text-white" id="ib-balance">$0.00</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('Lifetime Total') }}</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <div class="text-xs text-green-600 dark:text-green-400 mb-1">{{ __('Current IB Wallet') }}</div>
                    <div class="text-lg font-semibold text-green-700 dark:text-green-300" id="current-ib-wallet">$0.00
                    </div>
                    <div class="text-xs text-green-600 dark:text-green-400">{{ __('Available Balance') }}</div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('Filtered Results') }}</div>
                    <div class="text-lg font-semibold text-slate-900 dark:text-white" id="filtered-amount">$0.00</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400" id="filtered-count">0 showing</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <div class="text-xs text-blue-600 dark:text-blue-400 mb-1">{{ __('Filter Range') }}</div>
                    <div class="text-sm font-semibold text-blue-700 dark:text-blue-300" id="oldest-entry-date">
                        {{ __('All time') }}</div>
                    <div class="text-xs text-blue-600 dark:text-blue-400" id="date-range-info">
                        {{ __('No date filter') }}</div>
                </div>
            </div>

            <div class="overflow-x-auto -mx-6 dashcode-data-table relative">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                            id="user-ib-transaction-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('Detail') }}</th>
                                    <th scope="col" class="table-th">{{ __('Transaction ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="ib-processingIndicator"
                    class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-slate-800/80 hidden z-10">
                    <div class="text-center">
                        <iconify-icon class="spining-icon text-5xl dark:text-slate-100"
                            icon="lucide:loader"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="transaction-action-modal" tabindex="-1" aria-labelledby="deposit-action-modal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="popup-body-text deposit-action">

                </div>
            </div>
        </div>
    </div>
</div>

@include('backend.user.include.__child_ib_distribution')
@include('backend.user.include.__master_ib_distribution')
@push('single-script')
    <script>
        flatpickr(".flatpickr-created-at", {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        $(document).ready(function() {
            const table = $('#user-ib-transaction-dataTable')
                .on('processing.dt', function(e, settings, processing) {
                    $('#ib-processingIndicator').css('display', processing ? 'block' : 'none');
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
                    },
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('admin.user.ib_bonus', $user->id) }}",
                        data: function(d) {
                            d.login = $('#ib-bonus-login').val();
                            d.deal = $('#ib-bonus-deal').val();
                            d.order = $('#ib-bonus-order').val();
                            d.symbol = $('#ib-bonus-symbol').val();
                            d.created_at = $('#ib-bonus-created-at').val();
                            d.date_filter = $('#ib-bonus-date-filter').val();
                        },
                        dataSrc: function(json) {
                            // Update summary cards with data from server
                            if (json.summary) {
                                updateSummaryCards(json.summary);
                            }
                            return json.data;
                        }
                    },
                    columns: [{
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'tnx',
                            name: 'tnx'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'target_id',
                            name: 'target_id'
                        },
                        {
                            data: 'final_amount',
                            name: 'final_amount'
                        },
                        {
                            data: 'method',
                            name: 'method'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ]
                });

            // Function to update summary cards
            function updateSummaryCards(summary) {
                // Update IB Balance (from user model)
                $('#ib-balance').text('$' + summary.ib_balance);

                // Update Current IB Wallet Balance
                $('#current-ib-wallet').text('$' + summary.current_ib_wallet_balance);

                // Show filtered results
                $('#filtered-amount').text('$' + summary.total_amount);
                $('#filtered-count').text(summary.total_count + ' showing');

                // Update filter date range
                if (summary.filter_start_date && summary.filter_end_date) {
                    if (summary.filter_start_date === summary.filter_end_date) {
                        $('#oldest-entry-date').text(summary.filter_start_date);
                        $('#date-range-info').text('Single day filter');
                    } else {
                        $('#oldest-entry-date').text(summary.filter_start_date + ' - ' + summary.filter_end_date);
                        $('#date-range-info').text('Filter date range');
                    }
                } else {
                    $('#oldest-entry-date').text('All time');
                    $('#date-range-info').text('No date filter');
                }

                // Show summary cards
                $('#ib-summary-cards').show();
            }

            // Function to get date ranges for predefined options
            function getDateRanges() {
                const today = new Date();
                const formatDate = (date) => {
                    return date.getFullYear() + '-' +
                        String(date.getMonth() + 1).padStart(2, '0') + '-' +
                        String(date.getDate()).padStart(2, '0');
                };

                return {
                    '3_days': {
                        start: formatDate(new Date(today.getTime() - 3 * 24 * 60 * 60 * 1000)),
                        end: formatDate(today)
                    },
                    '5_days': {
                        start: formatDate(new Date(today.getTime() - 5 * 24 * 60 * 60 * 1000)),
                        end: formatDate(today)
                    },
                    '15_days': {
                        start: formatDate(new Date(today.getTime() - 15 * 24 * 60 * 60 * 1000)),
                        end: formatDate(today)
                    },
                    '1_month': {
                        start: formatDate(new Date(today.getFullYear(), today.getMonth() - 1, today.getDate())),
                        end: formatDate(today)
                    },
                    '3_months': {
                        start: formatDate(new Date(today.getFullYear(), today.getMonth() - 3, today.getDate())),
                        end: formatDate(today)
                    }
                };
            }

            // Handle predefined date range selection
            $('#ib-bonus-date-filter').on('change', function() {
                const selectedRange = $(this).val();
                const ranges = getDateRanges();

                if (selectedRange && ranges[selectedRange]) {
                    const range = ranges[selectedRange];
                    const dateRangeString = range.start + ' to ' + range.end;
                    $('#ib-bonus-created-at').val(dateRangeString);

                    // Trigger table reload
                    table.ajax.reload();
                } else if (selectedRange === '') {
                    // Clear date filter
                    $('#ib-bonus-created-at').val('');
                    table.ajax.reload();
                }
            });

            $('#ib-bonus-filter-btn').on('click', function() {
                table.ajax.reload();
            });
            $('#ib-bonus-export-form').on('submit', function() {
                $('#export-ib-bonus-login').val($('#ib-bonus-login').val());
                $('#export-ib-bonus-deal').val($('#ib-bonus-deal').val());
                $('#export-ib-bonus-symbol').val($('#ib-bonus-symbol').val());
                $('#export-ib-bonus-date-filter').val($('#ib-bonus-date-filter').val());
                $('#export-ib-bonus-created-at').val($('#ib-bonus-created-at').val());
            });
            // 👁️ Modal action
            $('body').on('click', '#deposit-action', function() {
                $('.deposit-action').empty();
                const id = $(this).data('id');
                $.ajax({
                    url: '{{ route('admin.transactions.view', ':id') }}'.replace(':id', id),
                    method: 'GET',
                    success: function(response) {
                        $('.deposit-action').append(response);
                        imagePreview();
                        $('#transaction-action-modal').modal('show');
                    }
                });
            });
        });
        flatpickr(".flatpickr-master-ib", {
            enableTime: true,
            dateFormat: "Y-m-d H:i:S", // Sent to backend (value of input)
            altInput: true,
            altFormat: "Y-m-d", // Shown to user
            maxDate: "today",
            defaultDate: new Date(),
            time_24hr: true,
            allowInput: true,
            minuteIncrement: 1,
            disableMobile: "true",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    instance.close();
                }
            }
        });


        // Show modal
        $('#master-ib-distribution-btn').on('click', function() {
            $('#master-ib-modal').modal('show');
        });

        $('#child-ib-distribution-btn').on('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('child-ib-modal'));
            myModal.show();
        });
    </script>
@endpush
