@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Staff') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Manage Staffs') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('staff-create')
                <a href="{{ route('admin.staff.create') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add New Staff') }}
                </a>
            @endcan
        </div>

    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Role') }}</th>
                                    <th scope="col" class="table-th">{{ __('Email') }}</th>
                                    <th scope="col" class="table-th">{{ __('Designation') }}</th>
                                    <th scope="col" class="table-th">{{ __('Department') }}</th>
                                    <th scope="col" class="table-th">{{ __('Location') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($staffs as $staff)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{$staff->name}}</strong>
                                    </td>
                                    <td class="table-td">
                                        {{ $staff->getRoleNames()->first() }}
                                    </td>
                                    <td class="table-td">{{ $staff->email }}</td>
                                    <td class="table-td">
                                        @if(isset($staff->designation))
                                            {{ $staff->designation->name }}
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if(isset($staff->department))
                                            {{ $staff->department->name }}
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td class="table-td">{{ $staff->location }}</td>
                                    <td class="table-td">
                                        @if($staff->status)
                                            <div class="badge bg-success text-success bg-opacity-30 capitalize">
                                                {{ __('Active') }}
                                            </div>
                                        @else
                                            <div class="badge bg-danger text-danger bg-opacity-30 capitalize">
                                                {{ __('InActive') }}
                                            </div>
                                        @endif

                                    </td>
                                    <td class="table-td">

                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="{{route('admin.staff.security',$staff->id)}}"
                                                 class="toolTip onTop action-btn"
                                                        data-id="{{$staff->id}}" type="button" id="edit1"
                                                        data-tippy-theme="tooltip"
                                                        data-tippy-content="2FA Security"
                                                >
                                                    <iconify-icon icon="mdi:security"></iconify-icon>
{{--                                                </button><button class="round-icon-btn primary-btn"--}}
{{--                                                                                                           data-id="{{$staff->id}}" type="button"--}}
{{--                                                                                                           data-bs-toggle="tooltip" title=""--}}
{{--                                                                                                           data-bs-placement="top"--}}
{{--                                                                                                           data-bs-original-title="2FA Security">--}}
{{--                                                    <i icon-name="edit-3"></i>--}}
{{--                                                </button>--}}
                                            </a>

                                        @if($staff->getRoleNames()->first() === 'Super-Admin')
                                                <button class="toolTip onTop action-btn" type="button"
                                                    data-tippy-theme="tooltip"
                                                    data-tippy-content="Not Editable">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </button>
                                            @else
                                                @can('staff-edit')
                                                    <a href="{{route('admin.staff.edit',$staff->id)}}" class="toolTip onTop action-btn"
                                                        data-tippy-theme="tooltip"
                                                        data-tippy-content="Edit Staff">
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                    </a>
                                                @endcan
                                                @can('staff-delete')
                                                    <button type="button" class="action-btn delete-schema-btn" data-id="{{ $staff->id }}">
                                                        <iconify-icon icon="lucide:trash"></iconify-icon>
                                                    </button>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $staffs->firstItem(); // The starting item number on the current page
                                    $to = $staffs->lastItem(); // The ending item number on the current page
                                    $total = $staffs->total(); // The total number of items
                                @endphp

                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $from }}</span>
                                    to
                                    <span class="font-medium">{{ $to }}</span>
                                    of
                                    <span class="font-medium">{{ $total }}</span>
                                    results
                                </p>
                            </div>
                            {{ $staffs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Staff-->

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
        $('body').on('click', '#edit', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-staff-body').empty();
            var id = $(this).data('id');

            $.get('staff/' + id + '/edit', function (data) {

                $('#editModal').modal('show');
                $('#edit-staff-body').append(data);

            })
        })
    </script>
    <script>
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
