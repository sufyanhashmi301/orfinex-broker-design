<div class="pageTitle flex flex-col md:flex-row justify-between md:items-center flex-wrap mb-6">
    <h4 class="font-medium text-xl capitalize text-slate-700 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-2">
        {{ __('All Referral Logs') }}
    </h4>
    <div class="flex flex-col sm:flex-row sm:items-center w-full gap-3 mt-2 sm:mt-0">
        <!-- Advanced Filter Inputs (same as admin side) -->
        <form id="filter-form" class="w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
            <div class="flex-1 input-area relative">
                <input type="text" id="ib-bonus-login" class="form-control h-full" placeholder="Login">
            </div>
            <div class="flex-1 input-area relative">
                <input type="text" id="ib-bonus-deal" class="form-control h-full" placeholder="Deal">
            </div>
            <div class="flex-1 input-area relative">
                <input type="text" id="ib-bonus-symbol" class="form-control h-full" placeholder="Symbol">
            </div>
            <div class="flex-1 input-area relative">
                <select id="ib-bonus-date-filter" class="form-control">
                    <option value="">{{ __('Select Days') }}</option>
                    <option value="3_days">{{ __('Last 3 Days') }}</option>
                    <option value="5_days">{{ __('Last 5 Days') }}</option>
                    <option value="15_days">{{ __('Last 15 Days') }}</option>
                    <option value="1_month">{{ __('Last 1 Month') }}</option>
                    <option value="3_months">{{ __('Last 3 Months') }}</option>
                </select>
            </div>
            <div class="flex-1 input-area relative">
                <input type="text" id="ib-bonus-created-at" class="form-control flatpickr-created-at h-full w-full"
                    placeholder="Created At Range" readonly>
            </div>
            <div class="input-area relative">
                <button type="button" id="ib-bonus-filter-btn"
                    class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                    {{ __('Filter') }}
                </button>
            </div>
        </form>
        <form method="POST" id="ib-export-form" action="{{ route('user.referral.history.export') }}">
            @csrf
            <input type="hidden" name="login" id="export-ib-bonus-login">
            <input type="hidden" name="deal" id="export-ib-bonus-deal">
            <input type="hidden" name="symbol" id="export-ib-bonus-symbol">
            <input type="hidden" name="date_filter" id="export-ib-bonus-date-filter">
            <input type="hidden" name="created_at" id="export-ib-bonus-created-at">
            <div class="input-area relative mb-1">
                <button type="submit"
                    class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                        icon="lets-icons:export-fill"></iconify-icon>
                    {{ __('Export') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards (same design as IB dashboard) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6" id="ib-summary-cards" style="display: none;">
    <div class="card">
        <div class="card-body p-6">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                {{ __('Total IB Received') }}
            </p>
            <div class="flex items-end space-x-3 mb-2">
                <h6 class="block text-2xl text-slate-900 dark:text-white font-medium leading-none" id="ib-balance">
                    $0.00
                </h6>
            </div>
            <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                {{ __('Lifetime Total') }}
            </p>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                {{ __('Current IB Wallet') }}
            </p>
            <div class="flex items-end space-x-3 mb-2">
                <h6 class="block text-2xl text-slate-900 dark:text-white font-medium leading-none"
                    id="current-ib-wallet">
                    $0.00
                </h6>
            </div>
            <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                {{ __('Available Balance') }}
            </p>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                {{ __('Filtered Results') }}
            </p>
            <div class="flex items-end space-x-3 mb-2">
                <h6 class="block text-2xl text-slate-900 dark:text-white font-medium leading-none" id="filtered-amount">
                    $0.00
                </h6>
            </div>
            <p class="font-normal text-xs text-slate-600 dark:text-slate-300" id="filtered-count">
                0 showing
            </p>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                {{ __('Filter Range') }}
            </p>
            <div class="flex items-end space-x-3 mb-2">
                <h6 class="block text-sm text-slate-900 dark:text-white font-medium leading-none"
                    id="oldest-entry-date">
                    {{ __('All time') }}
                </h6>
            </div>
            <p class="font-normal text-xs text-slate-600 dark:text-slate-300" id="date-range-info">
                {{ __('No date filter') }}
            </p>
        </div>
    </div>
</div>

<div class="space-y-5">
    <div class="card desktop-screen-show md:block hidden">
        <div class="card-body px-6 pt-3">
            @if (count($transactions) == 0)
                <div class="basicTable_wrapper flex items-center justify-center flex-col">
                    <svg width="52" height="53" viewBox="0 0 52 53" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z"
                            stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
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
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700"
                                    id="transaction-table-body">
                                    @include('frontend::referral.include.__ib_transaction_row', [
                                        'transactions' => $transactions,
                                    ])
                                </tbody>
                            </table>
                            <div
                                class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-3 mt-auto">
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
    @if (count($transactions) == 0)
        <div class="basicTable_wrapper card flex items-center justify-center flex-col p-4">
            <svg width="42" height="43" viewBox="0 0 52 53" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z"
                    stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" />
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
                <div id="transaction-mobile-body" class="contents space-y-3">
                    @include('frontend::referral.include.__mobile_ib_transaction_row', [
                        'transactions' => $transactions,
                    ])
                </div>
                {{ $transactions->onEachSide(1)->links() }}
            </div>
        </div>
    @endif
</div>
