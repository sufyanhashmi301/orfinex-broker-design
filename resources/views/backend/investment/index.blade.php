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
        <h4
            class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('All :type Accounts', ['type' => ucfirst($type)]) }}
        </h4>
    </div>
    <div class="innerMenu card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3 gap-x-2">
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div
                        class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-credit-card">
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <line x1="2" x2="22" y1="10" y2="10" />
                        </svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">Total Accounts</p>
                        <h4 class="count text-2xl font-bold mb-0">{{ $data['TotalAccounts'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div
                        class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-credit-card">
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <line x1="2" x2="22" y1="10" y2="10" />
                        </svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">With Balance</p>
                        <h4 class="count text-2xl font-bold mb-0">{{ $data['withBalance'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div
                        class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-credit-card">
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <line x1="2" x2="22" y1="10" y2="10" />
                        </svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">With Bonus</p>
                        <h4 class="count text-2xl font-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div
                        class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-credit-card">
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <line x1="2" x2="22" y1="10" y2="10" />
                        </svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">Without Balance</p>
                        <h4 class="count text-2xl font-bold mb-0">{{ $data['withoutBalance'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="position-relative bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div
                        class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-credit-card">
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <line x1="2" x2="22" y1="10" y2="10" />
                        </svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">Without Bonus</p>
                        <h4 class="count text-2xl font-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-body rounded p-4">
                <div class="flex flex-col text-center">
                    <div
                        class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-check-check">
                            <path d="M18 6 7 17l-5-5" />
                            <path d="m22 10-7.5 7.5L13 16" />
                        </svg>
                    </div>
                    <div class="content">
                        <p class="text-sm dark:text-white my-2">Inactive Accounts</p>
                        <h4 class="count text-2xl font-bold mb-0">{{ $data['unActiveAccounts'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-4 mb-5">
        <form id="filter-form" method="POST"
            action="{{ route('admin.forex-accounts.export', ['type' => $type === 'real' ? 'real' : 'all']) }}">
            @csrf
            <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
                <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                    <div class="flex-1 input-area relative">
                        <input type="text" name="global_search" id="global_search" class="form-control h-full"
                            placeholder="Search by Name, Email">
                    </div>
                    <div class="flex-1 input-area relative">
                        <input type="text" name="login" id="login" class="form-control h-full"
                            placeholder="Account Number">
                    </div>
                    <div class="flex-1 input-area relative">
                        <select name="status" class="form-control h-full" id="status">
                            <option value="">Status</option>
                            <option value="ongoing">active</option>
                            <option value="archive">archive</option>
                        </select>
                    </div>
                    <div class="flex-1 input-area relative">
                        <input type="date" name="created_at" id="created_at" class="form-control h-full"
                            placeholder="Created At">
                    </div>
                </div>
                <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                    <div class="input-area relative">
                        <button type="button" id="filter"
                            class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                icon="lucide:filter"></iconify-icon>
                            {{ __('Filter') }}
                        </button>
                    </div>
                    @can('accounts-export')
                        <div class="input-area relative">
                            <button type="export"
                                class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                    icon="lets-icons:export-fill"></iconify-icon>
                                {{ __('Export') }}
                            </button>
                        </div>
                    @endcan
                    <div class="input-area relative">
                        <button type="button"
                            class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white"
                            data-bs-toggle="modal" data-bs-target="#configureModal">
                            <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                            id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Account Number') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Group') }}</th>
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

    {{-- Modal for active trades --}}
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="openTradesModal" tabindex="-1" aria-labelledby="openTradesModal" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative modal-xl relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
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
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                        data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="modal-body px-6" id="dealsModalBody">

                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Account details -->
    @include('backend.investment.modal.__change_schema')
    @include('backend.investment.modal.__change_type')
    <!-- Modal for Account details -->
    @include('backend.investment.modal.__account_details')

    <!-- Modal for Account leverage -->
    @include('backend.investment.modal.__change_leverage')

    <!-- Modal for Demo deposit -->
    @include('backend.investment.modal.__deposit_demo_account')

    <!-- Modal for Account rename -->
    @include('backend.investment.modal.__account_rename')

    <!-- Modal for Account password -->
    @include('backend.investment.modal.__change_account_password')

    <!-- Modal for Account invest password -->
    @include('backend.investment.modal.__change_investor_password')

    <!-- Modal for Account archive -->
    @include('backend.investment.modal.__archive_account')

    <!-- Modal for Account unarchive -->
    @include('backend.investment.modal.__unarchive_account')
@endsection

@section('script')
    @include('backend.investment.fx-js')
    <script>
        (function($) {
            "use strict";
            var table = $('#dataTable')
                .on('processing.dt', function(e, settings, processing) {
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
                        url: "{{ route('admin.forex-accounts', ['type' => $type]) }}", // Dynamic type
                        data: function(d) {
                            d.global_search = $('#global_search').val();
                            d.login = $('#login').val();
                            d.status = $('#status').val();
                            d.created_at = $('#created_at').val();
                            d.tag = $('#tag').val();
                        }
                    },
                    columns: [{
                            data: 'login',
                            name: 'login'
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'schema',
                            name: 'schema'
                        },
                        {
                            data: 'group',
                            name: 'group'
                        },
                        {
                            data: 'leverage',
                            name: 'leverage'
                        },
                        {
                            data: 'balance',
                            name: 'balance'
                        },
                        {
                            data: 'ib_number',
                            name: 'ib_number'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ]
                });

            $('#country').select2({
                placeholder: $('#country').data(
                    'placeholder'), // Retrieve the placeholder text from the data attribute

            });
            $('#tag').select2({
                placeholder: $('#tag').data(
                    'placeholder'), // Retrieve the placeholder text from the data attribute

            });
            $('#filter').click(function() {
                table.draw();
            });
            $('#filter-form').on('keypress', function(e) {
                if (e.which === 13) { // 13 is the Enter key code
                    e.preventDefault(); // Prevent form submission
                    table.draw(); // Trigger filtering only
                    return false;
                }
            });
            $('#global_search').keyup(function() {
                table.draw();
            });

            $('body').on('click', '.open-trades-modal', function(event) {
                event.preventDefault();

                // Get the account login ID
                var login = $(this).data('login');
                $('#account-number').text(login);

                var url = '{{ route('admin.getDeals', ':login') }}';
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
    </script>
@endsection
