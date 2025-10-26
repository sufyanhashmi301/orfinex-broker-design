@extends('backend.withdraw.index')
@section('title')
    {{ __('Add Withdraw') }}
@endsection
@section('page-title')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4
            class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
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
                    <form action="{{ route('admin.withdraw.now') }}" method="post" id="withdrawForm">
                        @csrf
                        <input type="hidden" name="account_type" id="account_type" value="{{ old('account_type') }}">
                        <div class="space-y-5">
                            <div class="input-area">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The user who is withdrawing the funds">
                                        {{ __('User') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="user_id" class="select2 form-control w-full" data-placeholder="Select User"
                                    required>
                                    <option value="">{{ __('Select User') }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ the_hash($user->id) }}"
                                            class="inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->full_name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The account or wallet from which the funds will be withdrawn">
                                        {{ __('Account / Wallet') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="target_id" id="tradingAccount" class="select2 form-control w-full"
                                    data-placeholder="Select Account" required>
                                    <option value="">{{ __('Select Account') }}</option>
                                </select>

                                @error('target_id')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">
                                    <span class="flex justify-between">
                                        <span class="shift-Away inline-flex items-center gap-1"
                                            data-tippy-content="The account or wallet from which the funds will be withdrawn">
                                            {{ __('Withdraw Account') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                                class="text-[16px]"></iconify-icon>
                                        </span>
                                        <a href="javascript:;"
                                            class="inline-flex items-center btn-link withdraw_account_btn hidden">
                                            <iconify-icon icon="lucide:plus"
                                                class="text-base ltr:mr-1 rtl:ml-1 font-light"></iconify-icon>
                                            {{ __('Add New') }}
                                        </a>
                                    </span>
                                </label>
                                <select name="withdraw_account" id="withdrawAccountId" class="select2 form-control w-full"
                                    data-placeholder="Select Account" required></select>
                                @error('withdraw_account')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The amount of funds to be withdrawn">
                                        {{ __('Amount') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="amount" id="amount"
                                        oninput="this.value = validateDouble(this.value)"
                                        class="form-control !pr-12 withdrawAmount" placeholder="{{ __('Enter Amount') }}"
                                        value="{{ old('amount') }}" aria-describedby="basic-addon1" required>
                                    <span
                                        class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center"
                                        id="basic-addon1">
                                        {{ $currency }}
                                    </span>
                                </div>
                                <div class="error !text-xs withdrawAmountRange"></div>
                                @error('amount')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area conversion hidden">
                                <label for="exampleFormControlInput1" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The amount of funds to be withdrawn">
                                        {{ __('Amount') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" oninput="this.value = validateDouble(this.value)"
                                        class="form-control" id="converted-amount" placeholder="{{ __('Enter Amount') }}"
                                        aria-describedby="basic-addon2">
                                    <span
                                        class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center"
                                        id="basic-addon2">
                                        {{ $currency }}
                                    </span>
                                </div>
                                <div class="error !text-xs conversion-rate"></div>
                            </div>
                            <div class="withdrawDetailsTable hidden col-span-12 -mx-3">
                                <table class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                                    <tbody class="selectDetailsTbody">
                                        <tr class="border-b border-slate-100 dark:border-slate-700 detailsCol">
                                            <td
                                                class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-3 py-2">
                                                <strong>{{ __('Withdraw Amount') }}</strong>
                                            </td>
                                            <td class="dark:text-slate-300">
                                                <span class="withdrawAmount">{{ old('amount') }}</span>
                                                {{ $currency }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="The comments of the withdrawal">
                                        {{ __('Comments') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <textarea class="form-control" name="approval_cause" rows="5"></textarea>
                            </div>
                            @can('withdraw-auto-approve')
                                <div class="input-area">
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <label class="form-label !w-auto !mb-0">
                                            <span class="shift-Away inline-flex items-center gap-1"
                                                data-tippy-content="The auto approve of the withdrawal">
                                                {{ __('Auto Approve') }}
                                                <iconify-icon icon="mdi:information-slab-circle-outline"
                                                    class="text-[16px]"></iconify-icon>
                                            </span>
                                        </label>
                                        <div class="form-switch" style="line-height: 0;">
                                            <input class="form-check-input" type="hidden" value="0"
                                                name="is_auto_approve" />
                                            <label
                                                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="is_auto_approve" value="1"
                                                    class="sr-only peer">
                                                <span
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center space-x-2" data-loading-text="Processing...">
                                <iconify-icon class="text-xl" icon="lucide:check"></iconify-icon>
                                <span>{{ __('Proceed to Payment') }}</span>
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
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                                    id="dataTable">
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
                        <iconify-icon class="spining-icon text-5xl dark:text-slate-100"
                            icon="lucide:loader"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="transaction-action-modal" tabindex="-1" aria-labelledby="deposit-action-modal" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body popup-body">
                    <div class="popup-body-text deposit-action">

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal for withdraw account --}}
    @include('backend.withdraw.modals.__new_account')
@endsection
@section('script')
    <script !src="">
        "use strict";
        var info = [];
        var currency = @json($currency);

        (function($) {
            "use strict";

            var table = $('#dataTable')
                .on('processing.dt', function(e, settings, processing) {
                    $('#processingIndicator').css('display', processing ? 'block' : 'none');
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
                    ajax: {
                        url: "{{ route('admin.withdraw.history') }}",
                        data: function(d) {
                            d.user_id = $('select[name="user_id"]').val();
                            d.email = $('#email').val();
                            d.status = $('#status').val();
                            d.created_at = $('#created_at').val();

                        }
                    },

                    columns: [{
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'tnx',
                            name: 'tnx'
                        },
                        {
                            data: 'target_id',
                            name: 'target_id'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'charge',
                            name: 'charge'
                        },
                        {
                            data: 'method',
                            name: 'method'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            $('select[name="user_id"]').on('change', function() {
                table.draw();
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

            $('body').on('click', '#deposit-action', function() {
                $('.deposit-action').empty();

                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('admin.transactions.view', ':id') }}'.replace(':id', id),
                    method: 'GET',
                    success: function(response) {
                        $('.deposit-action').append(response)
                        imagePreview();
                        tippy(".shift-Away", {
                            placement: "top",
                            animation: "shift-away"
                        });
                        $('#transaction-action-modal').modal('show');

                    }
                });
            });
        })(jQuery);

        $('select[name="user_id"]').on('change', function() {
            var userId = $(this).val();
            $('select[name="target_id"]').empty();
            $('select[name="withdraw_account"]').empty();
            $('.withdrawDetailsTable').addClass('hidden');

            if (userId) {
                var url = '{{ route('admin.withdraw.get.user.accounts', ':userId') }}';
                url = url.replace(':userId', userId);

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {

                        $('select[name="target_id"]').empty();
                        $('select[name="withdraw_account"]').empty();

                        $('select[name="target_id"]').append(
                            '<option value="">{{ __('Select Account') }}</option>');
                        // Populate forex accounts
                        $.each(data.forexAccounts, function(key, account) {
                            $('select[name="target_id"]').append('<option value="' + account
                                .login + '" data-type="forex">' + account.login_title +
                                ' - ' + account.account_name + ' (' + account.equity + ' ' +
                                account.currency + ')</option>');
                        });

                        // Populate wallets
                        $.each(data.wallets, function(key, wallet) {
                            $('select[name="target_id"]').append('<option value="' + wallet
                                .wallet_id + '" data-type="wallet">' + wallet.wallet_name +
                                ' (' + wallet.amount + ' USD)</option>');
                        });

                        $.each(data.withdrawAccounts, function(key, withdrawAccount) {
                            if (key === 0) {
                                $('select[name="withdraw_account"]').append(
                                    '<option value="" disabled selected>Select Withdrawal Account</option>'
                                    );
                            }
                            $('select[name="withdraw_account"]').append(
                                '<option value="' + withdrawAccount.id +
                                '" data-type="withdrawal">' + withdrawAccount.method_name +
                                '</option>'
                            );
                        });

                        if (data.withdrawAccounts.length === 0) {
                            $('.withdraw_account_btn').removeClass('hidden');
                        } else {
                            $('.withdraw_account_btn').addClass('hidden');
                        }

                    }
                });
            } else {
                $('select[name="target_id"]').empty();
                $('select[name="withdraw_account"]').empty();
            }
        });

        // Capture the selected account and append the `data-type` to the form
        $('body').on('change', '#tradingAccount', function(e) {
            e.preventDefault();

            var selectedOption = $(this).find('option:selected');
            var dataType = selectedOption.data('type');
            console.log(dataType, 'dataType');

            $('#account_type').val(dataType);
        });

        $("#withdrawAccountId").on('change', function(e) {
            e.preventDefault();

            $('.withdrawDetailsTable').removeClass('hidden');

            $('.selectDetailsTbody').children().not(':first', ':second').remove();
            var accountId = $(this).val()
            var amount = $('.withdrawAmount').val();

            if (!isNaN(accountId)) {
                var url =
                    '{{ route('admin.withdraw.details', ['accountId' => ':accountId', 'amount' => ':amount']) }}';
                url = url.replace(':accountId', accountId, );
                url = url.replace(':amount', amount);

                $.get(url, function(data) {
                    info = data.info;
                    if (info.pay_currency === currency) {
                        $('.conversion').addClass('hidden');
                    } else {
                        $('.conversion').removeClass('hidden');
                        $('#basic-addon2').text(info.pay_currency);
                        $('#amount').trigger('keyup')
                        $('.conversion-rate').text('1' + ' ' + currency + ' = ' + info.rate + ' ' + info
                            .pay_currency)

                    }
                    $(data.html).insertAfter(".detailsCol");

                    $('.withdrawAmountRange').text(info.range)
                    $('.processing-time').text(info.processing_time)
                })
            }
        });

        $("#amount").on('keyup', function(e) {
            "use strict"
            e.preventDefault();
            var amount = $(this).val()
            var charge = info.charge_type === 'percentage' ? calPercentage(amount, info.charge) : info.charge
            $('.withdrawAmount').text(amount)
            $('.withdrawFee').text(charge)
            $('.processing-time').text(info.processing_time)
            $('.withdrawAmountRange').text(info.range)
            $('.pay-amount').text(parseFloat(((amount * info.rate) - (charge * info.rate)).toFixed(4)).toString() +
                ' ' + info.pay_currency)
            $('#converted-amount').val(parseFloat((amount * info.rate).toFixed(4)).toString())
        });

        $("#converted-amount").on('keyup', function(e) {
            "use strict"
            e.preventDefault();
            var converted_amount = $(this).val();
            var amount = parseFloat((converted_amount / info.rate).toFixed(4)).toString();
            $('#amount').val(amount);
            var charge = info.charge_type === 'percentage' ? calPercentage(amount, info.charge) : info.charge
            $('.withdrawAmount').text(amount)
            $('.withdrawFee').text(charge)
            $('.processing-time').text(info.processing_time)
            $('.withdrawAmountRange').text(info.range)
            $('.pay-amount').text(parseFloat(((amount * info.rate) - (charge * info.rate)).toFixed(4)).toString() +
                ' ' + info.pay_currency)
        });

        $('body').on('click', '.withdraw_account_btn', function(e) {
            "use strict"
            e.preventDefault();

            var userId = $('select[name="user_id"]').val();
            $('#userId__input').val(userId);

            $('#newWithdrawAccountModal').modal('show');
        })

        $("body").on('change', '#selectMethod', function(e) {
            "use strict"
            e.preventDefault();

            //$('.manual-row').empty();
            $('.selectMethodRow').children().not(':first').remove();

            var id = $(this).val()

            var url = '{{ route('admin.withdraw.account', ':id') }}';
            url = url.replace(':id', id);
            $.get(url, function(data) {
                $(data).insertAfter(".selectMethodCol");
                imagePreview()
            })
        });

        $(document).on('submit', '#withdrawForm', function () {
            
            const $form = $(this);
            const $btn = $form.find('[type=submit]');

            // Start loading
            $btn.buttonLoading(true, {
                text: '<span class="text-sm">Please wait...</span>'
            });

        });

    </script>
@endsection
