@extends('frontend::layouts.user')
@section('title')
    {{ __('Transaction History') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 pb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>
    
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div x-data="transactionFilter()" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-200 px-4 py-5 xl:px-6 xl:py-6 dark:border-gray-800">
            @include('frontend::user.transaction.include.__tabs_nav')
            <div class="flex-1 flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
                <div class="input-area relative">
                    <select x-model="filters.transaction_date" 
                        id="transaction-date" 
                        class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                        <option value="">{{ __('Select Days') }}</option>
                        <option value="3_days">{{ __('Last 3 Days') }}</option>
                        <option value="5_days">{{ __('Last 5 Days') }}</option>
                        <option value="15_days">{{ __('Last 15 Days') }}</option>
                        <option value="1_month">{{ __('Last 1 Month') }}</option>
                        <option value="3_months">{{ __('Last 3 Months') }}</option>
                    </select>
                </div>
                <div class="input-area relative">
                    <select x-model="filters.transaction_type" 
                        id="transaction-type" 
                        class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                        <option value="">{{ __('All transaction types') }}</option>
                        @foreach (getFilteredTxnTypes() as $txnType)
                            @if ($txnType->value !== 'ib_bonus')
                                <option value="{{ $txnType->value }}">{{ $txnType->label() }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="input-area relative">
                    <select x-model="filters.transaction_status" 
                        id="transaction-status" 
                        class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                        <option value="">{{ __('All statuses') }}</option>
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="success">{{ __('Success') }}</option>
                    </select>
                </div>
                <div class="input-area relative">
                    <select x-model="filters.forex_account" 
                        id="forex-account" 
                        class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                        <option value="">{{ __('All accounts') }}</option>
                        @foreach($realForexAccounts as $account)
                            <option value="{{ $account->login }}">{{ $account->account_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Clear filters button -->
                <button @click="clearFilters()" 
                    x-show="Object.values(filters).some(v => v !== '')"
                    class="inline-flex items-center gap-2 rounded bg-gray-100 px-3 py-1 text-sm font-medium text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i data-lucide="x" class="w-4"></i>
                    {{ __('Clear') }}
                </button>
                
                <form method="POST" action="{{ route('user.history.transactions.export') }}">
                    @csrf
                    <input type="hidden" name="query" value="{{ request('query') }}">
                    <input type="hidden" name="date" value="{{ request('date') }}">
                    <button type="submit" class="inline-flex items-center gap-2 rounded bg-white px-4 py-1 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                        <i data-lucide="arrow-up-from-dot" class="w-4 mr-2"></i>
                        {{ __('Export') }}
                    </button>
                </form>
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
                        {{ __("You don't have any transactions yet.") }}
                    </p>
                    <x-link-button href="{{ route('user.deposit.methods') }}" size="md" variant="primary" icon="arrow-up-from-dot" icon-position="left">
                        {{ __('Deposit Now') }}
                    </x-link-button>
                </div>
            @else
                <div class="custom-scrollbar overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead class="border-b border-gray-100 dark:border-gray-800">
                            <tr>
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Description') }}
                                    </span>
                                </th>
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Transactions ID') }}
                                    </span>
                                </th>
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Account') }}
                                    </span>
                                </th>
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Amount') }}
                                    </span>
                                </th>
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Gateway') }}
                                    </span>
                                </th>
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Fee') }}
                                    </span>
                                </th>
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="transaction-table-body">
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
            @endif
        </div>
    </div>
    <div class="md:hidden block mobile-screen-show">
        <!-- Transactions -->
        @if(count($transactions) == 0)
            <div class="flex items-center justify-center flex-col py-10 px-10">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __("You don't have any transactions yet.") }}
                </p>
                <x-link-button href="{{ route('user.deposit.methods') }}" size="md" variant="primary" icon="arrow-up-from-dot" icon-position="left">
                    {{ __('Deposit Now') }}
                </x-link-button>
            </div>
        @else
            <div class="mobile-transaction-filter">
                <div class="contents space-y-3" id="mobile-transactions-container">
                    @include('frontend::user.transaction.include.__transaction_row_mobile', ['transactions' => $transactions])
                </div>
                <div class="pagination-container">
                    {{ $transactions->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
@section('script')
    <script>
        function transactionFilter() {
            return {
                // Reactive filter data
                filters: {
                    transaction_status: '',
                    transaction_type: '',
                    transaction_date: '',
                    forex_account: ''
                },
                
                // UI state
                loading: false,
                error: null,
                totalRecords: 0,
                
                // Initialize component
                init() {
                    this.handleBackNavigation();
                    this.restoreFilters();
                    this.attachPaginationEvents();
                    
                    // Watch for filter changes with debouncing
                    this.$watch('filters', () => {
                        clearTimeout(this.debounceTimer);
                        this.debounceTimer = setTimeout(() => {
                            this.fetchTransactions();
                        }, 300);
                    }, { deep: true });
                },
                
                // Fetch transactions with Alpine.js
                async fetchTransactions(url = '{{ route("user.history.transactions") }}') {
                    this.loading = true;
                    this.error = null;
                    
                    // Build query parameters
                    const params = new URLSearchParams();
                    Object.entries(this.filters).forEach(([key, value]) => {
                        if (value) params.append(key, value);
                    });
                    
                    try {
                        const response = await fetch(`${url}?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (!response.ok) throw new Error('Network response was not ok');
                        
                        const data = await response.json();
                        
                        // Update DOM elements
                        this.updateTransactionTable(data);
                        this.updatePagination(data);
                        this.updateRecordsDisplay(data);
                        this.saveFiltersToStorage();
                        this.attachPaginationEvents();
                        
                    } catch (error) {
                        this.error = 'Error loading transactions. Please try again.';
                        console.error('Fetch error:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                // Update transaction table content
                updateTransactionTable(data) {
                    const tableBody = document.getElementById('transaction-table-body');
                    const mobileContainer = document.getElementById('mobile-transactions-container');
                    
                    if (data.html && data.html.trim() !== "") {
                        if (tableBody) tableBody.innerHTML = data.html;
                        if (mobileContainer) mobileContainer.innerHTML = data.mobile_html;
                    } else {
                        if (tableBody) {
                            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-8">No transactions found</td></tr>';
                        }
                        if (mobileContainer) {
                            mobileContainer.innerHTML = '<p class="text-center py-4 text-sm text-gray-500">{{ __("No transactions found") }}</p>';
                        }
                    }
                },
                
                // Update pagination
                updatePagination(data) {
                    const paginationContainers = document.querySelectorAll('.pagination-container');
                    paginationContainers.forEach(container => {
                        if (data.pagination) {
                            container.innerHTML = data.pagination;
                        }
                    });
                },
                
                // Update records display
                updateRecordsDisplay(data) {
                    this.totalRecords = data.total || 0;
                    const totalRecordsEl = document.getElementById('total-records');
                    if (totalRecordsEl && this.totalRecords > 0) {
                        totalRecordsEl.innerHTML = `
                            {{ __('Showing') }}
                            <span class="font-medium">1</span>
                            {{ __('to') }}
                            <span class="font-medium">${this.totalRecords}</span>
                            {{ __('of') }}
                            <span class="font-medium">${this.totalRecords}</span>
                            {{ __('results') }}
                        `;
                    }
                },
                
                // Handle pagination clicks
                attachPaginationEvents() {
                    setTimeout(() => {
                        document.querySelectorAll('.pagination a').forEach(link => {
                            link.removeEventListener('click', this.handlePaginationClick);
                            link.addEventListener('click', this.handlePaginationClick.bind(this));
                        });
                    }, 100);
                },
                
                // Pagination click handler
                handlePaginationClick(e) {
                    e.preventDefault();
                    const url = e.target.closest('a')?.href;
                    if (url) {
                        this.fetchTransactions(url);
                    }
                },
                
                // Save filters to localStorage
                saveFiltersToStorage() {
                    Object.entries(this.filters).forEach(([key, value]) => {
                        if (value) {
                            localStorage.setItem(key, value);
                        } else {
                            localStorage.removeItem(key);
                        }
                    });
                },
                
                // Restore filters from localStorage
                restoreFilters() {
                    Object.keys(this.filters).forEach(key => {
                        const value = localStorage.getItem(key);
                        if (value) {
                            this.filters[key] = value;
                        }
                    });
                },
                
                // Clear all filters
                clearFilters() {
                    this.filters = {
                        transaction_status: '',
                        transaction_type: '',
                        transaction_date: '',
                        forex_account: ''
                    };
                    
                    // Clear localStorage
                    Object.keys(this.filters).forEach(key => {
                        localStorage.removeItem(key);
                    });
                },
                
                // Handle browser back navigation
                handleBackNavigation() {
                    const isNavigatedBack = performance.navigation?.type === 2 || 
                                          sessionStorage.getItem('navigatedBack') === 'true';
                    
                    if (isNavigatedBack) {
                        console.log("Navigated back - resetting filters");
                        this.clearFilters();
                        sessionStorage.removeItem('navigatedBack');
                    }
                    
                    // Detect back navigation
                    window.addEventListener('pageshow', (event) => {
                        if (event.persisted || 
                            (performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                            sessionStorage.setItem('navigatedBack', 'true');
                        }
                    });
                }
            }
        }
    </script>
@endsection
