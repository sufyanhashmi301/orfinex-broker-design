@extends('backend.layouts.app')
@section('title')
    {{ __('All Customers') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All Customers') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body table-responsive">
                            <div class="site-datatable">
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
                                <table id="dataTable" class="display data-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Avatar') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                        {{--                                        <th>{{ __('Profit') }}</th>--}}
                                        <th>{{ __('KYC') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for Send Email -->
                @can('customer-mail-send')
                    @include('backend.user.include.__mail_send')
                @endcan
                <!-- Modal for Send Email-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        (function ($) {
            "use strict";

            var table = $('#dataTable').DataTable({
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
                    {data: 'avatar', name: 'avatar'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'balance', name: 'balance'},
                    // {data: 'total_profit', name: 'total_profit', orderable: false, searchable: false},
                    {data: 'kyc', name: 'kyc'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
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
