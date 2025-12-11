<div class="tab-pane space-y-5 fade" id="pills-transfer" role="tabpanel" aria-labelledby="pills-transfer-tab">
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h4 class="card-title text-lg font-semibold">{{ __('Account') }}</h4>
            <div class="flex space-x-2">
            @can('customer-account-create')

            
                <a href="javascript:;" class="btn btn-dark btn-sm flex items-center gap-1 px-3 py-2 rounded-md shadow-sm"
                    type="button" data-bs-toggle="modal" data-bs-target="#addForexAccount">
                    <i class="fa fa-plus"></i> {{ __('Add New Account') }}
                </a>
                @endcan

            @can('customer-account-mapping')
                <a href="javascript:;" class="btn btn-dark btn-sm flex items-center gap-1 px-3 py-2 rounded-md shadow-sm"
                    type="button" data-bs-toggle="modal" data-bs-target="#addmt5Account">
                    <i class="fa fa-user-plus"></i> {{ __('Account Mapping') }}
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="user-forex-account-dataTable">
                            <thead>
                                <tr>
                                    {{--<th scope="col" class="table-th">{{ __('Icon') }}</th>--}}
                                    <th scope="col" class="table-th">{{ __('Schema') }}</th>
                                    <th scope="col" class="table-th">{{ __('Login') }}</th>
                                    <th scope="col" class="table-th">{{ __('Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Credit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="processingIndicator processingIndicator-accounts text-center">
                {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>
</div>

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

<!-- Modal for Account details -->
@include('backend.investment.modal.__account_details')

<!-- Modal for Change Account Type -->
@include('backend.investment.modal.__change_type')

<!-- Modal for Change Account schema -->
@include('backend.investment.modal.__change_schema')

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

@push('single-script')
    @include('backend.investment.fx-js')
    <script>
        (function ($) {
            "use strict";
            var table = $('#user-forex-account-dataTable')
                .on('processing.dt', function(e, settings, processing) {
                    $('.processingIndicator-accounts').css('display', processing ? 'block' : 'none');
                }).DataTable({
                    dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                    searching: false,
                    lengthChange: false,
                    info: true,
                    order: [[0, 'desc']],
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
                    ajax: "{{ route('admin.forex-accounts',['type'=>'real',$user->id]) }}",
                    columns: [
                        // {data: 'icon', name: 'icon'},
                        {data: 'schema', name: 'schema'},
                        {data: 'login', name: 'login'},
                        {data: 'group', name: 'group'},
                        {data: 'balance', name: 'balance'},
                        {data: 'equity', name: 'equity'},
                        {data: 'credit', name: 'credit'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
            });
        })(jQuery);

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

        // Send Statement Email Function
        window.sendStatement = function(login) {

            if (!login) {
                tNotify('error', "Account login is required");
                return;
            }

            // Select the button
            let $btn = $('.send-statement-btn[data-login="' + login + '"]');

            // Disable to prevent double clicks
            $btn.prop('disabled', true)
                .addClass('opacity-50 cursor-not-allowed');

            // Notify user
            tNotify('info', "Sending statement email...");

            $.ajax({
                url: '{{ route("admin.forex.send-statement") }}',
                method: 'POST',
                dataType: 'json',
                timeout: 30000,
                data: {
                    login: login,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {

                    if (response.success) {
                        tNotify('success', response.message ?? "Statement email sent successfully");
                    } else {
                        tNotify('error', response.message ?? "Failed to send statement email");
                    }

                },
                error: function(xhr, status) {

                    let errMsg = "Failed to send statement email";

                    if (status === 'timeout') {
                        errMsg = "Request timed out. Please try again.";
                    } else if (xhr.responseJSON) {
                        errMsg = xhr.responseJSON.error ?? 
                                xhr.responseJSON.message ?? 
                                errMsg;
                    }

                    tNotify('error', errMsg);
                },
                complete: function() {
                    // Re-enable button
                    $btn.prop('disabled', false)
                        .removeClass('opacity-50 cursor-not-allowed');
                }
            });
        };

    </script>
@endpush
