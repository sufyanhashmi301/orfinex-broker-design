@extends('backend.deposit.index')
@section('title')
    {{ __('Add Deposit') }}
@endsection
@section('page-title')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('filters')
    <form id="filter-form" method="POST" action="{{ route('admin.deposit.export') }}">
        @csrf
        <div class="flex justify-between flex-wrap items-center">
            <div class="flex-1 inline-flex sm:space-x-3 space-x-2 ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                <div class="flex-1 input-area relative">
                    <input type="text" name="email" id="email" class="form-control h-full" placeholder="Search User By Email">
                </div>
                <div class="flex-1 input-area relative">
                    <select name="status" class="form-control h-full" id="status">
                        <option value="">Status</option>
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Cancelled</option>
                    </select>
                </div>

                <div class="flex-1 input-area relative">
                    <input type="date" name="created_at" id="created_at" class="form-control h-full flatpickr flatpickr-input active" data-mode="range" placeholder="Created At">
                </div>

            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <div class="input-area relative">
                    <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Apply Filter') }}
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
@section('deposit_content')
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-5 col-span-12">
            <div class="card h-full">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Add Deposit') }}</h4>
                </div>
                <div class="card-body p-6 pt-3">
                    <form action="{{route('admin.deposit.now')}}" method="post" >
                        @csrf
                        <div class="grid grid-cols-12 items-center gap-5">
                            <div class="input-area col-span-12">
                                <label for="" class="form-label">{{ __('User') }}</label>
                                <select name="user_id" class="select2 form-control w-full">
                                    <option selected="">{{__('Select User')}}</option>
                                    @foreach($users as $user)
                                        <option value="{{the_hash($user->id) }}"  class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $user->full_name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area col-span-12">
                                <label for="" class="form-label">{{ __('Account / Wallet') }}</label>
                                <select name="target_id" class="select2 form-control w-full">
                                      </select>
                            </div>
                            <div class="input-area col-span-12">
                                <label for="" class="form-label">{{ __('Payment Method') }}</label>
                                <select name="gateway_code" id="gatewaySelect" class="select2 form-control !text-lg w-full mt-2 py-2">
                                    <option selected class="inline-block font-Inter font-normal text-sm text-slate-600" disabled>--{{ __('Select Gateway') }}--</option>
                                    @foreach($gateways as $gateway)
                                        <option value="{{ $gateway->gateway_code }}" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $gateway->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area col-span-12">
                                <label for="" class="form-label">{{ __('Enter Amount:') }}</label>
                                <div class="relative">
                                    <input type="text" name="amount" class="form-control"
                                           oninput="this.value = validateDouble(this.value)" aria-label="Amount" id="amount"
                                           aria-describedby="basic-addon1">
                                    <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center" id="basic-addon1">{{ $currency }}</span>
                                </div>
                                <div class="font-Inter text-xs text-danger pt-2 inline-block min-max"></div>
                            </div>
                            <div class="input-area col-span-12 conversion hidden">
                                <label for="" class="form-label">{{ __('Enter Amount:') }}</label>
                                <div class="relative">
                                    <input type="text"  class="form-control"
                                           oninput="this.value = validateDouble(this.value)" aria-label="Amount" id="converted-amount"
                                           aria-describedby="basic-addon2">
                                    <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center" id="basic-addon2">{{ $currency }}</span>
                                </div>
                                <div class="font-Inter text-xs text-danger pt-2 inline-block conversion-rate"></div>
                            </div>
{{--                            <div class="manual-row"></div>--}}
                            <div class="input-area col-span-12">
                                <label for="approval_cause" class="form-label">{{ __('Comments') }}</label>
                                <textarea name="approval_cause" class="form-control basicTinymce" rows="5"></textarea>
                            </div>
                            <div class="input-area lg:col-span-6 col-span-12">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto !mb-0">
                                        {{ __('Auto Approve') }}
                                    </label>
                                    <div class="form-switch" style="line-height: 0;">
                                        <input class="form-check-input" type="hidden" value="0" name="is_auto_approve"/>
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_auto_approve" value="1" class="sr-only peer" >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
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
                                            <th scope="col" class="table-th">{{ __('Type') }}</th>
                                            <th scope="col" class="table-th">{{ __('Account') }}</th>
                                            <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                            <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                            <th scope="col" class="table-th">{{ __('Status') }}</th>
                                            <th scope="col" class="table-th">{{ __('Action') }}</th></tr>
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
                        <div class="popup-body-text deposit-action p-6">

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
                        url: "{{ route('admin.transactions') }}",
                        data: function (d) {
                            d.email = $('#email').val();
                            d.status = $('#status').val();
                            d.type = $('#type').val();
                            d.status = $('#status').val();
                            d.created_at = $('#created_at').val();

                        }
                    },
                    columns: [
                        {data: 'created_at', name: 'created_at'},
                        {data: 'username', name: 'username'},
                        {data: 'tnx', name: 'tnx'},
                        {data: 'type', name: 'type'},
                        {data: 'target_id', name: 'target_id'},
                        {data: 'final_amount', name: 'final_amount'},
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

        // $(document).ready(function() {
        let assetPath = '{{ asset('') }}/';
            var globalData;
            var currency = @json($currency)


            // When the user dropdown changes
            $('select[name="user_id"]').on('change', function() {
                var userId = $(this).val();
                $('select[name="target_id"]').empty();
                if (userId) {
                    // Use Laravel's route helper to generate the URL
                    var url = '{{ route("admin.deposit.get.user.accounts", ":userId") }}';
                    url = url.replace(':userId', userId);

                    // Make an AJAX call to fetch the user's forex accounts and wallets
                    $.ajax({
                        url: url, // URL generated from the route helper
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            // Clear the current options in Account / Wallet dropdown (now target_id)
                            $('select[name="target_id"]').empty();

                            // Populate forex accounts
                            $.each(data.forexAccounts, function(key, account) {
                                $('select[name="target_id"]').append('<option value="'+ account.login +'" data-type="forex">'+ account.login +' - '+ account.account_name + ' ('+ account.equity +' USD)</option>');
                            });

                            // Populate wallets
                            $.each(data.wallets, function(key, wallet) {
                                $('select[name="target_id"]').append('<option value="'+ wallet.wallet_id +'" data-type="wallet">'+ wallet.wallet_name +' ('+ wallet.amount +' USD)</option>');
                            });

                        }
                    });
                } else {
                    // If no user selected, clear the Account / Wallet dropdown (now target_id)
                    $('select[name="target_id"]').empty();
                }
            });

            $("#gatewaySelect").on('change', function (e) {
                "use strict"
                e.preventDefault();
                $('.manual-row').empty();
                var code = $(this).val()
                var url = '{{ route("admin.deposit.gateway",":code") }}';
                url = url.replace(':code', code);
                $.get(url, function (data) {

                    globalData = data;
                    console.log(data,'data')
                    if (data.currency === currency){
                        $('.conversion').addClass('hidden');
                    }else {
                        $('.conversion').removeClass('hidden');
                        $('#basic-addon2').text(globalData.currency);
                        $('#amount').trigger('keyup')
                    }

                    $('.charge').text('Charge ' + data.charge + ' ' + (data.charge_type === 'percentage' ? ' % ' : currency))
                    $('.conversion-rate').text('1' +' '+ currency + ' = ' + data.rate +' '+ data.currency)


                    $('.min-max').text('Minimum ' + data.minimum_deposit + ' ' + currency + ' and ' + 'Maximum ' + data.maximum_deposit + ' ' + currency)
                    $('#logo').html(`<img class="payment-method h-12" src='${assetPath + data.logo}'>`);
                    var amount = $('#amount').val()

                    if (Number(amount) > 0) {
                        $('.amount').text((Number(amount)))
                        var charge = data.charge_type === 'percentage' ? calPercentage(amount, data.charge) : data.charge
                        $('.charge2').text(charge + ' ' + currency)
                        $('.total').text((Number(amount) - Number(charge)) + ' ' + currency)
                    }

                    if (data.credentials !== undefined) {
                        console.log(data.credentials,'data.credentials')
                        $('.manual-row').append(data.credentials)
                        imagePreview()
                    }

                });

                $('#amount').on('keyup', function (e) {
                    "use strict"
                    var amount = $(this).val()
                    $('.amount').text((Number(amount)))
                    $('.currency').text(currency)

                    var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge
                    $('.charge2').text(charge + ' ' + currency)

                    var total = Number(amount) ;

                    $('.total').text(total + ' ' + currency)

                    $('.pay-amount').text(parseFloat((total * globalData.rate).toFixed(4)).toString() +' '+ globalData.currency)

                    $('#converted-amount').val(parseFloat((total * globalData.rate).toFixed(4)).toString())
                })
                $('#converted-amount').on('keyup', function (e) {
                    "use strict"
                    var converted_amount = $(this).val();
                    var amount = parseFloat((converted_amount / globalData.rate).toFixed(4)).toString();
                    $('#amount').val(amount);
                    $('.amount').text((Number(amount)))
                    $('.currency').text(currency)

                    var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge
                    $('.charge2').text(charge + ' ' + currency)

                    var total = Number(amount);

                    $('.total').text(total + ' ' + currency)

                    $('.pay-amount').text(parseFloat((total * globalData.rate +' '+ globalData.currency).toFixed(4)).toString());


                })

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
            // });

    </script>
@endsection
