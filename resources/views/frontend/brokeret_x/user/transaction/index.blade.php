@extends('frontend::layouts.user')
@section('title')
    {{ __('Transaction History') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>

        <x-frontend::text-link href="{{ route('user.ticket.index') }}" variant="primary">
            {{ __('Get Support') }}
        </x-frontend::text-link>
    </div>

    <div x-data="transactionFilter()">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
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
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('user.history.transactions.export') }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <x-frontend::forms.button type="submit" class="w-full" variant="outline" icon="arrow-up-from-dot" icon-position="left" size="sm">
                            {{ __('Export') }}
                        </x-frontend::forms.button>
                    </form>
                </div>
            </div>
        </div>
        
        @if(count($transactions) == 0)
            <x-frontend::empty-state 
                icon="inbox"
                title="{!! __('You don\'t have any transactions yet') !!}"
                subtitle="{{ __('Make a deposit to start trading') }}">
                <x-slot name="actions">
                    <x-frontend::link-button href="{{ route('user.deposit.methods') }}" variant="primary" size="md">
                        {{ __('Deposit Now') }}
                    </x-frontend::link-button>
                </x-slot>
            </x-frontend::empty-state>
        @else
            <div class="space-y-3 mb-3" id="transaction-table-body">
                @include('frontend::user.transaction.include.__transaction_row', ['transactions' => $transactions])
            </div>
            <x-frontend::forms.button type="button" class="w-full md:w-fit" variant="secondary" size="md" @click="loadMore()" x-show="hasMore" x-bind:disabled="loading">
                <span x-show="!loading">{{ __('Load More') }}</span>
                <span x-show="loading">{{ __('Loading...') }}</span>
            </x-frontend::forms.button>
        @endif
    </div>
@endsection
@section('script')
    <script>
        function transactionFilter() {
            return {
                filters: {
                    transaction_status: '',
                    transaction_type: '',
                    transaction_date: '',
                    forex_account: ''
                },
                loading: false,
                error: null,
                page: 1,
                hasMore: true,
                debounceTimer: null,

                init() {
                    // restore saved filters from localStorage
                    this.restoreFilters();

                    // Initialize Lucide icons on component init
                    this.refreshLucideIcons();

                    // watch filters and reload transactions
                    this.$watch('filters', () => {
                        clearTimeout(this.debounceTimer);
                        this.debounceTimer = setTimeout(() => {
                            this.page = 1;
                            this.fetchTransactions(false);
                        }, 300);
                    }, { deep: true });
                },

                /**
                 * Fetch transactions from server
                 * @param {boolean} append - true = append (load more), false = replace (filter or refresh)
                 */
                async fetchTransactions(append = false) {
                    this.loading = true;
                    this.error = null;

                    const params = new URLSearchParams();
                    Object.entries(this.filters).forEach(([key, value]) => {
                        if (value) params.append(key, value);
                    });
                    params.append('page', this.page);

                    try {
                        const response = await fetch(`{{ route("user.history.transactions") }}?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (!response.ok) throw new Error('Network response was not ok');

                        const data = await response.json();
                        const container = document.getElementById('transaction-table-body');

                        // handle no results
                        if (!append && (!data.html || data.html.trim() === '')) {
                            container.innerHTML = `
                                <x-frontend::empty-state icon="inbox">
                                    <x-slot name="title">
                                        {{ __("No transaction matches your filters") }}
                                    </x-slot>
                                    <x-slot name="subtitle">
                                        {{ __("Try changing your search terms") }}
                                    </x-slot>
                                    <x-slot name="actions">
                                        <x-frontend::forms.button type="button" variant="primary" size="md" @click="clearFilters()">
                                            {{ __('Reset Filters') }}
                                        </x-frontend::forms.button>
                                    </x-slot>
                                </x-frontend::empty-state>`;
                            this.hasMore = false;
                            return;
                        }

                        if (append) {
                            // append rows
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = data.html;
                            Array.from(tempDiv.children).forEach(el => {
                                container.appendChild(el);
                            });
                        } else {
                            // replace rows
                            container.innerHTML = data.html;
                        }

                        // Refresh Lucide icons after DOM update
                        this.refreshLucideIcons();

                        // update load more state
                        this.hasMore = data.has_more ?? false;

                        // save filters
                        this.saveFiltersToStorage();

                    } catch (error) {
                        this.error = 'Error loading transactions. Please try again.';
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },

                /**
                 * Load more transactions (next page)
                 */
                loadMore() {
                    if (!this.hasMore || this.loading) return;
                    this.page++;
                    this.fetchTransactions(true);
                },

                /**
                 * Save filters to localStorage
                 */
                saveFiltersToStorage() {
                    Object.entries(this.filters).forEach(([key, value]) => {
                        if (value) {
                            localStorage.setItem(key, value);
                        } else {
                            localStorage.removeItem(key);
                        }
                    });
                },

                /**
                 * Restore filters from localStorage
                 */
                restoreFilters() {
                    Object.keys(this.filters).forEach(key => {
                        const value = localStorage.getItem(key);
                        if (value) {
                            this.filters[key] = value;
                        }
                    });
                },

                /**
                 * Clear all filters
                 */
                clearFilters() {
                    this.filters = {
                        transaction_status: '',
                        transaction_type: '',
                        transaction_date: '',
                        forex_account: ''
                    };
                    this.page = 1;

                    Object.keys(this.filters).forEach(key => {
                        localStorage.removeItem(key);
                    });

                    this.fetchTransactions(false);
                },

                /**
                 * Refresh Lucide icons
                 */
                refreshLucideIcons() {
                    // Use setTimeout to ensure DOM is fully updated
                    setTimeout(() => {
                        if (window.renderLucideIcons && typeof window.renderLucideIcons === 'function') {
                            try {
                                window.renderLucideIcons();
                            } catch (error) {
                                console.error('Error refreshing Lucide icons:', error);
                            }
                        } else if (window.lucide && typeof lucide.createIcons === 'function') {
                            try {
                                lucide.createIcons();
                            } catch (error) {
                                console.error('Error refreshing Lucide icons (fallback):', error);
                            }
                        }
                    }, 100);
                }
            }
        }

        // Global Lucide icon initialization
        function initLucideIcons() {
            if (window.renderLucideIcons && typeof window.renderLucideIcons === 'function') {
                try {
                    window.renderLucideIcons();
                    console.log('Lucide icons initialized successfully');
                } catch (error) {
                    console.error('Error initializing Lucide icons:', error);
                }
            } else if (window.lucide && typeof lucide.createIcons === 'function') {
                try {
                    lucide.createIcons();
                    console.log('Lucide icons initialized successfully (fallback)');
                } catch (error) {
                    console.error('Error initializing Lucide icons (fallback):', error);
                }
            } else {
                console.warn('Lucide library not available');
            }
        }

        // Initialize on different events to ensure icons load
        document.addEventListener('DOMContentLoaded', initLucideIcons);
        document.addEventListener('alpine:init', initLucideIcons);
        
        // Fallback initialization after a delay
        setTimeout(initLucideIcons, 500);
        
        // Initialize when window loads (final fallback)
        window.addEventListener('load', initLucideIcons);
    </script>
@endsection

