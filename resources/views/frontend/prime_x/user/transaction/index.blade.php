@extends('frontend::layouts.user')
@section('title')
    {{ __('Transaction History') }}
@endsection
@section('content')
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
                    <option value="deposit">{{ __('Deposit') }}</option>
                    <option value="withdraw">{{ __('Withdraw') }}</option>
                    <option value="send_money">{{ __('Transfer') }}</option>
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
            <form method="POST" action="{{ route('user.transactions.export') }}">
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
                        <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
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

                                        <p class="text-sm text-gray-700 dark:text-slate-300 px-3">
                                            {{ __('Showing') }}
                                            <span class="font-medium">{{ $from }}</span>
                                            {{ __('to') }}
                                            <span class="font-medium">{{ $to }}</span>
                                            {{ __('of') }}
                                            <span class="font-medium">{{ $total }}</span>
                                            {{ __('results') }}
                                        </p>
                                    </div>
                                    {{ $transactions->links() }}
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
                    <form action="{{ route('user.transactions') }}" method="get">
                        <div class="search flex items-center gap-2">
                            <input type="text" class="form-control" placeholder="{{ __('Search') }}" value="{{ request('query') }}" name="query"/>
                            <input type="date" class="form-control" name="date" value="{{ request()->get('date') }}"/>
                            <button type="submit" class="apply-btn h-10 btn btn-dark">
                                <iconify-icon icon="lucide:search"></iconify-icon>
                            </button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('user.transactions.export') }}">
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
                    @foreach($transactions as $transaction )
                        <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                            <div class="transaction-left w-3/4">
                                <div class="transaction-des">
                                    <div class="transaction-title mb-1 dark:text-white">{{ $transaction->description }}</div>
                                    <div class="transaction-id mb-1 dark:text-white">{{ $transaction->tnx }}</div>
                                    <div class="transaction-date mb-1 dark:text-white">{{ $transaction->created_at }}</div>
                                </div>
                            </div>
                            <div class="transaction-right text-right">
                                <div class="transaction-amount {{ txn_type($transaction->type->value,['add','sub']) }} mb-1 dark:text-white">
                                    {{ txn_type($transaction->type->value,['+','-']) }}{{ $transaction->amount . ' ' . $currency }}</div>
                                <div class="transaction-fee sub mb-1 dark:text-white">
                                    -{{ $transaction->charge . ' ' . $currency . ' ' . __('Fee') }} </div>
                                <div class="transaction-gateway mb-1 dark:text-white">{{ $transaction->method }}</div>

                                @if($transaction->status->value == App\Enums\TxnStatus::Pending->value)
                                    <div class="transaction-status text-warning">{{ __('Pending') }}</div>
                                @elseif($transaction->status->value == App\Enums\TxnStatus::Success->value)
                                    <div class="transaction-status text-success">{{ __('Success') }}</div>
                                @elseif($transaction->status->value == App\Enums\TxnStatus::Failed->value)
                                    <div class="transaction-status text-danger">{{ __('Canceled') }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $transactions->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script !src="">

        $('#transaction-date, #transaction-status, #transaction-type, #forex-account').on('change', function() {
            const status = $('#transaction-status').val();
            const type = $('#transaction-type').val();
            const date = $('#transaction-date').val();
            const account = $('#forex-account').val();

            $.ajax({
                url: '{{ route("user.transactions") }}',
                type: 'GET',
                data: {
                    transaction_status: status,
                    transaction_type: type,
                    transaction_date: date,
                    forex_account: account,
                },
                success: function (response) {
                    $('#transaction-table-body').html(response); // Update the table body
                }
            })
        });

    </script>
@endsection
