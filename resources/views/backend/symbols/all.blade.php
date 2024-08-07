@extends('backend.symbol_groups.index')
@section('title')
    {{ __('All Symbols') }}
@endsection
@section('title-btns')
    <!-- <a href="{{route('admin.symbols.index')}}" class="btn btn-white inline-flex items-center justify-center">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
        {{ __('View All Symbols') }}
    </a> -->
    <a href="{{route('admin.symbol-groups.index')}}" class="btn btn-primary inline-flex items-center justify-center" type="button" >

        {{ __('Symbol Groups') }}
    </a>
@endsection
@section('symbol-groups-content')
    <div class="card">
        <div class="card-body p-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="symbols-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Symbol ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                    <th scope="col" class="table-th">{{ __('Path') }}</th>
                                    <th scope="col" class="table-th">{{ __('Description') }}</th>
                                    <th scope="col" class="table-th">{{ __('Contact Size') }}</th>
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
        </div>
    </div>

@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#symbols-dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
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
                searching: false,
                ajax: "{{ route('admin.symbols.index') }}",
                columns: [
                    {"class": "table-td", data: 'Symbol_ID', name: 'ID',orderable : false},
                    {"class": "table-td", data: 'Symbol', name: 'Symbol',orderable : false},
                    {"class": "table-td", data: 'Path', name: 'Path',orderable : false},
                    {"class": "table-td", data: 'Description', name: 'Description',orderable : false},
                    {"class": "table-td", data: 'ContractSize', name: 'ContractSize',orderable : false},
                    {"class": "table-td", data: 'action', name: 'action',orderable : false},
                ]
            });
        })(jQuery);
        function insertRecord(dataId) {
            "use strict";
            event.preventDefault();
            var checkbox = $('input[data-id="' + dataId + '"]');
            var newState = checkbox.is(':checked');
            $.post('symbols/store', { 
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
