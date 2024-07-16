@extends('backend.setting.index')
@section('title')
    {{ __('Roles & Permissions') }}
@endsection
@section('setting-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Roles & Permissions') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('role-create')
                <a href="{{route('admin.roles.create')}}" class="inline-flex items-center justify-center text-success-600">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add New Role') }}
                </a>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card-body px-6 pb-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($roles as  $role)
                                <tr>
                                    <td class="table-td">{{ ++$loop->index }}</td>
                                    <td class="table-td"><strong>{{ str_replace('-',' ',$role->name) }}</strong></td>
                                    <td class="table-td">
                                        @if($role->name == 'Super-Admin')
                                            <button class="btn btn-danger btn-sm inline-flex items-center justify-center">
                                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:alert-triangle"></iconify-icon>
                                                {{ __('Not Editable') }}
                                            </button>
                                        @else
                                            @can('role-edit')
                                                <a href="{{route('admin.roles.edit',$role->id)}}"
                                                class="btn btn-dark btn-sm inline-flex items-center justify-center">
                                                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:edit-3"></iconify-icon>
                                                    {{ __('Edit Permission') }}
                                                </a>
                                            @endcan
                                            @can('role-delete')
                                                <button type="button" class="btn btn-dark btn-sm inline-flex items-center justify-center delete-schema-btn table-btn" data-id="{{ $role->id }}">
                                                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:trash"></iconify-icon>
                                                    {{ __('Delete Role') }}
                                                </button>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @include('backend.roles.include.__delete')

@endsection
@section('script')
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
                        'action': '{{ route('admin.role.delete', ':id') }}'.replace(':id', deleteSchemaId)
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
    </script>
@endsection
