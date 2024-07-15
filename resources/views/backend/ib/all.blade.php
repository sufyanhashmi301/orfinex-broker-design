@extends('backend.layouts.app')
@section('title')
    {{ __('All IB Members') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('All IB Members') }}
        </h4>
    </div>

    @include('backend.ib.include.__menu')

    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Avatar') }}</th>
                                    <th scope="col" class="table-th">{{ __('Username') }}</th>
                                    <th scope="col" class="table-th">{{ __('Email') }}</th>
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
    <!-- Modal for confirm IB -->
    {{--@can('customer-mail-send')--}}
    @include('backend.ib.modal.__ib_confirm')
    {{--@endcan--}}
    <!-- Modal for confirm IB -->
    <!-- Modal for reject IB -->
    {{--@can('customer-mail-send')--}}
    @include('backend.ib.modal.__ib_reject')
    {{--@endcan--}}
    <!-- Modal for reject IB-->
    <!-- Modal for view IB Detail-->
    {{--@can('customer-mail-send')--}}
    @include('backend.ib.modal.__ib_detail')
    {{--@endcan--}}
    <!-- Modal for view IB Detail-->
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
                ajax: "{{ route('admin.ib.all.list') }}",
                columns: [
                    {"class": "table-td", data: 'avatar', name: 'avatar'},
                    {"class": "table-td", data: 'username', name: 'username'},
                    {"class": "table-td", data: 'email', name: 'email'},
                    {"class": "table-td", data: 'kyc', name: 'kyc'},
                    {"class": "table-td", data: 'ib_status', name: 'ib_status'},
                    {"class": "table-td", data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#dataTable').on('click', '.detail-btn', function () {
                console.log('view');
                let userId = $(this).data('user-id');

                // Fetch the IB data for the user via an AJAX request
                $.ajax({
                    url: "{{ route('admin.ib.answer.view', ['user' => ':userId']) }}".replace(':userId', userId),
                    method: 'GET',
                    success: function (response) {
                        // Replace the modal content with the rendered view HTML
                        $('#jsonDataContent').html(response);

                        // Show the modal
                        $('#viewDataModal').modal('show');
                    },
                    error: function (error) {
                        console.error('Error fetching IB data:', error);
                    }
                });
            });
            //confirm IB
            $('#dataTable').on('click', '.approve-btn', function () {
                // Open the confirmation modal
                $('#confirmModal').modal('show');
                var rowData = table.row($(this).closest('tr')).data()
                // Handle the "Confirm" button click inside the modal
                $('#confirmBtn').on('click', function () {
                    // var rowData = table.row($(this).closest('tr')).data();
                    approveUser(rowData.id);

                });
            });

            //reject IB
            $('#dataTable').on('click', '.reject-btn', function () {
                // Open the confirmation modal
                $('#rejectModal').modal('show');
                var rowData = table.row($(this).closest('tr')).data();

                // Handle the "Confirm" button click inside the modal
                $('#rejectBtn').on('click', function () {
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
                    success: function (res) {
                        if (res.success) {
                            tNotify('success', res.success);
                            $('#confirmModal').modal('hide');
                            if (res.reload) {
                                setTimeout(function () {
                                    location.reload();
                                }, 900);
                            }
                            if (res.redirect) {
                                setTimeout(function () {
                                    window.location.replace(res.redirect);
                                }, 900);
                            }
                            if (res.modal) {
                                $('#' + modalId).modal('toggle');
                                // NioApp.Form.errors(res, true);
                                // btn.prop('disabled', false);
                            }
                        } else if (res.append) {
                            $('#' + appendId).html(res.append);
                            // NioApp.Toast(res.error, 'warning');
                            // setTimeout(function(){ location.reload(); }, 900);
                        } else if (res.error) {
                            // NioApp.Toast(res.error, 'warning');
                            // tNotify('warning', res.message);
                            tNotify('warning', res.error);
                            // setTimeout(function(){ location.reload(); }, 900);
                        } else if (res.errors) {
                            NioApp.Form.errors(res, true);
                            tNotify('warning', res.message);
                            btn.prop('disabled', false);
                        }
                        table.ajax.reload();
                    },
                    error: function (error) {
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
                    success: function (res) {
                        if (res.success) {
                            tNotify('success', res.success);

                            $('#rejectModal').modal('hide');
                            if (res.reload) {
                                setTimeout(function () {
                                    location.reload();
                                }, 900);
                            }
                            if (res.redirect) {
                                setTimeout(function () {
                                    window.location.replace(res.redirect);
                                }, 900);
                            }
                            if (res.modal) {
                                $('#' + modalId).modal('toggle');
                                // NioApp.Form.errors(res, true);
                                // btn.prop('disabled', false);
                            }
                        } else if (res.append) {
                            $('#' + appendId).html(res.append);
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
                        } else if (res.error) {
                            // NioApp.Toast(res.error, 'warning');
                            // tNotify('warning', res.message);
                            tNotify('warning', res.error);
                            // setTimeout(function(){ location.reload(); }, 900);
                        } else if (res.errors) {
                            NioApp.Form.errors(res, true);
                            tNotify('warning', res.message);
                            btn.prop('disabled', false);
                        }
                        table.ajax.reload();
                    },
                    error: function (error) {
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
