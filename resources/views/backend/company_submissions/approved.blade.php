@extends('backend.company_submissions.pending')
@section('title')
    {{ __('Approved Company Form Submissions') }}
@endsection
@section('script')
<script>
    (function ($) {
        "use strict";
        var table = $('#submissionsTable').DataTable();
        table.destroy();
        var table = $('#submissionsTable')
        .on('processing.dt', function (e, settings, processing) {
            $('#processingIndicator').css('display', processing ? 'block' : 'none');
        }).DataTable({
            dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
            searching: false,
            lengthChange: false,
            info: true,
            order: [[0, 'desc']],
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: { previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>", next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>" },
                search: "Search:"
            },
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('admin.company-form-submissions.data', 'approved') }}",
                data: function (d) {
                    d.search = $('#search').val();
                    d.created_at = $('#created_at').val();
                }
            },
            columns: [
                {data: 'created_at', name: 'created_at'},
                {data: 'username', name: 'username'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('body').on('click', '.submission-action-btn', function () {
            $('.submission-action').empty();
            var id = $(this).data('id');
            var url = '{{ route("admin.company-form-submissions.action.modal", ":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {
                $('.submission-action').append(data);
                $('#submission-action-modal').modal('show');
                // Disable Approve, enable Reject
                $('.btn-approve').prop('disabled', true);
                $('.btn-reject').prop('disabled', false);
            });
        });

        $('#filter').click(function () { table.draw(); });

        const input = document.getElementById("created_at");
        const clearBtn = document.getElementById("clearBtn");
        const fp = flatpickr(input, { altInput:false, dateFormat:"Y-m-d", allowInput:false, mode:"range" });
        clearBtn.addEventListener("click", () => { fp.clear(); });

        $(document).on('companyFormSubmissionActionCompleted', function(_, payload) {
            if (typeof table !== 'undefined' && table.draw) { table.draw(); }
            if (payload && typeof tNotify === 'function') {
                if (payload.status === 'approved') {
                    tNotify('success', '{{ __('Submission approved successfully') }}');
                } else if (payload.status === 'rejected') {
                    tNotify('success', '{{ __('Submission rejected successfully') }}');
                } else if (payload.status === 'error') {
                    tNotify('error', '{{ __('Failed to update submission status. Please try again.') }}');
                } else {
                    tNotify('success', '{{ __('Submission status updated') }}');
                }
            }
        });
    })(jQuery);
</script>
@endsection



