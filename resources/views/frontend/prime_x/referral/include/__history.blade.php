<div class="pageTitle flex flex-col md:flex-row justify-between md:items-center flex-wrap mb-6">
    <h4 class="font-medium text-xl capitalize text-slate-700 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0">
        {{ __('All Referral Logs') }}
    </h4>
    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
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
            <select id="transaction-status" class="form-control">
                <option value="">{{ __('All statuses') }}</option>
                <option value="pending">{{ __('Pending') }}</option>
                <option value="success">{{ __('Success') }}</option>
            </select>
        </div>
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

                                    <p class="text-sm text-gray-700 dark:text-slate-100 px-3" id="total-records">
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
    @if(count($transactions) == 0)
        <div class="basicTable_wrapper card flex items-center justify-center flex-col p-4">
            <svg width="42" height="43" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-slate-600 dark:text-slate-100 my-3">
                {{ __("You don't have any transactions yet.") }}
            </p>
        </div>
    @else
        <div class="card all-feature-mobile mobile-transactions mb-3">
            <div class="card-header">
                <h4 class="card-title">{{ __('All Transactions') }}</h4>
            </div>
            <div class="card-body p-3 mobile-transaction-filter">
                <div class="contents space-y-3">
                    @foreach($transactions as $transaction)
                        <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                            <div class="transaction-left w-3/4">
                                <div class="transaction-des">
                                    <div class="transaction-title font-semibold dark:text-white mb-1">
                                        {{ $transaction->description }}
                                    </div>
                                    <div class="transaction-id dark:text-white mb-1">
                                        {{ $transaction->tnx }}
                                    </div>
                                    <div class="transaction-date dark:text-white mb-1">
                                        {{ $transaction->display_time->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            </div>
                            <div class="transaction-right text-right">
                                <div class="transaction-amount font-semibold dark:text-white mb-1">
                                    {{ txn_type($transaction->type->value, ['+','-']) }}{{ number_format($transaction->amount, 2) }} {{ $currency }}
                                </div>
                                <div class="transaction-fee dark:text-white mb-1">
                                    -{{ number_format($transaction->charge, 2) }} {{ $currency }}
                                </div>
                                <div class="transaction-gateway dark:text-white mb-1">
                                    {{ $transaction->method ?? '-' }}
                                </div>
                                <div class="transaction-status">
                                    @if($transaction->status->value == App\Enums\TxnStatus::Pending->value)
                                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                                    @elseif($transaction->status->value == App\Enums\TxnStatus::Success->value)
                                        <span class="badge badge-success">{{ __('Success') }}</span>
                                    @elseif($transaction->status->value == App\Enums\TxnStatus::Failed->value)
                                        <span class="badge badge-danger">{{ __('Canceled') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $transactions->onEachSide(1)->links() }}
            </div>
        </div>
    @endif
</div>
