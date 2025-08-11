@extends('backend.symbol_groups.index')
@section('title')
    {{ __('All Symbol Groups') }}
@endsection
@section('title-btns')
    <a href="{{route('admin.symbols.index')}}" class="btn btn-sm btn-white inline-flex items-center justify-center">
        {{ __('View All Symbols') }}
    </a>
    @can('symbol-group-create')
    <a href="#" class="btn btn-sm btn-primary inline-flex items-center justify-center addSymbolGroup" type="button" >
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add Symbol Group') }}
    </a>
    @endcan
@endsection

@section('filters')
    <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
        <!-- Filter Form (GET request) -->
        <form id="filter-form" method="GET" action="{{ route('admin.symbol-groups.index') }}" class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
            
            {{-- Filter by Group Name --}}
            <div class="flex-1 input-area relative">
                <input type="text" name="global_search" id="global_search" class="form-control h-full"
                       placeholder="Search by Group Name..." value="{{ request('global_search') }}">
            </div>

            {{-- Filter by Symbol --}}
            <div class="flex-1 input-area relative">
                <input type="text" name="symbol_filter" id="symbol_filter" class="form-control h-full"
                       placeholder="Search by Symbol..." value="{{ request('symbol_filter') }}">
            </div>

            {{-- Filter by Creation Date --}}
            <div class="flex-1 input-area relative">
                <input type="date" name="created_at_filter" id="created_at_filter" class="form-control h-full"
                       value="{{ request('created_at_filter') }}">
            </div>
            
            <!-- Filter Button -->
            <div class="input-area relative">
                <button type="submit" id="filter"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                  icon="lucide:filter"></iconify-icon>
                    {{ __('Filter') }}
                </button>
            </div>

             <!-- Clear Button -->
            <div class="input-area relative">
                <button type="button" id="clear-filters"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                  icon="lucide:rotate-cw"></iconify-icon>
                    {{ __('Clear') }}
                </button>
            </div>
            @can('symbol-group-export')
            <div class="input-area relative">
                <button id="export-button"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                  icon="lets-icons:export-fill"></iconify-icon>
                    {{ __('Export') }}
                </button>
            </div>
            @endcan
        </form>
    </div>
@endsection

@section('symbol-groups-content')
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="symbol-groups-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbols') }}</th>
                                    <th scope="col" class="table-th">{{ __('Create Time') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="processingIndicator" class="text-center" style="display: none;">
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @can('symbol-group-create')
        @include('backend.symbol_groups.modal.__create')
    @endcan
    @can('symbol-group-edit')
        @include('backend.symbol_groups.modal.__edit')
    @endcan
    @can('symbol-group-delete')
        @include('backend.symbol_groups.modal.__delete')
    @endcan
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#symbols, #edit_symbols').select2({
                dropdownParent: $("#symbolGroupModal, #editSymbolGroupModal")
            });

            // Filter toggle functionality for the "More" button
            $('.filter-toggle-btn').click(function() {
                const $content = $('#filters_div');
                
                if ($content.hasClass('hidden')) {
                    $content.removeClass('hidden').slideDown();
                } else {
                    $content.slideUp(function() {
                        $content.addClass('hidden');
                    });
                }
            });

            // DataTable initialization
            var table = $('#symbol-groups-dataTable').on('processing.dt', function(e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                searching: false,
                lengthChange: true,
                info: true,
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                        next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                    },
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.symbol-groups.index') }}",
                    data: function(d) {
                        d.global_search = $('#global_search').val();
                        d.symbol_filter = $('#symbol_filter').val();
                        d.created_at_filter = $('#created_at_filter').val();
                    }
                },
                columns: [
                    // ... (Your existing column definitions) ...
                    { data: 'id', name: 'id', orderable: false },
                    { data: 'title', name: 'title', orderable: false },
                    { data: 'symbols', name: 'symbols', orderable: false },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: true,
                        render: function(data, type, row) {
                            var date = new Date(data);
                            var dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
                            var timeOptions = { hour: 'numeric', minute: 'numeric', hour12: true };
                            var dateString = date.toLocaleDateString('en-GB', dateOptions);
                            var timeString = date.toLocaleTimeString('en-GB', timeOptions);
                            return `${dateString} ${timeString}`;
                        }
                    },
                    { data: 'action', name: 'action', orderable: false },
                ]
            });
