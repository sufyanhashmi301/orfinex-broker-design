@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Schema') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All IB Account Type') }}</h2>
                            @can('ib-account-type-create')
                                <a href="{{route('admin.ibAccountType.create')}}" class="title-btn"><i
                                        icon-name="plus-circle"></i>{{ __('Add New') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <div class="site-table table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Title') }}</th>
                                        <th scope="col">{{ __('Group') }}</th>
                                        <th scope="col">{{ __('Badge') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($schemas as $schema)
                                        <tr>
                                            <td><strong>{{$schema->title}}</strong></td>
                                            <td>
                                                <strong>{{$schema->group}}</strong>
                                            </td>

                                            <td>
                                                <div @class([
                                                'site-badge', // common classes
                                                'success' => $schema->badge,
                                                'pending' => !$schema->badge
                                                ])>{{ $schema->badge ? $schema->badge : 'No Feature Badge' }}</div>
                                            </td>
                                            <td>
                                                <div @class([
                                                'site-badge', // common classes
                                                'success' => $schema->status,
                                                'danger' => !$schema->status
                                                ])>{{ $schema->status ? 'Active' : 'Deactivated' }}</div>
                                            </td>
                                            <td>
                                                @can('schema-edit')
                                                    <a href="{{route('admin.ibAccountType.edit',$schema->id)}}"
                                                       class="round-icon-btn primary-btn">
                                                        <i icon-name="edit-3"></i>
                                                    </a>
                                                @endcan
                                                @can('schema-delete')
                                                    <button type="button" class="round-icon-btn danger-btn delete-schema-btn" data-id="{{ $schema->id }}">
                                                        <i icon-name="trash"></i>
                                                    </button>
                                                @endcan
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
    </div>

    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">{{ __('Are you sure?') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Please type "delete" to confirm.') }}</p>
                    <input type="text" id="deleteConfirmationInput" class="form-control" placeholder="delete">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" id="confirmDeleteButton" class="btn btn-danger">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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