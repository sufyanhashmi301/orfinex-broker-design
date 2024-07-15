@extends('backend.withdraw.index')
@section('title')
    {{ __('Pending Withdraws') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('withdraw_content')
    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Transaction ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    <th scope="col" class="table-th">{{ __('Charge') }}</th>
                                    <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!-- Modal for Pending Deposit Approval -->
    @can('withdraw-action')
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="deposit-action-modal" tabindex="-1" aria-labelledby="deposit-action-modal" aria-hidden="true">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="modal-body popup-body p-6">
                        <div class="popup-body-text withdraw-action">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <!-- Modal for Pending Deposit Approval -->
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#dataTable').DataTable();
            table.destroy();

            var table = $('#dataTable').DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],
                language: {
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
                search: "Search:"
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.withdraw.pending') }}",
                columns: [
                    {"class": "table-td", data: 'created_at', name: 'created_at'},
                    {"class": "table-td", data: 'username', name: 'username'},
                    {"class": "table-td", data: 'tnx', name: 'tnx'},
                    {"class": "table-td", data: 'target_id', name: 'target_id'},
                    {"class": "table-td", data: 'amount', name: 'amount'},
                    {"class": "table-td", data: 'charge', name: 'charge'},
                    {"class": "table-td", data: 'method', name: 'method'},
                    {"class": "table-td", data: 'status', name: 'status'},
                    {"class": "table-td", data: 'action', name: 'action'},
                ]
            });


            //send mail modal form open
            $('body').on('click', '#withdraw-action', function () {
                $('.withdraw-action').empty();

                var id = $(this).data('id');
                var url = '{{ route("admin.withdraw.action",":id") }}';
                url = url.replace(':id', id);
                $.get(url, function (data) {
                    $('.withdraw-action').append(data)
                    imagePreview()
                })
                $('#deposit-action-modal').modal('toggle')

            })

        })(jQuery);
    </script>
@endsection
