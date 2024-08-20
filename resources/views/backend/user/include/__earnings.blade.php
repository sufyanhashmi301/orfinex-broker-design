<div
    class="tab-pane space-y-5 fade"
    id="pills-deposit"
    role="tabpanel"
    aria-labelledby="pills-deposit-tab"
>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Earnings') }}</h4>
            <div class="card-header-info">
                {{ __('Total Earnings:') }} {{ $user->totalProfit() }} {{ $currency }}
            </div>
        </div>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="user-profit-dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit From') }}</th>
                                    <th scope="col" class="table-th">{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('single-script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#user-profit-dataTable').DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],
                language: {
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
                search: "Search:"
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.all-profits',$user->id) }}",
                columns: [
                    {"class": "table-td", data: 'created_at', name: 'created_at'},
                    {"class": "table-td", data: 'final_amount', name: 'final_amount'},
                    {"class": "table-td", data: 'type', name: 'type'},
                    {"class": "table-td", data: 'profit_from', name: 'profit_from'},
                    {"class": "table-td", data: 'description', name: 'description'},

                ]
            });

        })(jQuery);
    </script>
@endpush
