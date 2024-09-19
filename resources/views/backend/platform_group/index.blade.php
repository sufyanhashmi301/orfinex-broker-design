@extends('backend.setting.platform.index')
@section('title')
    {{ __('Platform Groups') }}
@endsection
@section('platform-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="platformGroups-dataTable">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Group ID') }}</th>
                                <th scope="col" class="table-th">{{ __('Group') }}</th>
                                <th scope="col" class="table-th">{{ __('Currency') }}</th>
                                <th scope="col" class="table-th">{{ __('Digits') }}</th>
                                <th scope="col" class="table-th">{{ __('Margin Call') }}</th>
                                <th scope="col" class="table-th">{{ __('Stop Out') }}</th>
                                <th scope="col" class="table-th">
                                    <div class="flex items-center">
                                        <span>{{ __('Enable') }}</span>
                                        <span class="toolTip onTop leading-none" data-tippy-content="primary tooltip!" data-tippy-theme="dark">
                                                <iconify-icon class="text-lg ltr:ml-2 rtl:mr-2" icon="lucide:info"></iconify-icon>
                                            </span>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

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
@endsection
@section('platform-script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#platformGroups-dataTable')
                .on('processing.dt', function (e, settings, processing) {
                    $('#processingIndicator').css('display', processing ? 'block' : 'none');
                }).DataTable({
                    dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                    searching: false,
                    lengthChange: false,
                    pageLength: 50,
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
                    ajax: "{{ route('admin.platformGroups') }}",
                    columns: [
                        {data: 'Group_ID', name: 'ID',orderable : false},
                        {data: 'Group', name: 'group',orderable : false},
                        {data: 'Currency', name: 'currency',orderable : false},
                        {data: 'CurrencyDigits', name: 'currencyDigits',orderable : false},
                        {data: 'MarginCall', name: 'marginCall',orderable : false},
                        {data: 'MarginStopOut', name: 'marginStopOut',orderable : false},
                        {data: 'action', name: 'action',orderable : false},
                    ]
                });
        })(jQuery);

        function insertRecord(dataId) {
            "use strict";
            event.preventDefault();
            var checkbox = $('input[data-id="' + dataId + '"]');
            var newState = checkbox.is(':checked');
            $.post('groups/store', {
                "_token": "{{ csrf_token() }}",
                "id": dataId

            }, function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    window.location.reload();
                }
            }).fail(function(error) {
                console.error('Error:', error);
            });

        }

    </script>
@endsection
