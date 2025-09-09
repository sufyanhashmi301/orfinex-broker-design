@if(count($realForexAccounts) == 0)
    <div class="py-10 px-10">
        <div class="flex items-center justify-center flex-col gap-3">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-theme-sm text-center text-gray-600 dark:text-gray-100 mb-3">
                {{ __('You don\'t have any Real account.') }}
            </p>
            <x-frontend::link-button href="{{ route('user.schema') }}" variant="primary" size="md">
                {{ __('Open Account') }}
            </x-frontend::link-button>
        </div>
    </div>
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
