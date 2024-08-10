@extends('backend.user.index')
@section('title')
    {{ __('With Balance Customers') }}
@endsection
@section('customers-content')
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
                                    <th scope="col" class="table-th">{{ __('Avatar') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Email') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Credit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Profit') }}</th> --}}
                                    <th scope="col" class="table-th">{{ __('KYC') }}</th>
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
            <div id="processingIndicator" class="text-center">
                {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>

    <!-- Modal for Send Email -->
    @can('customer-mail-send')
        @include('backend.user.include.__mail_send')
    @endcan
    <!-- Modal for Send Email-->
@endsection

@section('script')

    <script>
        (function ($) {
            "use strict";

            var table = $('#dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
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
                ajax: "{{ route('admin.user.with_balance') }}",
                columns: [
                    {"class": "table-td", data: 'avatar', name: 'avatar'},
                    {"class": "table-td", data: 'username', name: 'username'},
                    {"class": "table-td", data: 'email', name: 'email'},
                    {"class": "table-td", data: 'balance', name: 'balance'},
                    {"class": "table-td", data: 'equity', name: 'equity'},
                    {"class": "table-td", data: 'credit', name: 'credit'},
                    {"class": "table-td", data: 'country', name: 'country'},
                    // {"class": "table-td", data: 'total_profit', name: 'total_profit', orderable: false, searchable: false},
                    {"class": "table-td", data: 'kyc', name: 'kyc'},
                    {"class": "table-td", data: 'status', name: 'status'},
                    {"class": "table-td", data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });


            //send mail modal form open
            $('body').on('click', '.send-mail', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#name').html(name);
                var url = '{{ route("admin.user.mail-send", ":id") }}';
                url = url.replace(':id', id);
                $('#send-mail-form').attr('action', url);
                $('#sendEmail').modal('toggle')

            })

        })(jQuery);
    </script>
@endsection
