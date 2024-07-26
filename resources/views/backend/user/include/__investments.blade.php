<div
    class="tab-pane space-y-5 fade"
    id="pills-transfer"
    role="tabpanel"
    aria-labelledby="pills-transfer-tab"
>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Account') }}</h4>
        </div>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="user-forex-account-dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    {{--<th scope="col" class="table-th">{{ __('Icon') }}</th>--}}
                                    <th scope="col" class="table-th">{{ __('Schema') }}</th>
                                    <th scope="col" class="table-th">{{ __('Login') }}</th>
                                    <th scope="col" class="table-th">{{ __('Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Credit') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

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
            var table = $('#user-forex-account-dataTable').DataTable();
            table.destroy();
            var table = $('#user-forex-account-dataTable').DataTable({
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
                ajax: "{{ route('admin.forex-accounts',['type'=>'real',$user->id]) }}",
                columns: [
                    // {"class": "table-td", data: 'icon', name: 'icon'},
                    {"class": "table-td", data: 'schema', name: 'schema'},
                    {"class": "table-td", data: 'login', name: 'login'},
                    {"class": "table-td", data: 'group', name: 'group'},
                    {"class": "table-td", data: 'balance', name: 'balance'},
                    {"class": "table-td", data: 'equity', name: 'equity'},
                    {"class": "table-td", data: 'credit', name: 'credit'},
                ]
            });
        })(jQuery);
    </script>
@endpush
