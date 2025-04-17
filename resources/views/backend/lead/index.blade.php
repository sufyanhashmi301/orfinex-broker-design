@extends('backend.layouts.app')
@section('title')
    {{ __('Leads') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.lead.create') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add New Lead') }}
            </a>
            <button class="btn btn-sm btn-white inline-flex items-center" data-bs-toggle="modal" data-bs-target="#importModal">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:download"></iconify-icon>
                {{ __('Import') }}
            </button>
        </div>
    </div>

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
                                <th scope="col" class="table-th">{{ __('Contact Name') }}</th>
                                <th scope="col" class="table-th">{{ __('Email') }}</th>
                                <th scope="col" class="table-th">{{ __('Lead Owner') }}</th>
                                <th scope="col" class="table-th">{{ __('Created At') }}</th>
                                <th scope="col" class="table-th"></th>
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

    {{-- Modal for lead delete--}}
    @include('backend.lead.modal.__delete')

    {{-- Modal for import leads--}}
    @include('backend.lead.modal.__import')

@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#dataTable')
                .on('processing.dt', function (e, settings, processing) {
                    $('#processingIndicator').css('display', processing ? 'block' : 'none');
                }).DataTable({
                    dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
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
                        search: "Search:"
                    },
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('admin.lead.index') }}",
                    },
                    columns: [
                        {data: 'username', name: 'username'},
                        {data: 'client_email', name: 'client_email'},
                        {data: 'owner', name: 'owner'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
        })(jQuery);

        $('body').on('click', '.loseLeadBtn', function (event) {
            var leadId = $(this).data('lead-id');
            var stageId = 7;

            fetch('{{ route('admin.lead.stageUpdate', ':lead') }}'.replace(':lead', leadId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    stage_id: stageId,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    tNotify('success', data.message);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        $('body').on('click', '.deleteLeadBtn', function (event) {
            "use strict";
            event.preventDefault();
            var id = $(this).data('id');

            var url = '{{ route("admin.lead.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#leadDeleteForm').attr('action', url)

            $('#deleteLead').modal('show');
        });

    </script>
@endsection
