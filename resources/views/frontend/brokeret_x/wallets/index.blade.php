@extends('frontend::layouts.user')
@section('title')
    {{ __('My Wallet') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>

    <div class="space-y-3 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex flex-col">
                <div class="text-gray-600 dark:text-gray-300 text-sm mb-1 font-medium">
                    {{ __('E-') }}{{ data_get($mainWallet,'wallet_id') }}
                </div>
                <div class="flex items-end gap-1">
                    <span class="text-title-sm font-bold text-gray-800 dark:text-white/90">{{ data_get($mainWallet,'amount') }}</span>
                    <span class="text-lg font-bold text-gray-800 dark:text-white/90">{{ $currency }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-7">
                <x-frontend::link-button href="{{route('user.deposit.methods')}}" class="flex-1" variant="primary" icon="banknote-arrow-up" iconPosition="left">
                    {{ __('Deposit') }}
                </x-frontend::link-button>
                <x-frontend::link-button href="{{route('user.withdraw.view')}}" class="flex-1" variant="outline" icon="banknote-arrow-down" iconPosition="left">
                    {{ __('Withdraw') }}
                </x-frontend::link-button>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex flex-col">
                <div class="text-gray-600 dark:text-gray-300 text-sm mb-1 font-medium">
                    {{ __('IB-') }}{{ data_get($ibWallet,'wallet_id') }}
                </div>
                <div class="flex items-end gap-1">
                    <span class="text-title-sm font-bold text-gray-800 dark:text-white/90">{{ data_get($ibWallet,'amount') }}</span>
                    <span class="text-lg font-bold text-gray-800 dark:text-white/90">{{ $currency }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-7">
                <x-frontend::link-button href="{{route('user.deposit.methods')}}" class="flex-1" variant="primary" icon="banknote-arrow-up" iconPosition="left">
                    {{ __('Deposit') }}
                </x-frontend::link-button>
                <x-frontend::link-button href="{{route('user.withdraw.view')}}" class="flex-1" variant="outline" icon="banknote-arrow-down" iconPosition="left">
                    {{ __('Withdraw') }}
                </x-frontend::link-button>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            {{ __('Recent Transactions') }}
        </h2>
    </div>

    <div x-data="transactionTable()">
        @if(count($wallets) == 0)
            <div class="flex items-center justify-center flex-col gap-3 px-10 mt-10 lg:mt-20">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                        {{ __("You don't have any transactions yet.") }}
                    </h2>
                    <p class="mt-1 text-base text-gray-500 dark:text-gray-400">
                        {{ __("Make a deposit to start trading.") }}
                    </p>
                </div>
                <x-frontend::link-button href="{{ route('user.deposit.methods') }}" variant="primary" size="md">
                    {{ __('Deposit Now') }}
                </x-frontend::link-button>
            </div>
        @else
            <div class="space-y-3 mb-3" id="transaction-table-body">
                @include('frontend::wallets.include.__transaction_row', ['wallets' => $wallets])
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
        document.addEventListener("alpine:init", () => {
            Alpine.data("transactionTable", () => ({
                page: 1,
                hasMore: {{ $wallets->hasMorePages() ? 'true' : 'false' }},
                loading: false,

                async fetchTransactions() {
                    this.loading = true;
                    try {
                        let response = await fetch(`{{ route('user.wallet.index') }}?page=${this.page}`, {
                            headers: { "X-Requested-With": "XMLHttpRequest" }
                        });
                        let data = await response.text();

                        if (data.trim() === '') {
                            this.hasMore = false;
                        } else {
                            document.querySelector('#transaction-table-body')
                                .insertAdjacentHTML('beforeend', data);

                            // Reinitialize Lucide icons for newly loaded content
                            if (window.renderLucideIcons) {
                                window.renderLucideIcons();
                            }
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loading = false;
                    }
                },

                loadMore() {
                    this.page++;
                    this.fetchTransactions();
                }
            }))
        })
    </script>
@endsection
