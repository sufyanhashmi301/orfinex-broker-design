@extends('backend.layouts.app')
@section('title')
    {{ __('Discount Codes') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>

        @can('discount-code-create')
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="javascript:;" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#newDiscountModal">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('New Discount Code') }}
                </a>
            </div>    
        @endcan
        
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Code Name') }}</th>
                                <th scope="col" class="table-th">{{ __('Code') }}</th>
                                <th scope="col" class="table-th">{{ __('Value') }}</th> <!-- New Column -->
                                <th scope="col" class="table-th">{{ __('Applies To') }}</th>
                                <th scope="col" class="table-th">{{ __('Usage Limit') }}</th>
                                <th scope="col" class="table-th">{{ __('Expires On') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            {{-- Data will be populated by DataTables --}}
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

    {{--Modal for discount create--}}
    @include('backend.discounts.include.__create')

    @can('discount-code-edit')
        {{--Modal for discount update--}}
        @include('backend.discounts.include.__edit')
    @endcan
    
    @can('discount-code-delete')
        {{--Modal for discount delete--}}
        @include('backend.discounts.include.__delete')
    @endcan

    {{--Modal for discount disable--}}
    {{-- @include('backend.discounts.include.__disable') --}}
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#dataTable').on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                processing: true,
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
                    search: "Search:",
                    processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
                },
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.discounts.index') }}",
                columns: [
                    {data: 'code_name', name: 'code_name'},
                    {data: 'code', name: 'code'},
                    {data: 'type', name: 'type'},
                    {data: 'applied_to', name: 'applied_to'},
                    {data: 'usage_limit', name: 'usage_limit'},
                    {data: 'expire_at', name: 'expire_at', render: function(data, type, row) {
                            return new Date(data).toISOString().split('T')[0]; // Format date as Y-m-d
                        }},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                        next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                    },
                    processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
                },
            });
        })(jQuery);
        $(document).ready(function() {
            function toggleDiscountDiv() {
                // Hide all discount type divs
                $('.discount-type').addClass('hidden');

                // Get the selected value from the type dropdown
                const selectedValue = $('#discounttype').val();

                // Show the corresponding div based on selected type
                if (selectedValue) {
                    $('.discount-type[data-div="' + selectedValue + '"]').removeClass('hidden');
                }
            }

            // Attach the change event to the dropdown
            $('#discounttype').change(toggleDiscountDiv);
// Function to toggle between fixed and percentage fields
            function toggleDiscountFields() {
                var selectedType = $('#edit_discount_type').val();

                if (selectedType === 'fixed') {
                    $('#fixed_amount_field').removeClass('hidden');  // Show fixed amount
                    $('#percentage_field').addClass('hidden');  // Hide percentage
                } else if (selectedType === 'percentage') {
                    $('#percentage_field').removeClass('hidden');  // Show percentage
                    $('#fixed_amount_field').addClass('hidden');  // Hide fixed amount
                }
            }

            // Trigger the function when the type changes
            $('#edit_discount_type').on('change', function() {
                toggleDiscountFields();
            });

            // Ensure correct fields are displayed when modal is opened with data
            $('#editDiscountModal').on('show.bs.modal', function() {
                toggleDiscountFields();  // Ensure fields are shown/hidden based on the current type
            });
// Ensure flatpickr is re-initialized after the form is dynamically loaded
            $(document).on('shown.bs.modal', '#editDiscountModal', function () {
                flatpickr('#edit_expire_at', {
                    dateFormat: 'm/d/Y',
                });
            });


        });
        function editDiscount(id) {
            $.get("{{ route('admin.discounts.edit', ':id') }}".replace(':id', id), function(html) {
                $('#editDiscountModal .modal-body').html(html);
                $('#editDiscountModal').modal('show');
            });
        }

        function deleteDiscount(id, name) {
            let url = "{{ route('admin.discounts.destroy', ':id') }}".replace(':id', id);  // Generate the delete URL
            $('#discountCodeDeleteForm').attr('action', url);  // Set the form action to the delete route
            $('.name').text(name);  // Set the discount name in the modal
            $('#deleteDiscountModal').modal('show');  // Show the delete modal
        }

    </script>
@endsection
