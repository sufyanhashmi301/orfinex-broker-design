@extends('backend.symbol_groups.index')

@section('title')
    {{ __('All Symbols') }}
@endsection

@section('title-btns')
    <a href="{{route('admin.symbol-groups.index')}}"
       class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button">
        {{ __('Symbol Groups') }}
    </a>
@endsection

@section('filters')
    <form id="filter-form" method="GET" action="{{ route('admin.symbols.index') }}">
        <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full"
                           placeholder="Search by Symbol Name">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="contact_size" id="contact_size" class="form-control h-full"
                           placeholder="Contact Size">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="path" id="path" class="form-control h-full" placeholder="Path">
                </div>
{{--                <div class="flex-1 input-area relative">--}}
{{--                    <select name="status" id="status" class="select2 form-control h-full w-full">--}}
{{--                        <option value="">{{ __('Select Status') }}</option>--}}
{{--                        <option value="1">{{ __('Enabled') }}</option>--}}
{{--                        <option value="0">{{ __('Disabled') }}</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                <div class="input-area relative">
                    <button type="submit" id="filter"
                            class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                      icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
            </div>
            <div class="input-area relative">
                <button type="button"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                  icon="lets-icons:export-fill"></iconify-icon>
                    {{ __('Export') }}
                </button>
            </div>
            <div class="input-area relative">
                <button type="button"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white"
                        data-bs-toggle="modal" data-bs-target="#configureModal">
                    <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                </button>
            </div>
        </div>
    </form>
@endsection

@section('symbol-groups-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 mt-4">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                               id="symbolsTable">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Symbol ID') }}</th>
                                <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                <th scope="col" class="table-th">{{ __('Path') }}</th>
                                <th scope="col" class="table-th">{{ __('Description') }}</th>
                                <th scope="col" class="table-th">{{ __('Contract Size') }}</th>
                                <th scope="col" class="table-th">
                                    <div class="flex items-center">
                                        <span class="mr-2">{{ __('Enable') }}</span>
                                        <span class="toolTip onTop leading-none" data-tippy-content="Enable All">
                                            <button type="button" id="enableAll" class="action-btn">
                                                <iconify-icon icon="lucide:check"></iconify-icon>
                                            </button>
                                        </span>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                                <tbody id="symbolTableBody">
                                @foreach ($mt5Symbols as $symbol)
                                    <tr>
                                        <td class="table-td"><strong>{{ $symbol->Symbol_ID }}</strong></td>
                                        <td class="table-td">{{ $symbol->Symbol }}</td>
                                        <td class="table-td">{{ $symbol->Path }}</td>
                                        <td class="table-td">{{ $symbol->Description }}</td>
                                        <td class="table-td">{{ $symbol->ContractSize }}</td>
                                        <td class="table-td">
                                            <div class="form-switch">
                                                <label
                                                    class="relative !inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox">
                                                    <input
                                                        type="checkbox"
                                                        class="sr-only peer symbol-toggle"
                                                        data-id="{{ $symbol->Symbol_ID }}"
                                                        @if($symbol->status === 'Enabled') checked @endif>
                                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white
    after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all
    dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                        </table>

                            <div class="pagination-container mt-4">
                                Showing <span id="paginationInfo">{{ $mt5Symbols->firstItem() }} to {{ $mt5Symbols->lastItem() }} of {{ $mt5Symbols->total() }}</span>
                                symbols
                                <div id="paginationLinks">
                                    {{ $mt5Symbols->appends(request()->query())->links() }}
                                </div>
                            </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('symbol-groups-script')
    <script>
        $(document).ready(function () {

                $("#filter-form").submit(function (event) {
                    event.preventDefault();
                    fetchSymbols();
                });

                function fetchSymbols(page = 1) {
                    var formData = $("#filter-form").serialize(); // Serialize form data
                    $.ajax({
                        url: "{{ route('admin.symbols.index') }}?page=" + page, // Append page number
                        type: "GET",
                        data: formData, // Send filters along with request
                        success: function (response) {
                            $("#symbolTableBody").html(response.table); // Update table only
                            $("#paginationLinks").html(response.pagination); // Update pagination
                        },
                        error: function (xhr, status, error) {
                            tNotify('warning', "Error fetching symbols.");
                            console.error("Error:", error);
                        }
                    });
                }

                // Handle pagination click event dynamically
                $('body').on('click', '.pagination a', function (event) {
                    event.preventDefault();
                    var page = $(this).attr('href').split('page=')[1]; // Get the clicked page number
                    fetchSymbols(page); // Load the filtered page via AJAX
                });


            // Handle Toggle Change for Single Symbol
            $('body').on('click', '.symbol-toggle', function () {
                var checkbox = $(this);
                var symbolId = $(this).data("id");
                var newState = $(this).is(":checked") ? 1 : 0;

                $.ajax({
                    url: "{{ route('admin.symbols.updateStatus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: symbolId,
                        status: newState
                    },
                    success: function (response) {
                        if (response.success) {
                            tNotify('success', response.message);
                        } else {
                            tNotify('warning', response.message);
                            checkbox.prop("checked", !newState); // Revert toggle

                        }
                    },
                    error: function (xhr, status, error) {
                        tNotify('warning', "Error updating symbol status.");
                        console.error("Error:", error);
                    }
                });
            });

            // Handle Enable All Click
            $("#enableAll").click(function () {
                $.ajax({
                    url: "{{ route('admin.symbols.enableAll') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            tNotify('success', response.message);
                            fetchSymbols(); // Refresh table
                        } else {
                            tNotify('warning', response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        tNotify('warning', "Error enabling all symbols.");
                        console.error("Error:", error);
                    }
                });
            });
        });
    </script>
@endsection
