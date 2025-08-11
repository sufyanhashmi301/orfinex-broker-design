@extends('backend.rebate_rules.index')
@section('title')
    {{ __('All Rebate Rules') }}
@endsection
@section('title-btns')
@can('symbols-list')
    <a href="{{route('admin.symbols.index')}}" class="btn btn-sm btn-white inline-flex items-center justify-center">
        {{ __('View All Symbols') }}
    </a>
@endcan
@can('rebate-rules-create')
    <a href="" class="btn btn-sm btn-primary inline-flex items-center justify-center addRebateGroup" type="button" >
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add Rebate Rules') }}
    </a>
@endcan
@endsection
@section('symbol-groups-content')
    <!-- Filters Section -->
    <div class="card p-6 mb-5">
        <form id="filter-form" method="POST" action="{{ route('admin.rebateRules.export')}}">
            @csrf
            @if(request('filter_rule'))
                <input type="hidden" name="filter_rule" value="{{ request('filter_rule') }}">
            @endif
            <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
                <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="input-area relative">
                        <input type="text" name="global_search" id="global_search" class="form-control h-full" placeholder="Search Rebate Name, Symbol Group, Account Type, IB Group..." value="{{ request('global_search') }}">
                    </div>
                    <div class="input-area relative">
                        <input type="number" name="total_rebate" id="total_rebate" class="form-control h-full" placeholder="Total Rebate" value="{{ request('total_rebate') }}">
                    </div>
                    <div class="input-area relative">
                        <select name="status" id="status" class="form-control h-full w-full">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Disabled') }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <div class="input-area relative">
                        <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                            {{ __('Filter') }}
                        </button>
                    </div>
                    @can('rebate-rules-export')
                    <div class="input-area relative">
                        <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                            {{ __('Export') }}
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </form>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8 hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="rebate-rules-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Rebate Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol Groups') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Types') }}</th>
                                    <th scope="col" class="table-th">{{ __('IB Groups') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total Rebate') }}</th>
                                    <!-- <th scope="col" class="table-th">{{ __('Accounts') }}</th> -->
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
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
    @can('rebate-rules-create')
    @include('backend.rebate_rules.modal.__create')
    @endcan
    @can('rebate-rules-edit')
    @include('backend.rebate_rules.modal.__edit')
    @endcan
    @can('rebate-rules-delete')
    @include('backend.rebate_rules.modal.__delete')
    @endcan
@endsection

@section('script')
    <script>

         $(document).ready(function () {
            $('#modalForm').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();
                console.log('formData',formData);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.rebate-rules.store") }}', // Adjust the route to your store function
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            window.location.reload();
                        }
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            displayErrors(errors);
                        }
                    }
                });
            });

            function displayErrors(errors) {
                $('.invalid-feedback').hide(); // Hide all previous error messages
                $('.is-invalid').removeClass('is-invalid'); // Remove is-invalid class from inputs

                for (let field in errors) {
                    let input = $('[name="' + field + '[]"]');
                    if (!input.length) {
                        input = $('[name="' + field + '"]');
                    }
                    input.addClass('is-invalid');
                    $('#'+field+'-error').text(errors[field][0]).show();
                }
            }

            // Debounce function
            let filterTimeout;
            function debouncedFilter() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    loadRebateRules();
                }, 500);
            }

            // AJAX function to load Rebate Rules
            function loadRebateRules() {
                const globalSearch = $('#global_search').val();
                const status = $('#status').val();
                const totalRebate = $('#total_rebate').val();
                const filterRule = $('input[name="filter_rule"]').val();

                // Show loading indicator

                const data = {
                    global_search: globalSearch,
                    status: status,
                    total_rebate: totalRebate,
                    ajax: true
                };

                // Add filter_rule if present
                if (filterRule) {
                    data.filter_rule = filterRule;
                }

                $.ajax({
                    url: '{{ route("admin.rebate-rules.index") }}',
                    method: 'GET',
                    data: data,
                    success: function(response) {
                        // Reload the DataTable with new data
                        $('#rebate-rules-dataTable').DataTable().ajax.reload();
                        
                        // Update export form with current filters
                        updateExportForm();
                    },
                    error: function() {
                        $('#rebate-rules-dataTable tbody').html('<tr><td colspan="8" class="text-center py-4 text-red-500">Error loading data</td></tr>');
                    }
                });
            }

            // Function to update export form with current filters
            function updateExportForm() {
                const globalSearch = $('#global_search').val();
                const status = $('#status').val();
                const totalRebate = $('#total_rebate').val();
                const filterRule = $('input[name="filter_rule"]').val();

                // Only remove hidden inputs, not the visible ones!
                $('input[type="hidden"][name="global_search"], input[type="hidden"][name="status"], input[type="hidden"][name="total_rebate"]').remove();

                // Add current filter values as hidden inputs
                if (globalSearch) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'global_search',
                        value: globalSearch
                    }).appendTo('#filter-form');
                }
                if (status) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'status',
                        value: status
                    }).appendTo('#filter-form');
                }
                if (totalRebate) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'total_rebate',
                        value: totalRebate
                    }).appendTo('#filter-form');
                }
                if (filterRule) {
                    // Update existing filter_rule input or create new one
                    if ($('input[name="filter_rule"]').length) {
                        $('input[name="filter_rule"]').val(filterRule);
                    } else {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'filter_rule',
                            value: filterRule
                        }).appendTo('#filter-form');
                    }
                }
            }

            // Remove filter_rule if any other filter is used
            function clearFilterRuleIfOtherFilters() {
                const globalSearch = $('#global_search').val();
                const status = $('#status').val();
                const totalRebate = $('#total_rebate').val();
                if (globalSearch || status || totalRebate) {
                    $('input[name="filter_rule"]').remove();
                }
            }

            // Event listeners for filters
            $('#global_search').on('keyup', function() {
                if (!$(this).val()) {
                    // If global_search is empty, remove filter_rule and reload all
                    $('input[name="filter_rule"]').remove();
                    debouncedFilter();
                } else {
                    clearFilterRuleIfOtherFilters();
                    debouncedFilter();
                }
            });

            $('#status, #total_rebate').on('change', function() {
                clearFilterRuleIfOtherFilters();
                debouncedFilter();
            });

            $('#filter').on('click', function() {
                clearFilterRuleIfOtherFilters();
                loadRebateRules();
            });

            // Pre-populate filters from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            $('#global_search').val(urlParams.get('global_search') || '');
            $('#status').val(urlParams.get('status') || '');
            $('#total_rebate').val(urlParams.get('total_rebate') || '');
            
            // Initial export form update
            updateExportForm();
        });

       (function ($) {
            "use strict";
            var table = $('#rebate-rules-dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                searching: false,
                lengthChange: false,
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
                    url: "{{ route('admin.rebate-rules.index') }}",
                    data: function (d) {
                        d.global_search = $('#global_search').val();
                        d.status = $('#status').val();
                        d.total_rebate = $('#total_rebate').val();
                        d.filter_rule = $('input[name="filter_rule"]').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'ID',orderable : false},
                    {data: 'title', name: 'Rebate Name',orderable : false},
                    {data: 'symbolGroups', name: 'Symbol Groups',orderable : false},
                    { data: 'forexSchemas', name: 'Forex Schemas', orderable: false },
                    { data: 'ibGroups', name: 'IB Groups', orderable: false },
                    {data: 'rebate_amount', name: 'Total Rebate',orderable : false},
                    {data: 'status', name: 'Status',orderable : false},
                    {data: 'action', name: 'action',orderable : false},
                ]
            });
        })(jQuery);

        $('.addRebateGroup').on('click', function(e) {
            e.preventDefault()
            $.ajax({
                url: '{{ route("admin.rebate-rules.create") }}',
                method: 'GET',
                success: function(response) {

                    var symbols_data = response.symbolGroups;
                    var select = $('select[name="symbol_groups[]"]');
                    select.empty();
                    $.each(symbols_data, function(index, symbol) {
                        select.append(new Option(symbol, index));
                    });
                    $('#addRebateRuleModal').modal('show');
                }
            });
        });

         $(document).on('click', '.editRebateRule', function(e) {
             e.preventDefault();
             var id = $(this).data('id');
             $.ajax({
                 url: '{{ route("admin.rebate-rules.edit", ":id") }}'.replace(':id', id),
                 method: 'GET',
                 success: function(response) {
                     $('#edit_rebate_rule').html(response);
                     $('#editSymbolGroupModal').modal('show');
                     $('.select2').select2();
                     tippy(".shift-Away", {
                        placement: "top",
                        animation: "shift-away"
                    });
                 },
                 error: function(xhr, status, error) {
                     console.error(xhr.responseText);
                 }
             });
         });


        
        $('#editSymbolGroupModal').on('submit', '#editRebateRuleForm', function(e) {
    e.preventDefault();
    var form = $(this);
    var actionUrl = form.attr('action');
    var table = $('#rebate-rules-dataTable').DataTable();
    var currentPage = table.page();
    
    $.ajax({
        url: actionUrl,
        method: 'POST',
        data: form.serialize(),
        success: function(response) {
            $('#editSymbolGroupModal').modal('hide');
            // Reload table but maintain page
            table.ajax.reload(null, false); // false means don't reset pagination
            // Restore the page
            table.page(currentPage).draw('page');
            tNotify('success', 'Rebate Rule updated successfully');

        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            if (errors) {
                displayErrors(errors);
            }
        }
    });
});


         $(document).on('click', '.deleteRebateRule', function(event) {

            "use strict";
            event.preventDefault();
            var id = $(this).data('id');

            var url = '{{ route("admin.rebate-rules.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#rebateRuleDeleteForm').attr('action', url)
            $('#deleteRebateRule').modal('show');
        });

       $(document).on('click', '.status-checkbox', function(event) {
    "use strict";
    event.preventDefault();
    var checkbox = $(this);
    var itemId = checkbox.data('id');
    var status = checkbox.is(':checked') ? 1 : 0;
    
    // Save current page
    var table = $('#rebate-rules-dataTable').DataTable();
    var currentPage = table.page();
    
    $.ajax({
        url: '{{ route('admin.rebateRules.updateStatus') }}',
        type: 'POST',
        data: {
            id: itemId,
            status: status,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Reload table but maintain page
                table.ajax.reload(null, false); // false means don't reset pagination
                // Restore the page
                table.page(currentPage).draw('page');
                tNotify('success', 'Rebate Rule updated successfully');
            } else {
                // Revert checkbox if failed
                checkbox.prop('checked', !status);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            checkbox.prop('checked', !status);
        }
    });
});
    </script>
@endsection

