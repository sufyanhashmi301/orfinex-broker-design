@extends('frontend::layouts.user')
@section('title')
    {{ __('Transaction History') }}
@endsection
@section('content')
    @include('frontend::user.transaction.include.__tabs_nav')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <div class="input-area relative">
                <select id="transaction-date" class="form-control">
                    <option value="">{{ __('Select Days') }}</option>
                    <option value="3_days">{{ __('Last 3 Days') }}</option>
                    <option value="5_days">{{ __('Last 5 Days') }}</option>
                    <option value="15_days">{{ __('Last 15 Days') }}</option>
                    <option value="1_month">{{ __('Last 1 Month') }}</option>
                    <option value="3_months">{{ __('Last 3 Months') }}</option>
                </select>
            </div>
            <div class="input-area relative">
                <select id="transaction-type" class="form-control">
                    <option value="">{{ __('All transaction types') }}</option>
                    @foreach (getFilteredTxnTypes() as $txnType)
                        <option value="{{ $txnType->value }}">{{ $txnType->label() }}</option>
                    @endforeach
{{--                    <option value="deposit">{{ __('Deposit') }}</option>--}}
{{--                    <option value="withdraw">{{ __('Withdraw') }}</option>--}}
{{--                    <option value="send_money">{{ __('Transfer') }}</option>--}}
{{--                    <option value="ib_bonus">{{ __('IB Bonus') }}</option>--}}
                </select>
            </div>
            <div class="input-area relative">
                <select id="transaction-status" class="form-control">
                    <option value="">{{ __('All statuses') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="success">{{ __('Success') }}</option>
                </select>
            </div>
            <div class="input-area relative">
                <select id="forex-account" class="form-control">
                    <option value="">{{ __('All accounts') }}</option>
                    @foreach($realForexAccounts as $account)
                        <option value="{{ $account->login }}">{{ $account->account_name }}</option>
                    @endforeach
                </select>
            </div>
            <form method="POST" action="{{ route('user.history.transactions.export') }}">
                @csrf
                <input type="hidden" name="query" value="{{ request('query') }}">
                <input type="hidden" name="date" value="{{ request('date') }}">
                <button type="submit" class="btn btn-sm btn-white inline-flex items-center justify-center min-w-max">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                    {{ __('Export') }}
                </button>
            </form>
        </div>
    </div>

    <div class="space-y-5">
        <div class="card desktop-screen-show md:block hidden">
            <div class="card-body px-6 pt-3">
                @if(count($transactions) == 0)
                    <div class="basicTable_wrapper flex items-center justify-center flex-col">
                        <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                            {{ __("You don't have any transactions yet.") }}
                        </p>
                        <a href="{{ route('user.deposit.methods') }}" class="btn btn-dark inline-flex items-center justify-center min-w-[170px]">
                            {{ __('Deposit Now') }}
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden basicTable_wrapper">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('Description') }}</th>
                                            <th scope="col" class="table-th">{{ __('Transactions ID') }}</th>
                                            <th scope="col" class="table-th">{{ __('Account') }}</th>
                                            <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                            <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                            <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                            <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700" id="transaction-table-body">
                                        @include('frontend::user.transaction.include.__transaction_row', ['transactions' => $transactions])
                                    </tbody>
                                </table>
                                <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-3 mt-auto">
                                    <div>
                                        @php
                                            $from = $transactions->firstItem(); // The starting item number on the current page
                                            $to = $transactions->lastItem(); // The ending item number on the current page
                                            $total = $transactions->total(); // The total number of items
                                        @endphp

                                        <p class="text-sm text-gray-700 dark:text-slate-300 px-3" id="total-records">
                                            {{ __('Showing') }}
                                            <span class="font-medium">{{ $transactions->firstItem() }}</span>
                                            {{ __('to') }}
                                            <span class="font-medium">{{ $transactions->lastItem() }}</span>
                                            {{ __('of') }}
                                            <span class="font-medium">{{ $transactions->total() }}</span>
                                            {{ __('results') }}
                                        </p>

                                    </div>
                                    <div class="pagination-container">
                                        {{ $transactions->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="md:hidden block mobile-screen-show">
        <!-- Transactions -->
        <div class="card all-feature-mobile mobile-transactions mb-3">
            <div class="card-header">
                <h4 class="card-title">{{ __('All Transactions') }}</h4>
            </div>
            <div class="card-body p-3 mobile-transaction-filter">
                <div class="filter mb-3">
                    <form action="{{ route('user.history.transactions') }}" method="get">
                        <div class="search flex items-center gap-2">
                            <input type="text" class="form-control" placeholder="{{ __('Search') }}" value="{{ request('query') }}" name="query"/>
                            <input type="date" class="form-control" name="date" value="{{ request()->get('date') }}"/>
                            <button type="submit" class="apply-btn h-10 btn btn-dark">
                                <iconify-icon icon="lucide:search"></iconify-icon>
                            </button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('user.history.transactions.export') }}">
                        @csrf
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                            {{ __('Export') }}
                        </button>
                    </form>
                </div>
                <div class="contents space-y-3">
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="table-td">
            <span class="block font-medium text-slate-700 dark:text-white">
                {{ $transaction->description }}
            </span>
                                <span class="block text-xs text-gray-500">
                {{ $transaction->display_time->format('M d, Y h:i A') }}
            </span>
                            </td>
                            <td class="table-td">{{ $transaction->tnx }}</td>
                            <td class="table-td">{{ $transaction->target_id ?? '-' }}</td>
                            <td class="table-td">{{ txn_type($transaction->type->value, ['+','-']) }}{{ number_format($transaction->amount, 2) }} {{ $currency }}</td>
                            <td class="table-td">{{ $transaction->method ?? '-' }}</td>
                            <td class="table-td">-{{ number_format($transaction->charge, 2) }} {{ $currency }}</td>
                            <td class="table-td">
                                @if($transaction->status->value == App\Enums\TxnStatus::Pending->value)
                                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                                @elseif($transaction->status->value == App\Enums\TxnStatus::Success->value)
                                    <span class="badge badge-success">{{ __('Success') }}</span>
                                @elseif($transaction->status->value == App\Enums\TxnStatus::Failed->value)
                                    <span class="badge badge-danger">{{ __('Canceled') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </div>
                {{ $transactions->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script !src="">
        $(document).ready(function () {
            function fetchTransactions(url = '{{ route("user.history.transactions") }}') {
                let status = $('#transaction-status').val();
                let type = $('#transaction-type').val();
                let date = $('#transaction-date').val();
                let account = $('#forex-account').val();

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        transaction_status: status,
                        transaction_type: type,
                        transaction_date: date,
                        forex_account: account,
                    },
                    beforeSend: function () {
                        $('#transaction-table-body').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                    },
                    success: function (response) {
                        if (response.html.trim() === "") {
                            $('#transaction-table-body').html('<tr><td colspan="7" class="text-center">No transactions found</td></tr>');
                        } else {
                            $('#transaction-table-body').html(response.html);
                        }
                        $('.pagination-container').html(response.pagination);
                        $('#total-records').html(`
                    {{ __('Showing') }}
                        <span class="font-medium">${response.total > 0 ? 1 : 0}</span>
                    {{ __('to') }}
                        <span class="font-medium">${response.total}</span>
                    {{ __('of') }}
                        <span class="font-medium">${response.total}</span>
                    {{ __('results') }}
                        `);

                        // Store selections in localStorage
                        localStorage.setItem('transaction_status', status);
                        localStorage.setItem('transaction_type', type);
                        localStorage.setItem('transaction_date', date);
                        localStorage.setItem('forex_account', account);

                        // Reattach event handlers
                        attachPaginationEvents();
                    },
                    error: function () {
                        alert('Error loading transactions.');
                    }
                });
            }

            function attachPaginationEvents() {
                $('.pagination a').off('click').on('click', function (e) {
                    e.preventDefault();
                    let url = $(this).attr('href');
                    if (url) {
                        fetchTransactions(url);
                    }
                });
            }

            function resetFiltersIfNavigatedBack() {
                let isNavigatedBack = performance.navigation.type === 2 || sessionStorage.getItem('navigatedBack') === 'true';

                if (isNavigatedBack) {
                    console.log("Navigated back - resetting filters");

                    // Clear stored filters
                    localStorage.removeItem('transaction_status');
                    localStorage.removeItem('transaction_type');
                    localStorage.removeItem('transaction_date');
                    localStorage.removeItem('forex_account');

                    // Reset dropdown values
                    $('#transaction-status').val('');
                    $('#transaction-type').val('');
                    $('#transaction-date').val('');
                    $('#forex-account').val('');

                    sessionStorage.removeItem('navigatedBack'); // Reset flag
                }
            }

            function restoreSelections() {
                if (localStorage.getItem('transaction_status')) {
                    $('#transaction-status').val(localStorage.getItem('transaction_status'));
                }
                if (localStorage.getItem('transaction_type')) {
                    $('#transaction-type').val(localStorage.getItem('transaction_type'));
                }
                if (localStorage.getItem('transaction_date')) {
                    $('#transaction-date').val(localStorage.getItem('transaction_date'));
                }
                if (localStorage.getItem('forex_account')) {
                    $('#forex-account').val(localStorage.getItem('forex_account'));
                }
            }

            // Detect if user navigated back
            window.addEventListener('pageshow', function (event) {
                if (event.persisted || (performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                    sessionStorage.setItem('navigatedBack', 'true');
                }
            });

            // Reset filters if user navigated back
            resetFiltersIfNavigatedBack();

            // Restore previous selections if they exist
            restoreSelections();

            // Attach event handlers to filters
            $('#transaction-date, #transaction-status, #transaction-type, #forex-account').on('change', function () {
                fetchTransactions();
            });

            // Attach pagination event initially
            attachPaginationEvents();
        });



    </script>
@endsection
