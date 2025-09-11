<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
        {{ __('Reward History') }}
    </h2>

    <div class="flex-1 flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
        <div class="input-area relative">
            <select id="transaction-date" x-model="filters.transaction_date" @change="onFilterChange()"
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
            <select id="transaction-status" x-model="filters.transaction_status" @change="onFilterChange()"
                class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                <option value="">{{ __('All statuses') }}</option>
                <option value="pending">{{ __('Pending') }}</option>
                <option value="success">{{ __('Success') }}</option>
            </select>
        </div>
    </div>
</div>

@if(count($transactions) == 0)
    <x-frontend::empty-state icon="inbox">
        <x-slot name="title">
            {{ __("You don't have any referral transactions yet.") }}
        </x-slot>
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
