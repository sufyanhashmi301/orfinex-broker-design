<div
    class="tab-pane space-y-5 fade"
    id="pills-transactions"
    role="tabpanel"
    aria-labelledby="pills-transactions-tab"
>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Transactions') }}</h4>
            <div class="flex items-center space-x-2 sm:rtl:space-x-reverse">
                @can('customer-transactions-stats')
                    <a href="{{ route('admin.transactions.user-summary', $user->id) }}" target="_blank" class="btn btn-sm btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:chart-pie"></iconify-icon>
                        <span>{{ __('Payment stats') }}</span>
                    </a>
                @endcan
                @can('customer-transactions-export')
                    <form method="POST" action="{{ route('admin.user.export', ['type' => 'transaction', 'user_id' => $user->id]) }}">
                        @csrf
                        <div class="input-area relative">
                            <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                                {{ __('Export') }}
                            </button>
                        </div>
                    </form>
                @endcan
            </div>
        </div>
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="user-transaction-dataTable">
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
                                    <th scope="col" class="table-th">{{ __('Action By') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="processingIndicator processingIndicator-transactions text-center">
                {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>
</div>
@push('single-script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#user-transaction-dataTable')
                .on('processing.dt', function(e, settings, processing) {
                    $('.processingIndicator-transactions').css('display', processing ? 'block' : 'none');
                }).DataTable({
                    dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                    searching: false,
                    lengthChange: false,
                    info: true,
                    order: [[0, 'desc']],
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
                    ajax: "{{ route('admin.user.transaction',$user->id) }}",
                    columns: [
                        {data: 'created_at', name: 'created_at', orderable: true},
                        {data: 'description', name: 'description', orderable: true},
                        {data: 'tnx', name: 'tnx', orderable: true},
                        {data: 'type', name: 'type', orderable: true},
                        {data: 'target_id', name: 'target_id', orderable: true},
                        {data: 'final_amount', name: 'final_amount', orderable: true},
                        {data: 'method', name: 'method', orderable: true},
                        {data: 'status', name: 'status', orderable: true},
                        {data: 'action_by', name: 'action_by', orderable: true},
                    ]
            });
        })(jQuery);
    </script>
@endpush
