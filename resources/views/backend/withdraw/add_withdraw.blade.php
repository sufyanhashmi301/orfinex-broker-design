@extends('backend.withdraw.index')
@section('title')
    {{ __('Add Withdraw') }}
@endsection
@section('page-title')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('withdraw_content')
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-5 col-span-12">
            <div class="card h-full">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Add Withdraw') }}</h4>
                </div>
                <div class="card-body p-6 pt-3">
                    <form action="" method="post">
                        <div class="grid grid-cols-12 items-center gap-5">
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Transaction By') }}</label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="customer wallet">{{ __('Customer Wallet') }}</option>
                                    <option value="trading account">{{ __('Trading Account') }}</option>
                                </select>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Account / Wallet') }}</label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="customer wallet">{{ __('Customer Wallet') }}</option>
                                    <option value="trading account">{{ __('Trading Account') }}</option>
                                </select>
                            </div>
                            <div class="input-area col-span-12">
                                <label for="" class="form-label">{{ __('Payment Method') }}</label>
                                <select name="gateway_code" class="select2 form-control">
                                    <option selected="">--Select Gateway--</option>
                                    <option value="perfectmoney-usd">Perfect Money</option>
                                    <option value="BANKPK">Bank Transfer - PKR</option>
                                    <option value="USDT-TRC20">USDT</option>
                                    <option value="BANKAED">Bank Transfer - AED</option>
                                </select>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Base Currency') }}</label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="usd">{{ __('USD') }}</option>
                                    <option value="eur">{{ __('EUR') }}</option>
                                    <option value="btc">{{ __('BTC') }}</option>
                                    <option value="bnb">{{ __('BNB') }}</option>
                                </select>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Account Currency') }}</label>
                                <input type="text" name="" class="form-control w-full" placeholder="PKR" readonly>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label">{{ __('Amount') }}</label>
                                <input type="text" name="" class="form-control" placeholder="1000">
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <label for="" class="form-label opacity-0">{{ __('Auto Approve') }}</label>
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto !mb-0">
                                        {{ __('Auto Approve') }}
                                    </label>
                                    <div class="form-switch" style="line-height: 0;">
                                        <input class="form-check-input" type="hidden" value="0" name=""/>
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="" value="1" class="sr-only peer" >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-area col-span-12">
                                <label for="" class="form-label">{{ __('Comments') }}</label>
                                <textarea class="form-control summernote" rows="5"></textarea>
                                <input type="hidden" name="comments">
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Proceed to Payment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="lg:col-span-7 col-span-12">
            <div class="card h-full">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Recent Transactions') }}</h4>
                </div>
                <div class="card-body relative px-6 pt-3">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <span class=" col-span-8  hidden"></span>
                        <span class="  col-span-4 hidden"></span>
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('Date') }}</th>
                                            <th scope="col" class="table-th">{{ __('User') }}</th>
                                            <th scope="col" class="table-th">{{ __('Transaction ID') }}</th>
                                            <th scope="col" class="table-th">{{ __('Account') }}</th>
                                            <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                            <th scope="col" class="table-th">{{ __('Charge') }}</th>
                                            <th scope="col" class="table-th">{{ __('Gateway') }}</th>
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
        </div>
    </div>
    @can('transaction-action')
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="transaction-action-modal" tabindex="-1" aria-labelledby="deposit-action-modal" aria-hidden="true">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                    <div class="modal-body popup-body">
                        <div class="popup-body-text deposit-action">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    <script !src="">
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
                        url: "{{ route('admin.withdraw.history') }}",
                        data: function (d) {
                            d.email = $('#email').val();
                            d.status = $('#status').val();
                            d.status = $('#status').val();
                            d.created_at = $('#created_at').val();

                        }
                    },

                    columns: [
                        {data: 'created_at', name: 'created_at'},
                        {data: 'username', name: 'username'},
                        {data: 'tnx', name: 'tnx'},
                        {data: 'target_id', name: 'target_id'},
                        {data: 'amount', name: 'amount'},
                        {data: 'charge', name: 'charge'},
                        {data: 'method', name: 'method'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action'},
                    ]
                });
            $('#filter').click(function () {
                table.draw();
            });
            $('body').on('click', '#deposit-action', function () {
                $('.deposit-action').empty();

                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route("admin.transactions.view", ":id") }}'.replace(':id', id),
                    method: 'GET',
                    success: function(response) {
                        $('.deposit-action').append(response)
                        imagePreview()
                        $('#transaction-action-modal').modal('show');

                    }
                });
            });
        })(jQuery);


    </script>
@endsection
