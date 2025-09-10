@if(count($realForexAccounts) == 0)
    <x-frontend::empty-state icon="inbox">
        <x-slot name="subtitle">
            {{ __('Open a new account to start trading.') }}
        </x-slot>
        <x-slot name="actions">
            <x-frontend::link-button href="{{ route('user.schema') }}" variant="primary" size="md" icon="plus" icon-position="left">
                {{ __('Open Account') }}
            </x-frontend::link-button>
        </x-slot>
    </x-frontend::empty-state>
@else
    <div class="accounts-container" 
         x-data="{ 
            viewMode: 'grid',
            init() {
                this.viewMode = this.$root.viewMode || 'grid';
            }
         }" 
         x-on:view-mode-changed.window="viewMode = $event.detail.mode"
         x-bind:class="viewMode === 'list' ? 'space-y-2.5' : 'grid xl:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-3'">
        @foreach($realForexAccounts as $account)
            <div class="rounded-lg border border-gray-200 dark:border-gray-800 trading-account-card" 
                 x-bind:class="viewMode === 'list' ? 'w-full' : 'lg:h-full'">
                <div class="grid-view-layout">
                    <div class="flex items-center justify-between border-b dark:border-slate-700 p-3">
                        <div class="flex items-center gap-2">
                            <x-frontend::badge variant="light" style="light" size="sm">
                                {{ $account->schema->title }}
                            </x-frontend::badge>
                            <h5 class="text-base font-medium mb-0 dark:text-white/90">{{ $account->account_name }}</h5>
                        </div>
                        @include('frontend::.user.forex.dropdown-menu')
                    </div>
                    <ul class="h-full p-3">
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('Number') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-right text-gray-600 dark:text-gray-300">
                                {{ $account->login }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('Account Type') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-right text-gray-600 dark:text-gray-300">
                                {{ $account->account_type }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('Platform') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-gray-600 dark:text-gray-300">
                                {{ __('MT5') }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('Server') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-gray-600 dark:text-gray-300">
                                {{ $account->server }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('Balance') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-gray-600 dark:text-gray-300">
                                {{ get_mt5_account_balance($account->login) }} {{ $account->currency }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('Leverage') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-gray-600 dark:text-gray-300">
                                {{ $account->leverage }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('Equity') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-gray-600 dark:text-gray-300">
                                {{ get_mt5_account_equity($account->login) }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="list-view-layout p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <div>
                            <x-frontend::badge variant="light" style="light" size="sm">
                                {{ ucfirst(data_get($account, 'account_type')) }}
                            </x-frontend::badge>
                            <x-frontend::badge variant="light" style="light" size="sm" class="mx-1">
                                {{ __('MT5') }}
                            </x-frontend::badge>
                            <x-frontend::badge variant="light" style="light" size="sm" class="mr-1">
                                {{ $account->schema->title }}
                            </x-frontend::badge>
                        </div>
                        <h6 class="text-base font-medium text-gray-700 dark:text-gray-400">
                            {{ $account->account_name }} / {{ $account->login }}
                        </h6>
                    </div>
                    <div class="flex flex-wrap gap-3 justify-between items-center mt-5">
                        <p class="account-balance mb-0 text-gray-800 dark:text-white/90">
                            <span class="text-3xl font-medium">{{ $account->balance }}</span>
                            <span>{{ $account->currency }}</span>
                        </p>
                        <div class="action-btns flex items-center gap-3">
                            <x-frontend::link-button href="{{ route('user.deposit.methods') }}" variant="primary" size="md" icon="download" iconPosition="left">
                                {{ __('Deposit') }}
                            </x-frontend::link-button>
                            <x-frontend::link-button href="{{ route('user.withdraw.view') }}" variant="secondary" size="md" icon="upload" iconPosition="left">
                                {{ __('Withdraw') }}
                            </x-frontend::link-button>
                            <x-frontend::link-button href="javascript:;" variant="outline" size="md" icon="chart-candlestick" iconPosition="left">
                                {{ __('Trade') }}
                            </x-frontend::link-button>
                            @include('frontend::.user.forex.dropdown-menu')
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
