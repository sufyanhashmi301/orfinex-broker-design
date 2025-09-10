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
            <x-frontend::empty-state icon="inbox">
                <x-slot name="title">
                    {{ __("You don't have any transactions yet.") }}
                </x-slot>
                <x-slot name="subtitle">
                    {{ __("Make a deposit to start trading.") }}
                </x-slot>
                <x-slot name="actions">
                    <x-frontend::link-button href="{{ route('user.deposit.methods') }}" variant="primary" size="md">
                        {{ __('Deposit Now') }}
                    </x-frontend::link-button>
                </x-slot>
            </x-frontend::empty-state>
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
