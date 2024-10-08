@extends('backend.setting.platform.index')
@section('title')
    {{ __('Platform Groups') }}
@endsection
@section('platform-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{route('admin.platform.riskBook')}}" class="btn btn-white inline-flex items-center justify-center">
                {{ __('All Risk Book') }}
            </a>
        </div>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    {{ __('Meta Trader 5') }}
                </a>
            </li>
        </ul>
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

    <!-- Modal for Assign Risk Book -->
    @include('backend.platform_group.modal.__create')

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
