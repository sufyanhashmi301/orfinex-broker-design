@extends('backend.layouts.rms')
@section('title')
    {{ __('Active Positions') }}
@endsection
@section('content')
    @php
        use App\Models\PlatformGroup;
        $groups = PlatformGroup::all();
    @endphp
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="innerMenu card p-5 mb-3">
        <div class="flex items-center gap-3">
            <div class="max-w-xl w-full input-area relative flex items-center gap-5">
                <label for="group" class="form-label !w-auto min-w-max">{{ __('Select Group:') }}</label>
                <select id="group" class="form-control w-full">
                    <option value="" disabled selected>Select a group</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->group }}">{{ $group->group }}</option>
                    @endforeach
                </select>
            </div>
            <button id="fetch-positions" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                {{ __('Fetch Positions') }}
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="positions-table">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Login') }}</th>
                                <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                                <th scope="col" class="table-th">{{ __('Position') }}</th>
                                <th scope="col" class="table-th">{{ __('Open Price') }}</th>
                                <th scope="col" class="table-th">{{ __('Current Price') }}</th>
                                <th scope="col" class="table-th">{{ __('SL') }}</th>
                                <th scope="col" class="table-th">{{ __('TP') }}</th>
                                <th scope="col" class="table-th">{{ __('Volume') }}</th>
                                <th scope="col" class="table-th">{{ __('Profit') }}</th>
                                <th scope="col" class="table-th">{{ __('Profit Rate') }}</th>
                                <th scope="col" class="table-th">{{ __('Margin Rate') }}</th>
                                <th scope="col" class="table-th">{{ __('Reason') }}</th>
                                <th scope="col" class="table-th">{{ __('Created') }}</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="processingIndicator" class="text-center hidden">
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            const positionsTable = $('#positions-table').DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                processing: true,
                searching: false,
                lengthChange: false,
                info: true,
                autoWidth: false,
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
                data: [], // Start with an empty data array
                columns: [
                    { data: 'login', name: 'login' },
                    { data: 'symbol', name: 'symbol' },
                    { data: 'action', name: 'action' },
                    { data: 'position', name: 'position' },
                    { data: 'priceOpen', name: 'priceOpen' },
                    { data: 'priceCurrent', name: 'priceCurrent' },
                    { data: 'priceSL', name: 'priceSL' },
                    { data: 'priceTP', name: 'priceTP' },
                    { data: 'volume', name: 'volume' },
                    { data: 'profit', name: 'profit' },
                    { data: 'rateProfit', name: 'rateProfit' },
                    { data: 'rateMargin', name: 'rateMargin' },
                    { data: 'reason', name: 'reason' },
                    { data: 'timeCreate', name: 'timeCreate' },
                ]
            });

            $('#fetch-positions').click(function() {
                const group = $('#group').val();

                $('#processingIndicator').removeClass('hidden');
                positionsTable.clear();

                $.ajax({
                    url: '{{ route('admin.positions.group') }}',
                    type: 'POST',
                    data: {
                        group: group,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        positionsTable.rows.add(data.data).draw();
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    },
                    complete: function() {
                        $('#processingIndicator').addClass('hidden');
                    }
                });
            });
        });
    </script>
@endsection
