@extends('backend.user.index')
@section('title')
    {{ __('All Customers') }}
@endsection
@php
    $riskProfileTags = getRiskProfileTag();
@endphp
@section('filters')
    <form id="filter-form" method="POST" action="{{ route('admin.user.export') }}">
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
                    <select name="country" id="country" class="select2 form-control h-full w-full">
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
                    <select name="tag" id="tag" class="select2 form-control w-full h-full">
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
@section('customers-content')
    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="dataTable">
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

    @include('backend.user.include.__configure_modal')
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
                processing: true,
                info: false,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                        next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                    },
                    search: "Search:",
                    processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
                },
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
                    {"class": "table-td", data: 'equity', name: 'equity'},
                    {"class": "table-td", data: 'credit', name: 'credit'},
                    {"class": "table-td", data: 'country', name: 'country'},
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
