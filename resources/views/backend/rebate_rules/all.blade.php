@extends('backend.rebate_rules.index')
@section('title')
    {{ __('All Rebate Rules') }}
@endsection
@section('title-btns')

    <a href="" class="btn btn-primary inline-flex items-center justify-center addRebateGroup" type="button" >
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add Rebate Rules') }}
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
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="rebate-rules-dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Rebate Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol Groups') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total Rebate') }}</th>
                                    <!-- <th scope="col" class="table-th">{{ __('Accounts') }}</th> -->
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
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
    @include('backend.rebate_rules.modal.__create')
    @include('backend.rebate_rules.modal.__edit')
    @include('backend.rebate_rules.modal.__delete')
@endsection
@section('script')

    <script>

         $(document).ready(function() {
            $('#symbols').select2();
         });

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
        });

       (function ($) {
            "use strict";
            var table = $('#rebate-rules-dataTable')
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
                ajax: "{{ route('admin.rebate-rules.index') }}",
                columns: [
                    {"class": "table-td", data: 'id', name: 'ID',orderable : false},
                    {"class": "table-td", data: 'title', name: 'Rebate Name',orderable : false},
                    {"class": "table-td", data: 'symbolGroups', name: 'Symbol Groups',orderable : false},
                    {"class": "table-td", data: 'rebate_amount', name: 'Total Rebate',orderable : false},
                    {"class": "table-td", data: 'status', name: 'Status',orderable : false},
                    {"class": "table-td", data: 'action', name: 'action',orderable : false},
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

             $.ajax({
                 url: actionUrl,
                 method: 'POST',
                 data: form.serialize(),
                 success: function(response) {
                     $('#editSymbolGroupModal').modal('hide');
                     location.reload(); // Reload the page to reflect changes
                 },
                 error: function(xhr, status, error) {
                     console.error(xhr.responseText);
                     // Handle validation errors and display them
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
        })

    </script>
@endsection

