@extends('backend.symbol_groups.index')
@section('title')
    {{ __('All Symbols') }}
@endsection
@section('title-btns')
    <!-- <a href="{{route('admin.symbols.index')}}" class="btn btn-white inline-flex items-center justify-center">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
        {{ __('View All Symbols') }}
    </a> -->
    <a href="{{route('admin.symbol-groups.index')}}" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" >
        {{ __('Symbol Groups') }}
    </a>
@endsection
@section('filters')
    <form id="filter-form" method="POST" action="">
        @csrf
        <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full" placeholder="Search by Symbol Name">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="contact_size" id="contact_size" class="form-control h-full" placeholder="Contact Size">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="path" id="path" class="form-control h-full" placeholder="Path">
                </div>
                <div class="flex-1 input-area relative">
                    <select name="status" id="status" class="select2 form-control h-full w-full" data-placeholder="{{ __('Select a status') }}">
                        <option >{{ __('Select Status') }}</option>
                        <option value="1">{{ __('Enabled') }}</option>
                        <option value="0">{{ __('Disabled') }}</option>
                    </select>
                </div>
            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                <div class="input-area relative">
                    <button type="submit" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                        <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('symbol-groups-content')
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
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
                                            <span class="mr-2">{{ __('Enable') }}</span>
                                            <span class="toolTip onTop leading-none" data-tippy-content="Enable All" data-tippy-theme="dark">
                                                <button type="button" id="enableAll" class="action-btn">
                                                    <iconify-icon icon="lucide:check"></iconify-icon>
                                                </button>
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

@endsection
@section('symbol-groups-script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#symbols-dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                searching: false,
                lengthChange: false,
                pageLength: 250,
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
                        url: "{{ route('admin.symbols.index') }}",
                        data: function (d) {
                            d._token = "{{ csrf_token() }}";
                            d.global_search = $('#global_search').val();
                            d.contact_size = $('#contact_size').val();
                            d.path = $('#path').val();
                            d.status = $('#status').val();
                        }
                    },
                    columns: [
                    {data: 'Symbol_ID', name: 'ID',orderable : false},
                    {data: 'Symbol', name: 'Symbol',orderable : false},
                    {data: 'Path', name: 'Path',orderable : false},
                    {data: 'Description', name: 'Description',orderable : false},
                    {data: 'ContractSize', name: 'ContractSize',orderable : false},
                    {data: 'action', name: 'action',orderable : false},
                ]
            });

            $('#filter-form').on('submit', function (e) {
                e.preventDefault(); // Prevent the default form submission
                table.ajax.reload(); // Reload the table with the new filter parameters
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

        $(document).ready(function(){


            $('#enableAll').click(function() {
                $.ajax({
                    url: 'all-symbols/store',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        })

    </script>
@endsection