$('#global_search, #symbol_filter, #created_at_filter').on('change keyup', function() {
        table.draw();
        
    });
    
            function applyFilters() {
                table.draw();
            }

            $('#global_search, #symbol_filter').on('keyup', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    e.preventDefault();
                }
                applyFilters();
            });

            $('#created_at_filter').on('change', function() {
                applyFilters();
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                applyFilters();
            });

            $('#clear-filters').on('click', function() {
                $('#filter-form').find('input').val('');
                applyFilters();
            });

      const urlParams = new URLSearchParams(window.location.search);
    const prefilteredGroupTitle = urlParams.get('filter_symbol_group_title');
    const prefilteredGlobalSearch = urlParams.get('global_search');

    if (prefilteredGroupTitle) {
        $('#global_search').val(decodeURIComponent(prefilteredGroupTitle));
        // Show the filter div
        const $filtersDiv = $('#filters_div');
        if ($filtersDiv.hasClass('hidden')) {
            $filtersDiv.removeClass('hidden').slideDown();
        }
        table.draw();
        // Clear the URL to prevent re-filtering on refresh
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({ path: newUrl }, '', newUrl);
    } else if (prefilteredGlobalSearch) {
        $('#global_search').val(decodeURIComponent(prefilteredGlobalSearch));
        // Show the filter div
        const $filtersDiv = $('#filters_div');
        if ($filtersDiv.hasClass('hidden')) {
            $filtersDiv.removeClass('hidden').slideDown();
        }
        table.draw();
    }
    

            $('.addSymbolGroup').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("admin.symbol-groups.create") }}',
                    method: 'GET',
                    success: function(response) {
                        var symbols_data = response.symbols;
                        var select = $('#symbols');
                        select.empty();
                        $.each(symbols_data, function(index, symbol) {
                            select.append(new Option(symbol, index));
                        });
                        select.trigger('change');
                        $('#symbolGroupModal').modal('show');
                    }
                });
            });

            // --- CORRECTED EDIT MODAL SCRIPT ---
            $(document).on('click', '.editSymbolGroup', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route("admin.symbol-groups.edit", ":id") }}'.replace(':id', id),
                    method: 'GET',
                    success: function(response) {
                        // Using the selector from your working code.
                        // This assumes the input in your edit modal has: id="groupName"
                        $('#groupName').val(response.symbolGroup.title);

                        // Using the selector from your working code.
                        // This assumes the select in your edit modal has: id="symbols"
                        var symbolsSelect = $('#symbols');
                        symbolsSelect.empty(); // Clear existing options

                        // Get an array of the IDs that should be selected.
                        var selectedSymbolIds = response.symbolGroup.symbols.map(function(s) {
                            return s.id;
                        });

                        // Add all possible symbols to the dropdown.
                        $.each(response.allSymbols, function(index, symbol) {
                            var newOption = new Option(symbol.symbol, symbol.id, false, false);
                            symbolsSelect.append(newOption);
                        });

                        // IMPORTANT: Use .val() to set the selected options for Select2 and trigger a change.
                        symbolsSelect.val(selectedSymbolIds).trigger('change');

                        $('#editSymbolGroupModal').modal('show');
                        $('#editSymbolGroupForm').attr('action', '{{ route("admin.symbol-groups.update", ":id") }}'.replace(':id', id));
                    }
                });
            });

            $(document).on('click', '.deleteSymbolGroup', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                var url = '{{ route("admin.symbol-groups.destroy", ":id") }}'.replace(':id', id);
                $('#symbolGroupDeleteForm').attr('action', url);
                $('#deleteSymbolGroup').modal('show');
            });

           // Export functionality
$('#export-button').click(function(e) {
    e.preventDefault();
    
    // Get all current filter values
    var filters = {
        global_search: $('#global_search').val(),
        symbol_filter: $('#symbol_filter').val(),
        created_at_filter: $('#created_at_filter').val()
    };
    
    // Convert filters to URL parameters
    var params = $.param(filters);
    
    // Create a temporary form to submit
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.symbol-groups.export") }}?' + params;
    
    // Add CSRF token
    var csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Submit the form
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});
        });
    </script>
@endsection
