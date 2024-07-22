@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Schema') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('All IB Account Type') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('schema-create')
                <a href="{{route('admin.ibAccountType.create')}}" class="inline-flex items-center justify-center text-success-600">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add New') }}
                </a>
            @endcan
        </div>
    </div>

    @include('backend.forex_schema.include.__menu')

    <div class="card">
        <div class="card-body px-6 pb-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Badge') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($schemas as $schema)
                                    <tr>
                                        <td class="table-td">
                                            {{$schema->title}}
                                        </td>
                                        <td class="table-td">
                                            {{$schema->group}}
                                        </td>
                                        <td class="table-td">
                                            <div @class([
                                            'badge bg-opacity-30 capitalize', // common classes
                                            'bg-success-500 text-success-500' => $schema->badge,
                                            'bg-warning-500 text-warning-500' => !$schema->badge
                                            ])>{{ $schema->badge ? $schema->badge : 'No Feature Badge' }}</div>
                                        </td>
                                        <td class="table-td">
                                            <div @class([
                                            'badge bg-opacity-30 capitalize', // common classes
                                            'bg-success-500 text-success-500' => $schema->status,
                                            'bg-danger-500 text-danger-500' => !$schema->status
                                            ])>{{ $schema->status ? 'Active' : 'Deactivated' }}</div>
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @can('schema-edit')
                                                    <a href="{{route('admin.ibAccountType.edit',$schema->id)}}" class="action-btn">
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                    </a>
                                                @endcan
                                                @can('schema-delete')
                                                    <a href="#" class="action-btn delete-schema-btn" data-id="{{ $schema->id }}">
                                                        <iconify-icon icon="lucide:trash"></iconify-icon>
                                                    </a>
                                                @endcan
                                            </div>
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
    @include('backend.ib_schema.include.__delete')
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
                    'action': '{{ route('admin.ibAccountType.delete', ':id') }}'.replace(':id', deleteSchemaId)
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