<div
    class="tab-pane space-y-5 fade"
    id="pills-bonus"
    role="tabpanel"
    aria-labelledby="pills-bonus-tab"
>
    <div class="card">
        <div class="card-header flex-col !items-start gap-5">
            <div class="flex justify-between w-full gap-3">
                <h4 class="card-title">{{ __('IB Bonus') }}</h4>
                @canany(['customer-master-ib-network-distribution', 'customer-child-ib-distribution'])
                    <div class="flex justify-end items-center gap-2">
                        @can('customer-master-ib-network-distribution')
                            @if ($user->ib_status == \App\Enums\IBStatus::APPROVED)
                                <button type="button" id="master-ib-distribution-btn" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="carbon:network-4"></iconify-icon>
                                    {{ __('Master IB Network Distribution') }}
                                </button>
                            @endif
                        @endcan

                        @can('customer-child-ib-distribution')
                            @if ($user->ib_status !== \App\Enums\IBStatus::APPROVED && isset($user->ref_id))
                                <button type="button" id="child-ib-distribution-btn" class="btn btn-dark btn-sm inline-flex items-center justify-center">
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
                        <input type="text" id="ib-bonus-created-at" class="form-control flatpickr-created-at h-full w-full" placeholder="Created At Range" readonly>
                    </div>
                    <div class="input-area relative">
                        <button type="button" id="ib-bonus-filter-btn" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                            {{ __('Filter') }}
                        </button>
                    </div>
                </form>
                @can('customer-ib-bonus-export')
                    <form method="POST" action="{{ route('admin.user.export', ['type' => 'ibtransaction', 'user_id' => $user->id]) }}">
                        @csrf
                        <div class="input-area relative mb-1">
                            <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                                {{ __('Export') }}
                            </button>
                        </div>
                    </form>
                @endcan
            </div>
        </div>
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="user-ib-transaction-dataTable">
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
            </div>
        </div>
    </div>
</div>
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="transaction-action-modal" tabindex="-1" aria-labelledby="deposit-action-modal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
      <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
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

        $(document).ready(function () {
            const table = $('#user-ib-transaction-dataTable').DataTable({
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
                    data: function (d) {
                        d.login = $('#ib-bonus-login').val();
                        d.deal = $('#ib-bonus-deal').val();
                        d.order = $('#ib-bonus-order').val();
                        d.symbol = $('#ib-bonus-symbol').val();
                        d.created_at = $('#ib-bonus-created-at').val();
                    }
                }
                ,
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'description', name: 'description'},
                    {data: 'tnx', name: 'tnx'},
                    {data: 'type', name: 'type'},
                    {data: 'target_id', name: 'target_id'},
                    {data: 'final_amount', name: 'final_amount'},
                    {data: 'method', name: 'method'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });


            $('#ib-bonus-filter-btn').on('click', function () {
                table.ajax.reload();
            });

            // 👁️ Modal action
            $('body').on('click', '#deposit-action', function () {
                $('.deposit-action').empty();
                const id = $(this).data('id');
                $.ajax({
                    url: '{{ route("admin.transactions.view", ":id") }}'.replace(':id', id),
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
