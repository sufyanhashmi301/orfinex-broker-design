<!-- Notes Tab -->
<div class="tab-pane fade space-y-5" id="pills-note" role="tabpanel" aria-labelledby="pills-note-tab">
    @can('customer-notes-create')
    <div class="flex justify-end items-center mb-3">
        <button class="btn btn-primary btn-sm inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addNotesModal">
            {{ __('Add Notes') }}
        </button>
    </div>
    @endcan
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="notes-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Description') }}</th>
                                    <th scope="col" class="table-th">{{ __('Added From') }}</th>
                                    <th scope="col" class="table-th">{{ __('Date Added') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Include Add, Edit, and Delete Modals -->
@can('customer-notes-create')
@include('backend.user.notes.include.__add_notes')
@endcan
@can('customer-notes-edit')
@include('backend.user.notes.include.__edit_notes')
@endcan
@can('customer-notes-delete')
@include('backend.user.notes.include.__delete_notes')
@endcan
@push('single-script')
<script>
    (function ($) {
        "use strict";

        var table = $('#notes-table').DataTable({
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
    ajax: "{{ route('admin.user.note.data', $user->id) }}",
    columns: [
        {data: 'description', name: 'description'},
        {data: 'staff', name: 'staff'},
        {
            data: 'created_at',
            name: 'created_at',
            render: function(data) {
                // Create a new Date object
                var date = new Date(data);

                // Get year, month, day, hours, minutes, and seconds
                var year = date.getFullYear();
                var month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
                var day = String(date.getDate()).padStart(2, '0');
                var hours = String(date.getHours()).padStart(2, '0');
                var minutes = String(date.getMinutes()).padStart(2, '0');
                var seconds = String(date.getSeconds()).padStart(2, '0');

                // Format the date as 'YYYY-MM-DD HH:MM:SS'
                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }
        },
        {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
});



        // Edit note
        $(document).on('click', '.edit-note', function() {
            var noteId = $(this).data('id');
            var noteDescription = $(this).data('description');
            var editUrl = $(this).data('url'); // No need for userId in the URL

            // Set the form action dynamically
            $('#edit-note-form').attr('action', editUrl);

            // Populate the textarea with the current note description
            $('#edit_notes').val(noteDescription);

            // Open the modal
            $('#editNotesModal').modal('show');
        });

        // Handle form submission (updating the note)
        $('#edit-note-form').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = $(this).serialize(); // Serialize form data
            var actionUrl = $(this).attr('action'); // Get the action URL

            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#editNotesModal').modal('hide'); // Close the modal
                    table.ajax.reload(null, false); // Reload DataTable without resetting pagination
                    notify('success', 'Note updated successfully!'); // Use notify for success
                },
                error: function(xhr) {
                    notify('error', 'Error updating note: ' + xhr.responseJSON.message); // Use notify for error
                }
            });
        });

        // Handle delete note
        $(document).on('click', '.delete-note', function() {
            var noteId = $(this).data('id'); // Only note ID is needed now
            var deleteUrl = '{{ route("admin.user.note.delete", ":id") }}'.replace(':id', noteId); // Updated route without userId

            // Set the form action dynamically
            $('#deleteNoteForm').attr('action', deleteUrl);

            // Open the delete confirmation modal
            $('#deleteConfirmationModal').modal('show');
        });

        // Submit the delete form via AJAX
        $('#deleteNoteForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            var formAction = $(this).attr('action'); // Get the form action URL

            $.ajax({
                url: formAction,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success); // Notify success
                        $('#deleteConfirmationModal').modal('hide'); // Hide modal
                        table.ajax.reload(null, false); // Reload the DataTable without resetting pagination
                    } else if (response.error) {
                        notify('error', response.error); // Notify error
                    }
                },
                error: function(xhr) {
                    notify('error', 'Failed to delete the note.'); // Handle error
                }
            });
        });
    })(jQuery);
</script>
@endpush
