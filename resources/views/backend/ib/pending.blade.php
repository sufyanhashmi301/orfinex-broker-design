@extends('backend.layouts.app')
@section('title')
    {{ __('Pending IB Members') }}
@endsection
@php
    $riskProfileTags = getRiskProfileTag();
@endphp
@section('filters')
    <form id="filter-form" method="POST" action="{{ route('admin.user.export',['type' => 'all']) }}">
        @csrf
        <div class="flex justify-between flex-wrap items-center">
            <div class="flex-1 inline-flex sm:space-x-3 space-x-2 ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full" placeholder="Search by Name, Username, Email">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="phone" id="phone" class="form-control h-full" placeholder="Phone">
                </div>
                <div class="flex-1 input-area relative">
                    <select name="country" id="country" class="select2 form-control h-full w-full" data-placeholder="{{ __('Select a country') }}">
                        <option value="" selected>
                            {{ __('country') }}
                        </option>
                        @foreach( getCountries() as $country)
                            <option value="{{ $country['name'] }}">
                                {{ $country['name']  }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 input-area relative">
                    <input type="date" name="created_at" id="created_at" class="form-control h-full" placeholder="Created At">
                </div>
                <div class="flex-1 input-area relative">
                    <select name="tag" id="tag" class="select2 form-control w-full h-full" data-placeholder="{{ __('Select a tag') }}">
                        <option value="" selected>
                            {{ __('tags') }}
                        </option>
                        @foreach($riskProfileTags as $tag)
                            <option value="{{ $tag->name }}">
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <div class="input-area relative">
                    <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                        <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
    </form>
    
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Pending IB Members') }}
        </h4>
    </div>

    @include('backend.ib.include.__menu')

    <div class="card">
        <div class="card-body relative px-6 pt-3">
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
{{--                                    <th scope="col" class="table-th">{{ __('KYC') }}</th>--}}
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

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

            var table = $('#dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                processing: true,
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
                    search: "Search:",
                    processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
                },
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.ib.pending.list') }}",
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
                    // {data: 'kyc', name: 'kyc'},
                    {data: 'ib_status', name: 'ib_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#country').select2({
                placeholder: $('#country').data('placeholder'), // Retrieve the placeholder text from the data attribute

            });
            $('#tag').select2({
                placeholder: $('#tag').data('placeholder'), // Retrieve the placeholder text from the data attribute

            });
            $('#filter').click(function () {
                table.draw();
            });

            $('#global_search').keyup(function() {
                table.draw();
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

            $(document).ready(function() {
            $('.filter-toggle-btn').click(function() {
                const $content = $('#filters_div');

                if ($content.hasClass('hidden')) {
                    $content.removeClass('hidden').slideDown();
                } else {
                    $content.slideUp(function() {
                        $content.addClass('hidden');
                    });
                }
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
