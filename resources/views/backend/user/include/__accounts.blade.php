<div class="tab-pane space-y-5 fade" id="pills-transfer" role="tabpanel" aria-labelledby="pills-transfer-tab">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Account') }}</h4>
            <a href="javascript:;" class="btn btn-dark btn-sm inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addForexAccount">
                {{ __('Add New') }}
            </a>
        </div>
        <div class="card-body px-6 pt-3">
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

@include('backend.investment.include.reset_credit')
@push('single-script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#user-forex-account-dataTable').DataTable();
            table.destroy();
            var table = $('#user-forex-account-dataTable').DataTable({
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
                ajax: "{{ route('admin.forex-accounts',['type'=>'real',$user->id]) }}",
                columns: [
                    // {data: 'icon', name: 'icon'},
                    {data: 'schema', name: 'schema'},
                    {data: 'login', name: 'login'},
                    {data: 'group', name: 'group'},
                    {data: 'balance', name: 'balance'},
                    {data: 'equity', name: 'equity'},
                    {data: 'credit', name: 'credit'},
                    {data: 'action', name: 'action'},
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


        // Open confirmation modal on reset button click
        $('body').on('click', '.reset-data-btn', function () {
            userId = $(this).data('id'); // Get user ID from button
             $('#reset_credit_login').text(userId); // Get user ID from button
            $('#resetConfirmationModal').modal('show'); // Show the modal
        });

        // Handle confirmation button click
        $('#confirmResetBtn').click(function () {
            if (userId) {
                const apiUrl = `{{ route('admin.reset.credit', ':id') }}`.replace(':id', userId); // API endpoint

                // Make AJAX request to reset data
                $.ajax({
                    url: apiUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Include CSRF token
                    },
                    success: function (response) {
                        $('#resetConfirmationModal').modal('hide'); // Hide the modal
{{--                        alert('Data has been successfully reset.');--}}
                            tNotify('success', 'Data has been successfully reset');
                       location.reload(); // Optionally reload the page
                   },
                   error: function (xhr) {
                       $('#resetConfirmationModal').modal('hide');
                       tNotify('warning', 'Failed to reset data. Please try again');


                   }
               });
           }
       });
</script>
@endpush
