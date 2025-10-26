@extends('backend.layouts.app')
@section('title')
    {{ __('IB Leaderboard') }}
@endsection
@section('content')
    <div class="pageTitle flex flex-col justify-between sm:flex-row gap-3 mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <form id="searchForm" class="max-w-3xl w-full">
            @csrf
            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                <div class="flex-1 input-area">
                    <select name="ib_group_id" id="ib_group_id" class="select2 form-control h-9" data-placeholder="Select IB Group">
                        <option value="all">{{ __('All IB Groups') }}</option>
                        @foreach ($ibGroups as $group)
                            <option value="{{ $group->id }}" {{ $ib_group_id == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 input-area">
                    <select id="rangeSelect" class="form-control h-9">
                        <option value="">{{ __('-- Select Range --') }}</option>
                        <option value="today">{{ __('Today') }}</option>
                        <option value="yesterday">{{ __('Yesterday') }}</option>
                        <option value="last30">{{ __('Last 30 Days') }}</option>
                        <option value="thisMonth">{{ __('This Month') }}</option>
                        <option value="lastMonth">{{ __('Last Month') }}</option>
                        <option value="ytd">{{ __('Year to Date') }}</option>
                        <option value="lastYear">{{ __('Last Year') }}</option>
                        <option value="custom">{{ __('Custom Range') }}</option>
                    </select>
                </div>
                <div class="flex-1 input-area">
                    <div class="relative">
                        <input type="date" name="created_at" id="created_at" class="form-control h-9 !pr-9" data-mode="range" placeholder="Created At">
                        <button id="clearBtn" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                            <iconify-icon icon="mdi:window-close"></iconify-icon>
                        </button>
                    </div>
                    <span class="text-xs font-light dark:text-slate-200">
                        {{ __('Double click for a single date') }}
                    </span>
                </div>
                <div class="input-area relative">
                    <button type="submit" id="filter" class="btn btn-white btn-sm inline-flex items-center justify-center min-w-max" style="min-height: 34px;">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Apply Filter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div id="leaderboard-summary">
        @include('backend.leaderboard.summary')
    </div>
@endsection
@section('style')
    <style>
        #searchForm .select2-container .select2-selection--single {
            height: 2.25rem;
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            initLeaderboardTable();
        });

        $('#searchForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();

            // Reload full page with filter parameters (HTML block content)
            $.ajax({
                url: '{{ route("admin.leaderboard.index") }}',
                type: 'GET',
                data: {
                    ib_group_id: $('#ib_group_id').val(),
                    created_at: $('#created_at').val()
                },
                success: function (response) {
                    $('#leaderboard-summary').html(response.summaryHtml);
                    initLeaderboardTable();
                }
            });
        });

        function initLeaderboardTable() {
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            table = $('#dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            })
            .DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                searching: false,
                lengthChange: false,
                info: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "<iconify-icon icon='ic:round-keyboard-arrow-left'></iconify-icon>",
                        next: "<iconify-icon icon='ic:round-keyboard-arrow-right'></iconify-icon>"
                    }
                },
                ajax: {
                    url: "{{ route('admin.leaderboard.index') }}",
                    data: function (d) {
                        d.created_at = $('#created_at').val();
                        d.ib_group_id = $('#ib_group_id').val();
                        d.datatable = 1;
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'user', name: 'user' },
                    { data: 'ib_group', name: 'ib_group' },
                    { data: 'network_users', name: 'network_users' },
                    { data: 'incoming_total', name: 'incoming_total' },
                    { data: 'outgoing_total', name: 'outgoing_total' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ]
            });
        }

        const input = document.getElementById("created_at");
        const clearBtn = document.getElementById("clearBtn");
        const rangeSelect = document.getElementById("rangeSelect");

        const fp = flatpickr(input, {
            altInput: false,
            dateFormat: "Y-m-d",
            allowInput: false,
        });

        // Define range presets
        function getDateRanges() {
            const today = new Date();
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            const startOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            const startOfYear = new Date(today.getFullYear(), 0, 1);
            const endOfLastYear = new Date(today.getFullYear() - 1, 11, 31);
            const startOfLastYear = new Date(today.getFullYear() - 1, 0, 1);

            return {
                today: [today, today],
                yesterday: [new Date(today.getFullYear(), today.getMonth(), today.getDate() - 1), new Date(today.getFullYear(), today.getMonth(), today.getDate() - 1)],
                last30: [new Date(today.getFullYear(), today.getMonth(), today.getDate() - 29), today],
                thisMonth: [startOfMonth, endOfMonth],
                lastMonth: [startOfLastMonth, endOfLastMonth],
                ytd: [startOfYear, today],
                lastYear: [startOfLastYear, endOfLastYear]
            };
        }

        // Set range on selection
        rangeSelect.addEventListener("change", function () {
            const selected = this.value;
            const ranges = getDateRanges();

            if (selected === 'custom') {
                fp.clear();
                fp.open();
            } else if (ranges[selected]) {
                fp.setDate(ranges[selected], true); // second param triggers `onChange`
            }
        });

        // Clear button logic
        clearBtn.addEventListener("click", () => {
            fp.clear();
        });
    </script>
@endsection
