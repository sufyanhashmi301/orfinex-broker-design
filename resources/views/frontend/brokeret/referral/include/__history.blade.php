<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-5 xl:px-6 xl:py-6 dark:border-gray-800">
        <div class="flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('Referral History') }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Track your referral transactions and earnings') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
            <div class="input-area relative">
                <select id="transaction-date" x-model="transactionDate" @change="onFilterChange()" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                    <option value="">{{ __('Select Days') }}</option>
                    <option value="3_days">{{ __('Last 3 Days') }}</option>
                    <option value="5_days">{{ __('Last 5 Days') }}</option>
                    <option value="15_days">{{ __('Last 15 Days') }}</option>
                    <option value="1_month">{{ __('Last 1 Month') }}</option>
                    <option value="3_months">{{ __('Last 3 Months') }}</option>
                </select>
            </div>
            <div class="input-area relative">
                <select id="transaction-status" x-model="transactionStatus" @change="onFilterChange()" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                    <option value="">{{ __('All statuses') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="success">{{ __('Success') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="desktop-screen-show md:block hidden">
        @if(count($transactions) == 0)
            <div class="flex items-center justify-center flex-col py-10 px-10">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __("You don't have any referral transactions yet.") }}
                </p>
                <a href="{{ route('user.deposit.methods') }}" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    {{ __('Deposit Now') }}
                </a>
            </div>
        @else
            <div class="custom-scrollbar overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Description') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Transactions ID') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Account') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Amount') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Gateway') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Fee') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Status') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="transaction-table-body">
                        @include('frontend::user.transaction.include.__transaction_row', ['transactions' => $transactions])
                    </tbody>
                </table>
            </div>
            <div class="flex flex-wrap justify-between items-center border-t border-gray-100 dark:border-gray-800 gap-3 px-4 py-3 mt-auto">
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
        @endif
    </div>

    <div class="md:hidden block mobile-screen-show">
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
        @endif
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
                    {{ __("You don't have any referral transactions yet.") }}
                </p>
            </div>
        @else
            <div class="card all-feature-mobile mobile-transactions mb-3">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Referral Transactions') }}</h4>
                </div>
                <div class="card-body p-3 mobile-transaction-filter">
                    <div class="contents space-y-3" id="mobile-transactions-container">
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
                                            <x-badge variant="warning" style="light" size="sm">
                                                {{ __('Pending') }}
                                            </x-badge>
                                        @elseif($transaction->status->value == App\Enums\TxnStatus::Success->value)
                                            <x-badge variant="success" style="light" size="sm">
                                                {{ __('Success') }}
                                            </x-badge>
                                        @elseif($transaction->status->value == App\Enums\TxnStatus::Failed->value)
                                            <x-badge variant="error" style="light" size="sm">
                                                {{ __('Canceled') }}
                                            </x-badge>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pagination-container">
                        {{ $transactions->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
