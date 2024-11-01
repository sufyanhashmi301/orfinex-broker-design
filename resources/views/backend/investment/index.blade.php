@extends('backend.layouts.app')
@section('title')
    {{ __('Accounts') }}
@endsection
@section('style')
    <style>
        .data-card {
            flex-direction: column !important;
        }
    </style>
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('All :type Accounts',['type'=>ucfirst($type)]) }}
        </h4>
    </div>
    <div class="innerMenu card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3 gap-x-2">
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">Total Accounts</p>
                        <h4 class="count text-2xl font-bold mb-0">{{$data['TotalAccounts']}}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">With Balance</p>
                        <h4 class="count text-2xl font-bold mb-0">{{$data['withBalance']}}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">With Bonus</p>
                        <h4 class="count text-2xl font-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">Without Balance</p>
                        <h4 class="count text-2xl font-bold mb-0">{{$data['withoutBalance']}}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">Without Bonus</p>
                        <h4 class="count text-2xl font-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-check"><path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/></svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">Inactive Accounts</p>
                        <h4 class="count text-2xl font-bold mb-0">{{$data['unActiveAccounts']}}</h4>
                    </div>
                </div>
            </div>
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
                                    <th scope="col" class="table-th">{{ __('Account Number') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Currency') }}</th>
                                    <th scope="col" class="table-th">{{ __('Leverage') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Agent/IB Number') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Created At') }}</th>
                                    <th scope="col" class="table-th">{{ __('Actions') }}</th>
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

    {{--Modal for active trades--}}
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="openTradesModal" tabindex="-1" aria-labelledby="openTradesModal" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative modal-xl relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="flex items-start justify-between gap-3 p-5">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                            {{ __('Positions / Active Trades') }}
                        </h3>
                        <p class="text-slate-600 dark:text-slate-200">
                            {{ __('Here are the current positions / active trades for Account Number ') }}
                            <span id="account-number">876960</span>
                        </p>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="modal-body px-6" id="dealsModalBody">

                </div>
            </div>
        </div>
    </div>

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
                ajax: "{{ route('admin.forex-accounts',['type'=>'real']) }}",
                columns: [
                    {data: 'login', name: 'login'},
                    {data: 'username', name: 'username'},
                    {data: 'schema', name: 'schema'},
                    // {data: 'login', name: 'login'},
                    {data: 'group', name: 'group'},
                    {data: 'currency', name: 'currency'},
                    {data: 'leverage', name: 'leverage'},
                    {data: 'balance', name: 'balance'},
                    {data: 'ib_number', name: 'ib_number'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'},
                ]
            });

            $('body').on('click', '.open-trades-modal', function(event) {
                event.preventDefault();

                // Get the account login ID
                var login = $(this).data('login');
                $('#account-number').text(login);

                var url = '{{ route("admin.getDeals", ":login") }}';
                url = url.replace(':login', login);

                // Fetch deals using AJAX
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {

                        $('#dealsModalBody').html(response);
                        $('#openTradesModal').modal('show');

                    },
                    error: function() {
                        alert('Failed to fetch data');
                    }
                });
            });

            // Handler for pagination links inside the modal
            $('body').on('click', '#dealsModalBody nav a', function(event) {
                event.preventDefault();

                var url = $(this).attr('href'); // Get the href attribute from the pagination link

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#dealsModalBody').html(response); // Update the modal content
                    },
                    error: function() {
                        alert('Failed to fetch data');
                    }
                });
            });

        })(jQuery);
    </script>
@endsection
