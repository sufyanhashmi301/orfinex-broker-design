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
        <a href="javascript:;" class="staffList-open-btn items-center justify-center dark:text-white p-1">
            <iconify-icon class="text-lg font-medium" icon="mdi:dots-vertical"></iconify-icon>
        </a>
    </div>
    <div class="card border dark:border-slate-700">
        <div class="grid grid-cols-12">
            <div class="medium:col-span-4 col-span-12">
                <div class="mobile-close-overlay w-full h-full" id="close-settings-overlay"></div>
                <div class="h-full border-r dark:border-slate-700" id="staff-list__container">
                    <a href="javascript:;" class="staffList-close-btn btn-primary absolute items-center justify-center p-2">
                        <iconify-icon class="text-lg font-medium" icon="material-symbols:close-rounded"></iconify-icon>
                    </a>
                    <div class="card-header gap-2 pl-0" style="padding-bottom: 11px;">
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
                                    <span class="text-nowrap">{{ __('Add New Staff') }}</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div id="staff-list" class="p-6 pr-0">
                        @include('backend.staff.include.__staff_list', ['staff' => $staffs])
                    </div>
                </div>
            </div>
            <div class="medium:col-span-8 col-span-12">
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

    <!-- Delete Confirmation Modal -->
    @include('backend.staff.include.__delete')

    {{-- Detach User Modal--}}
    @include('backend.staff.modal.__detach_user')

@endsection

@section('style')
    <style>
        .dataTables_wrapper {
            min-height: unset !important;
        }
    </style>
@endsection

@section('script')
    <script>
        $('.staffList-open-btn').click(function(){
            $('#staff-list__container, .mobile-close-overlay').addClass('in');
        });

        $('.staffList-close-btn').click(function(){
            $('#staff-list__container, .mobile-close-overlay').removeClass('in');
        });

        $('body').on('click', '#create-staff', function (event) {
            "use strict";
            event.preventDefault();
            const createStaffRoute = "{{ route('admin.staff.create') }}";
            $('#edit-staff-body').empty();
            $('#loader_placeholder').removeClass('hidden');

            $.get(createStaffRoute, function (data) {
                $('#edit-staff-body').append(data.html);
                $('#loader_placeholder').addClass('hidden');

                $('.select2').select2();
                $('.flatpickr').flatpickr();
                $(".dateOfBirth").flatpickr({
                    dateFormat: "d.m.Y",
                    maxDate: "15.12.2017"
                });

            });

        });

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
                $('.select2').select2();
                $('.flatpickr').flatpickr();
                $(".dateOfBirth").flatpickr({
                    dateFormat: "d.m.Y",
                    maxDate: "15.12.2017"
                });

                $('.data-table').DataTable().destroy();
                $(".data-table").DataTable({
                    dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
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
                });

            });
        });

        $(document).on('submit', '#update-staff__form', function (event) {
            event.preventDefault();

            var form = $('#update-staff__form')[0];
            var data = new FormData(form);

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var staffId = $('#staff-id').val();

            var url = "{{ route('admin.staff.update', ':id') }}".replace(':id', staffId);

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-HTTP-METHOD-OVERRIDE': 'PUT'
                },
                success: function (response) {
                    if (response.success) {
                        tNotify('success', response.message);
                        $('#edit-staff-body').html(response.updatedHtml);
                        $('.select2').select2();
                        $('.flatpickr').flatpickr();
                        $(".dateOfBirth").flatpickr({
                            dateFormat: "d.m.Y",
                            maxDate: "15.12.2017"
                        });

                        $('.data-table').DataTable().destroy();
                        $(".data-table").DataTable({
                            dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
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
                        });
                    } else {
                        tNotify('error', response.message);
                    }
                },
                error: function (xhr) {
                    var message = xhr.responseJSON?.message || 'An unexpected error occurred.';
                    tNotify('error', message);
                }
            });
        });


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

        $('body').on('click', '.userDetachBtn', function (e) {
            e.preventDefault();
            let userId = $(this).data('user-id');
            let staffId = $(this).data('staff-id');
            var name = $(this).data('name');

            var url = '{{ route("admin.staff.detachUser", ":staffId") }}';
            url = url.replace(':staffId', staffId);
            $('#detachUserForm').attr('action', url);

            $('#userIdInput').val(userId);
            $('.name').html(name);
            $('#detachUserModal').modal('show');
        });

        // Handle the form submission using AJAX
        $('#detachUserForm').on('submit', function (e) {
            e.preventDefault();
            $('#detachUserModal').modal('hide');

            var formData = $(this).serialize();

            // Perform the AJAX request
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle the response on success
                    tNotify('success', response.message);
                    $('#edit-staff-body').html(response.updatedHtml);
                    $('.select2').select2();
                    $('.flatpickr').flatpickr();
                    $(".dateOfBirth").flatpickr({
                        dateFormat: "d.m.Y",
                        maxDate: "15.12.2017"
                    });

                    $('.data-table').DataTable().destroy();
                    $(".data-table").DataTable({
                        dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
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
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors (if any)
                    tNotify('error', 'An error occurred while detaching the user.');
                    console.error(xhr.responseText);
                }
            });
        });


    </script>
@endsection
