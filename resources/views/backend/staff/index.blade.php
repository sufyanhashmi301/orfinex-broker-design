@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Staff') }}
@endsection
@section('style')
    <style>
        @keyframes pulse{
            50%{
                opacity: .5;
            }
        }
    </style>
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Manage Staffs') }}
        </h4>
    </div>
    <div class="card border dark:border-slate-700">
        <div class="grid grid-cols-12">
            <div class="lg:col-span-4 col-span-12">
                <div class="h-full border-r dark:border-slate-700">
                    <div class="card-header pl-0" style="padding-bottom: 11px;">
                        <div class="input-area relative">
                            <select id="staffStatusFilter" class="form-control">
                                <option value="active">{{ __('Active Staff') }} ({{ $activeStaffCount }})</option>
                                <option value="inactive">{{ __('Inactive Staff') }} ({{ $inactiveStaffCount }})</option>
                            </select>
                        </div>
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            @can('staff-create')
                                <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center" id="create-staff">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                                    {{ __('Add New Staff') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div id="staff-list" class="p-6 pr-0">
                        @include('backend.staff.include.__staff_list', ['staff' => $staffs])
                    </div>
                </div>
            </div>
            <div class="lg:col-span-8 col-span-12">
                <div id="edit-staff-body">
                    @include('backend.staff.edit')
                </div>
                <div class="card hidden p-6" id="loader_placeholder">
                    @include('backend.staff.include.placeholder')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit Staff -->
    @can('staff-edit')
        @include('backend.staff.modal.__edit_staff')
    @endcan
    <!-- Modal for Edit Staff-->

    <!-- Delete Confirmation Modal -->
    @include('backend.staff.include.__delete')



@endsection

@section('script')
    <script>

        $('body').on('click', '#create-staff', function (event) {
            "use strict";
            event.preventDefault();
            const createStaffRoute = "{{ route('admin.staff.create') }}";
            $('#edit-staff-body').empty();
            $('#loader_placeholder').removeClass('hidden');

            $.get(createStaffRoute, function (data) {
                $('#edit-staff-body').append(data);
                $('#loader_placeholder').addClass('hidden');
            });

        })

        $('#staffStatusFilter').change(function() {
            var status = $(this).val();

            $.ajax({
                url: "{{ route('admin.staff.index') }}",
                type: 'GET',
                data: { status: status },
                success: function(response) {
                    // Update the staff list
                    $('#staff-list').html(response.staffs);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching staff:', error);
                }
            });
        });

        $('body').on('click', '.edit-staff', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-staff-body').empty();
            $('#loader_placeholder').removeClass('hidden');
            var id = $(this).data('id');

            $.get('staff/' + id + '/edit', function (data) {
                $('#edit-staff-body').append(data);
                $('#loader_placeholder').addClass('hidden');
            });
        })

        $(document).ready(function () {
            let deleteSchemaId = null;

            // Event listener for delete buttons
            $('.delete-schema-btn').on('click', function (e) {
                e.preventDefault();
                deleteSchemaId = $(this).data('id');
                $('#deleteConfirmationModal').modal('show');
            });

            // Event listener for the confirm delete button in the modal
            $('#confirmDeleteButton').on('click', function () {
                const input = $('#deleteConfirmationInput').val();
                if (input.toLowerCase() === 'delete') {
                    // Create a form and submit it
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': '{{ route('admin.staff.delete', ':id') }}'.replace(':id', deleteSchemaId)
                    });

                    // Add the CSRF token and method fields
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': csrfToken
                    }));

                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
                } else {
                    alert('You must type "delete" to confirm.');
                }
            });
        });

        $(".dateOfBirth").flatpickr({
            dateFormat: "d.m.Y",
            maxDate: "15.12.2017"
        });

    </script>
@endsection
