@extends('backend.setting.communication.index')
@section('title')
    {{ __('Email Template') }}
@endsection
@section('communication-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Email Template') }}
        </h4>
        @can('email-setting')
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.settings.mail') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:mail"></iconify-icon>
                {{ __('Email Config') }}
            </a>
        </div>
        @endcan
    </div>
    @include('backend.email.include.__menu')
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
                                    <th scope="col" class="table-th">{{ __('Email For') }}</th>
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
@endsection

@section('communication-script')

    <script>
        (function ($) {
            "use strict";

            var savedPage = sessionStorage.getItem('emailTemplatePage') || 0;

            var table = $('#dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                searching: false,
                displayStart: savedPage * 10,
                lengthChange: false,
                info: true,
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
                order: [[0, 'asc']], // Default: Sort by name ascending
                ajax: "{{ route('admin.email-template.user') }}",
                columns: [
                    {data: 'name', name: 'name', orderable: true},
                    {data: 'status', name: 'status', orderable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Clear session value after restoring
            sessionStorage.removeItem('emailTemplatePage');

        })(jQuery);

        $(document).on('click', '.action-btn', function (e) {
            e.preventDefault();

            var table = $('#dataTable').DataTable();
            var currentPage = table.page.info().page;

            // Save current page to sessionStorage
            sessionStorage.setItem('emailTemplatePage', currentPage);

            // Proceed to the edit page
            window.location.href = $(this).attr('href');
        });
    </script>
@endsection
