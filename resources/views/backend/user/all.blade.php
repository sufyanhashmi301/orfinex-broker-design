@extends('backend.user.index')
@section('title')
    {{ __('All Customers') }}
@endsection
@section('customers-content')
    <div class="card">
        <form id="filter-form" method="POST" action="{{ route('admin.user.export') }}">
            @csrf
            <div class="row mb-3">
                <div class="col-md-2">
                    <input type="text" name="global_search" id="global_search" class="form-control" placeholder="Search by Name, Username, Email">
                </div>
                <div class="col-md-2">
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone">
                </div>
                <div class="col-md-2">
                    <input type="text" name="country" id="country" class="form-control" placeholder="Country">
                </div>
                <div class="col-md-2">
                    <select name="status" id="status" class="form-control">
                        <option value="">{{ __('Select Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="created_at" id="created_at" class="form-control" placeholder="Created At">
                </div>
                <div class="col-md-2">
                    <input type="text" name="tag" id="tag" class="form-control" placeholder="Tag">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <button type="button" id="filter" class="btn btn-primary">{{ __('Filter') }}</button>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success">{{ __('Export') }}</button>
                </div>
            </div>
        </form>
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

            var table = $('#dataTable').DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
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
                searching: false, // Disable default search box
                ajax: {
                    url: "{{ route('admin.user.index') }}",
                    data: function (d) {
                        d.global_search = $('#global_search').val();
                        d.phone = $('#phone').val();
                        d.country = $('#country').val();
                        d.status = $('#status').val();
                        d.created_at = $('#created_at').val();
                        d.tag = $('#tag').val();
                    }
                },
                columns: [
                    {"class": "table-td", data: 'avatar', name: 'avatar'},
                    {"class": "table-td", data: 'username', name: 'username'},
                    {"class": "table-td", data: 'email', name: 'email'},
                    {"class": "table-td", data: 'balance', name: 'balance'},
                    // {"class": "table-td", data: 'total_profit', name: 'total_profit', orderable: false, searchable: false},
                    {"class": "table-td", data: 'kyc', name: 'kyc'},
                    {"class": "table-td", data: 'status', name: 'status'},
                    {"class": "table-td", data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#filter').click(function () {
                table.draw();
            });

            $('#global_search').keyup(function() {
                table.draw();
            });

            //send mail modal form open
            $('body').on('click', '.send-mail', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#name').html(name);
                $('#userId').val(id);
                $('#sendEmail').modal('toggle')
            });

        })(jQuery);
    </script>
@endsection
