@extends('backend.layouts.app')
@section('title')
    {{ __('Pending KYC') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Pending KYC') }}
        </h4>
    </div>
    @include('backend.kyc.include.__menu')
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="pending-kyc-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
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
    <!-- Modal for Pending KYC Details -->
    @can('kyc-action')
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="kyc-action-modal"
        tabindex="-1"
        aria-labelledby="kyc-action-modal"
        aria-hidden="true"
    >
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body p-6 py-8 text-center space-y-5" id="kyc-action-data">

                </div>
            </div>
        </div>
    </div>
    @endcan
    <!-- Modal for Pending KYC Details -->
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#pending-kyc-dataTable')
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
                ajax: "{{ route('admin.kyc.pending') }}",
                columns: [
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'user', name: 'user',orderable : false},
                    {data: 'type', name: 'type',orderable : false},
                    {data: 'status', name: 'status',orderable : false},
                    {data: 'action', name: 'action',orderable : false},
                ]
            });
        })(jQuery);

        $('body').on('click', '#action-kyc', function (e) {
            "use strict";
            e.preventDefault()
            $('#kyc-action-data').empty();

            var id = $(this).data('id');
            var url = '{{ route("admin.kyc.action",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {

                $('#kyc-action-data').append(data)
                imagePreview()
            })

            $('#kyc-action-modal').modal('toggle')
        })
    </script>
@endsection
