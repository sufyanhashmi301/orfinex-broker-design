@extends('backend.symbol_groups.index')
@section('title')
    {{ __('All Symbol Groups') }}
@endsection
@section('title-btns')
    <a href="{{route('admin.symbols.index')}}" class="btn btn-white inline-flex items-center justify-center">
        {{ __('View All Symbols') }}
    </a>
    <a href="" class="btn btn-primary inline-flex items-center justify-center addSymbolGroup" type="button" >
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add Symbol Group') }}
    </a>
@endsection
@section('symbol-groups-content')
    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="symbol-groups-dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbols') }}</th>
                                    <th scope="col" class="table-th">{{ __('Create Time') }}</th>

                                    <th scope="col" class="table-th">
                                        <div class="flex items-center">
                                            <span>{{ __('Action') }}</span>
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

    <!-- Modal for Add New Group -->
    @include('backend.symbol_groups.modal.__create')
    
    {{--Modal for edit symbol group--}}
    @include('backend.symbol_groups.modal.__edit')

    {{--Modal for delete symbol group--}}
    @include('backend.symbol_groups.modal.__delete')

@endsection
@section('script')

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
         $(document).ready(function() {
            $('#symbols').select2();
         });
         $(document).ready(function () {
            {{--$('#modalForm').on('submit', function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    let formData = $(this).serialize();--}}
            {{--    $.ajax({--}}
            {{--        type: 'POST',--}}
            {{--        url: '{{ route("admin.symbol-groups.store") }}', // Adjust the route to your store function--}}
            {{--        data: formData,--}}
            {{--        success: function (response) {--}}
            {{--            if (response.success) {--}}
            {{--                window.location.reload();--}}
            {{--            }--}}
            {{--        },--}}
            {{--        error: function (xhr) {--}}
            {{--            let errors = xhr.responseJSON.errors;--}}
            {{--            if (errors) {--}}
            {{--                displayErrors(errors);--}}
            {{--            }--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

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
        });
       (function ($) {
            "use strict";
            var table = $('#symbol-groups-dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
                searching: false,
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
                ajax: "{{ route('admin.symbol-groups.index') }}",
                columns: [
                    {"class": "table-td", data: 'id', name: 'ID',orderable : false},
                    {"class": "table-td", data: 'symbol_group', name: 'Symbol Group',orderable : false},
                    {"class": "table-td", data: 'symbols', name: 'symbols',orderable : false},
                    {
                        "class": "table-td",
                        data: 'created_at',
                        name: 'created_at',
                        orderable: false,
                        render: function (data, type, row) {
                            // Format the date and time as "15 May 2024 9:30 AM"
                            var date = new Date(data);
                            var dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
                            var timeOptions = { hour: 'numeric', minute: 'numeric', hour12: true };

                            var dateString = date.toLocaleDateString('en-GB', dateOptions);
                            var timeString = date.toLocaleTimeString('en-GB', timeOptions);

                            return `${dateString} ${timeString}`;
                        }
                    },
                    {"class": "table-td", data: 'action', name: 'action',orderable : false},
                ]
            });
        })(jQuery);
        $('.addSymbolGroup').on('click', function(e) {
            e.preventDefault()
            $.ajax({
                url: '{{ route("admin.symbol-groups.create") }}',
                method: 'GET',
                success: function(response) {

                    var symbols_data = response.symbols;
                    var select = $('select[name="symbols[]"]');

                    select.empty(); // Clear any existing options

                    // Populate the dropdown
                    $.each(symbols_data, function(index, symbol) {
                        console.log(index);
                        select.append(new Option(symbol, index));
                    });

                    $('#symbolGroupModal').modal('show');
                }
            });
        });
        $(document).on('click', '.editSymbolGroup', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: '{{ route("admin.symbol-groups.edit", ":id") }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#groupName').val(response.symbolGroup.symbol_group);
                    var symbolsSelect = $('#symbols');
                    symbolsSelect.empty();
                    $.each(response.allSymbols, function(index, symbol) {
                        var selected = response.symbolGroup.symbols.some(function(s) {
                            return s.id === symbol.id;
                        }) ? 'selected' : '';
                        symbolsSelect.append('<option value="' + symbol.id + '" ' + selected + '>' + symbol.symbol + '</option>');
                    });
                    $('#editSymbolGroupModal').modal('show');
                    $('#editSymbolGroupForm').attr('action', '{{ route("admin.symbol-groups.update", ":id") }}'.replace(':id', id),);
                }
            });
        });
        $(document).on('click', '.deleteSymbolGroup', function(event) {

            "use strict";
            event.preventDefault();
            var id = $(this).data('id');

            var url = '{{ route("admin.symbol-groups.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#symbolGroupDeleteForm').attr('action', url)
            $('#deleteSymbolGroup').modal('show');
        })

    </script>
@endsection

