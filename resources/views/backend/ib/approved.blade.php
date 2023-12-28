@extends('backend.layouts.app')
@section('title')
    {{ __('Approved Customers') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Approved Customers') }}</h2>
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
                                <table id="dataTable" class="display data-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Avatar') }}</th>
                                        <th>{{ __('Username') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('KYC') }}</th>
                                        <th>{{ __('IB Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for confirm IB -->
{{--                @can('customer-mail-send')--}}
                    @include('backend.ib.modal.__ib_confirm')
{{--                @endcan--}}
                <!-- Modal for confirm IB -->
                    <!-- Modal for reject IB -->
                {{--                @can('customer-mail-send')--}}
                @include('backend.ib.modal.__ib_reject')
                {{--                @endcan--}}
                <!-- Modal for reject IB-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    ]
    <script>
        (function ($) {
            "use strict";

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.ib.pending') }}",
                columns: [
                    {data: 'avatar', name: 'avatar'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'kyc', name: 'kyc'},
                    {data: 'ib_status', name: 'ib_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            //confirm IB
            $('#dataTable').on('click', '.approve-btn', function() {
                // Open the confirmation modal
                $('#confirmModal').modal('show');
                var rowData = table.row($(this).closest('tr')).data()
                // Handle the "Confirm" button click inside the modal
                $('#confirmBtn').on('click', function() {
                    // var rowData = table.row($(this).closest('tr')).data();
                    approveUser(rowData.id);

                });
            });

            //reject IB
            $('#dataTable').on('click', '.reject-btn', function() {
                // Open the confirmation modal
                $('#rejectModal').modal('show');
                var rowData = table.row($(this).closest('tr')).data();

                // Handle the "Confirm" button click inside the modal
                $('#rejectBtn').on('click', function() {
                    // console.log('rowData')
                    // var rowData = table.row($(this).closest('tr')).data();

                    rejectUser(rowData.id);
                });
            });

            // Function to user
            function approveUser(userId) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.ib.approve") }}',
                    data: {user_id: userId},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if(res.success){
                            tNotify('success', res.success);
                            $('#confirmModal').modal('hide');
                            if(res.reload) {
                                setTimeout(function(){ location.reload(); }, 900);
                            }
                            if(res.redirect) {
                                setTimeout(function(){ window.location.replace(res.redirect); }, 900);
                            }
                            if (res.modal) {
                                $('#'+modalId).modal('toggle');
                                // NioApp.Form.errors(res, true);
                                // btn.prop('disabled', false);
                            }
                        }
                        else if(res.append){
                            $('#'+appendId).html(res.append);
                            // NioApp.Toast(res.error, 'warning');
                            // setTimeout(function(){ location.reload(); }, 900);
                        }
                        else if(res.error){
                            // NioApp.Toast(res.error, 'warning');
                            // tNotify('warning', res.message);
                            tNotify('warning', res.error);
                            // setTimeout(function(){ location.reload(); }, 900);
                        }
                        else if (res.errors) {
                            NioApp.Form.errors(res, true);
                            tNotify('warning', res.message);
                            btn.prop('disabled', false);
                        }
                        table.ajax.reload();
                    },
                    error: function(error) {
                        // console.log(data.responseJSON.message,'data.message')
                        tNotify('warning', error.responseJSON.message);
                        // console.error(error);
                    }
                });
            }
            function rejectUser(userId) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.ib.reject") }}',
                    data: {user_id: userId},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if(res.success){
                            tNotify('success', res.success);

                            $('#rejectModal').modal('hide');
                            if(res.reload) {
                                setTimeout(function(){ location.reload(); }, 900);
                            }
                            if(res.redirect) {
                                setTimeout(function(){ window.location.replace(res.redirect); }, 900);
                            }
                            if (res.modal) {
                                $('#'+modalId).modal('toggle');
                                // NioApp.Form.errors(res, true);
                                // btn.prop('disabled', false);
                            }
                        }
                        else if(res.append){
                            $('#'+appendId).html(res.append);
                            // NioApp.Toast(res.error, 'warning');
                            // setTimeout(function(){ location.reload(); }, 900);

                            $(".form-select").select2({
                                matcher: matchCustom,
                                templateResult: formatState,
                                templateSelection: formatState
                            });

                            function stringMatch(term, candidate) {
                                return candidate && candidate.toLowerCase().indexOf(term.toLowerCase()) >= 0;
                            }
                            function matchCustom(params, data) {
                                // If there are no search terms, return all of the data
                                if ($.trim(params.term) === '') {
                                    return data;
                                }
                                // Do not display the item if there is no 'text' property
                                if (typeof data.text === 'undefined') {
                                    return null;
                                }
                                // Match text of option
                                if (stringMatch(params.term, data.text)) {
                                    return data;
                                }
                                // Match attribute "data-foo" of option
                                if (stringMatch(params.term, $(data.element).attr('data-des'))) {
                                    return data;
                                }
                                // Return `null` if the term should not be displayed
                                return null;
                            }
                            function formatState(opt) {
                                if (!opt.id) {
                                    return opt.text.toUpperCase();
                                }

                                var optimage = $(opt.element).attr('data-image');
                                var optdes = $(opt.element).attr('data-des');
                                // console.log(optimage)
                                if (!optimage) {
                                    return opt.text.toUpperCase();
                                } else {
                                    var $opt = $(
                                        '<div class="coin-item coin-btc"><div class="coin-icon"><img src="' + optimage + '" class="mr-2" width="40px" /></div><div class="coin-info"><span class="coin-name">' + opt.text.toUpperCase() + '</span><ul class="kanban-item-meta-list">' + optdes + '</ul></div></div>'
                                    );
                                    return $opt;
                                }
                            }
                        }
                        else if(res.error){
                            // NioApp.Toast(res.error, 'warning');
                            // tNotify('warning', res.message);
                            tNotify('warning', res.error);
                            // setTimeout(function(){ location.reload(); }, 900);
                        }
                        else if (res.errors) {
                            NioApp.Form.errors(res, true);
                            tNotify('warning', res.message);
                            btn.prop('disabled', false);
                        }
                        table.ajax.reload();
                    },
                    error: function(error) {
                        // console.log(data.responseJSON.message,'data.message')
                        tNotify('warning', error.responseJSON.message);
                        // console.error(error);
                    }
                });
            }

            //send mail modal form open
            $('body').on('click', '.send-mail', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#name').html(name);
                $('#userId').val(id);
                $('#sendEmail').modal('toggle')
            })
        })(jQuery);
    </script>
@endsection
