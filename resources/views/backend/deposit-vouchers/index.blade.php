@extends('backend.layouts.app')

@section('title', __('Deposit Vouchers'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('Deposit Vouchers')</h4>
                    <div class="card-toolbar">
                        @can('deposit-voucher-create')
                            <button type="button" class="btn btn-primary addVoucher">
                                <i class="fas fa-plus"></i> @lang('Create Voucher')
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body relative px-6">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="deposit-vouchers-table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">@lang('Title')</th>
                                            <th scope="col" class="table-th">@lang('Amount')</th>
                                            <th scope="col" class="table-th">@lang('Expiry Date')</th>
                                            <th scope="col" class="table-th">@lang('Status')</th>
                                            <th scope="col" class="table-th">@lang('Used By')</th>
                                            <th scope="col" class="table-th">@lang('Actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="processingIndicator" class="text-center">
                        <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.deposit-vouchers.include.__create')
    @include('backend.deposit-vouchers.include.__edit')
    @include('backend.deposit-vouchers.include.__delete')
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#deposit-vouchers-table')
        .on('processing.dt', function (e, settings, processing) {
            $('#processingIndicator').css('display', processing ? 'block' : 'none');
        }).DataTable({
            dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'ip>",
            paging: true,
            ordering: true,
            info: true,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
                search: "Search:"
            },
            serverSide: true,
            autoWidth: false,
            ajax: "{{ route('admin.deposit-vouchers.index') }}",
            columns: [
                {data: 'title', name: 'title'},
                {data: 'amount', name: 'amount'},
                {data: 'expiry_date', name: 'expiry_date'},
                {data: 'status', name: 'status'},
                {data: 'used_by', name: 'used_by'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        // Handle create button click
        $('.addVoucher').on('click', function(e) {
            e.preventDefault();
            $('#createModal').modal('show');
        });

        // Handle create form submission
        $('#createForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#createModal').modal('hide');
                    table.ajax.reload();
                    form[0].reset();
                    $.notify('@lang("Deposit voucher created successfully.")', 'success');
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '_error').text(value[0]);
                    });
                }
            });
        });

        // Handle edit button click
        $(document).on('click', '.editVoucher', function(e) {
            e.preventDefault();
            $('#edit-voucher-body').empty();
            var id = $(this).data('id');

            $.get('deposit-vouchers/' + id + '/edit', function(data) {
                $('#editModal').modal('show');
                $('#edit-voucher-body').append(data);
            });
        });

        // Handle edit form submission
        $(document).on('submit', '#editForm', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    $.notify('@lang("Deposit voucher updated successfully.")', 'success');
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '_error').text(value[0]);
                    });
                }
            });
        });

        // Handle delete
        $(document).on('click', '.deleteVoucher', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var title = $(this).data('title');

            var url = '{{ route("admin.deposit-vouchers.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#voucherDeleteForm').attr('action', url);

            $('.title').html(title);
            $('#deleteVoucher').modal('show');
        });

        // Handle delete form submission
        $('#voucherDeleteForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteVoucher').modal('hide');
                    table.ajax.reload();
                    $.notify('@lang("Deposit voucher deleted successfully.")', 'success');
                }
            });
        });
    });
</script>
@endsection 