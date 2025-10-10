@extends('backend.layouts.app')

@section('title', __('Deposit Vouchers'))

@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @lang('Deposit Vouchers')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('deposit-voucher-create')
                <button type="button" class="btn btn-sm btn-primary inline-flex items-center justify-center" data-bs-toggle="modal" data-bs-target="#newVoucher">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    @lang('Create Voucher')
                </button>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="deposit-vouchers-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">@lang('Title')</th>
                                    <th scope="col" class="table-th">@lang('Voucher Code')</th>
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

    @include('backend.deposit-vouchers.include.__create')
    @include('backend.deposit-vouchers.include.__edit')
    @include('backend.deposit-vouchers.include.__delete')
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#expiry_date').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        var table = $('#deposit-vouchers-table')
        .on('processing.dt', function (e, settings, processing) {
            $('#processingIndicator').css('display', processing ? 'block' : 'none');
        }).DataTable({
            dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
            searching: false,
            lengthChange: false,
            info: true,
            order: [[3, 'desc']],
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
            ajax: "{{ route('admin.deposit-vouchers.index') }}",
            columns: [
                {data: 'title', name: 'title'},
                {data: 'code', name: 'code'},
                {data: 'amount', name: 'amount'},
                {data: 'expiry_date', name: 'expiry_date'},
                {data: 'status', name: 'status'},
                {data: 'used_by', name: 'used_by'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('body').on('click', '.editVoucher', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-voucher-body').empty();
            var id = $(this).data('id');

            $.get('deposit-vouchers/' + id + '/edit', function (data) {

                $('#editVoucher').modal('show');
                $('#edit-voucher-body').append(data);
                $('.select2').select2();
                $(".flatpickr").flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                });

            })
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

        $(document).on('click', '.copy-btn', function () {
            const $button = $(this);
            const code = $button.data('code');

            // Create a temporary input element
            const $tempInput = $('<input>');
            $('body').append($tempInput);
            $tempInput.val(code).select();
            document.execCommand('copy');
            $tempInput.remove();

            $button.addClass('text-success border-success');

            // Revert changes after delay
            setTimeout(() => {
                $button.removeClass('text-success border-success');
            }, 2000); // 2 seconds
        });

    });
</script>
@endsection
