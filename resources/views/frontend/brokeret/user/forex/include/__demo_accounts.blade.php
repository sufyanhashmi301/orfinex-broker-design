@if(count($demoForexAccounts) == 0)
    <div class="py-10 px-10">
        <div class="flex items-center justify-center flex-col gap-3">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                {{ __('You don\'t have any Demo account.') }}
            </p>
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
         x-bind:class="viewMode === 'list' ? 'space-y-3' : 'grid xl:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5'">
        @foreach($demoForexAccounts as $account)
            <div class="rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/5 trading-account-card" 
                 x-bind:class="viewMode === 'list' ? 'w-full' : 'lg:h-full'">
                <div class="grid-view-layout">
                    <div class="flex items-center justify-between border-b dark:border-slate-700 p-3">
                        <div class="flex items-center">
                            <h5 class="text-xl mb-0 dark:text-white">{{ $account->account_name }}</h5>
                            <x-badge variant="light" style="light" size="sm" class="ml-2">
                                {{ $account->schema->title }}
                            </x-badge>
                        </div>
                        @include('frontend::.user.forex.dropdown-menu')
                    </div>
                    <ul class="h-full p-3">
                        <li class="flex items-baseline relative overflow-hidden py-3">
                            <span class="text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Number') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-right text-slate-600 dark:text-slate-300">
                                {{ $account->login }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-3">
                            <span class="text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Account Type') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-right text-slate-600 dark:text-slate-300">
                                {{ $account->account_type }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-3">
                            <span class="text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Platform') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-slate-600 dark:text-slate-300">
                                {{ __('MT5') }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-3">
                            <span class="text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Server') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-slate-600 dark:text-slate-300">
                                {{ $account->server }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-3">
                            <span class="text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Balance') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-slate-600 dark:text-slate-300">
                                {{ get_mt5_account_balance($account->login) }} {{ $account->currency }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-3">
                            <span class="text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Leverage') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-slate-600 dark:text-slate-300">
                                {{ $account->leverage }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-3">
                            <span class="text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Equity') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-sm text-right text-slate-600 dark:text-slate-300">
                                {{ get_mt5_account_equity($account->login) }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="flex flex-col gap-3 list-view-layout p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <div>
                            <x-badge variant="light" style="light" size="sm">
                                {{ ucfirst(data_get($account,'account_type')) }}
                            </x-badge>
                            <x-badge variant="light" style="light" size="sm" class="mx-1">
                                {{ __('MT5') }}
                            </x-badge>
                            <x-badge variant="light" style="light" size="sm" class="mr-1">
                                {{ $account->schema->title }}
                            </x-badge>
                        </div>
                        <h6 class="text-base font-medium text-gray-700 dark:text-gray-400">
                            {{ $account->account_name }} / {{ $account->login }}
                        </h6>
                    </div>
                    <div class="flex flex-wrap gap-3 justify-between items-center mt-5">
                        <p class="account-balance mb-0 text-gray-800 dark:text-white/90">
                            <span class="text-3xl font-medium">{{ get_mt5_account_balance($account->login) }}</span>
                            <span>{{ $account->currency }}</span>
                        </p>
                        <div class="action-btns flex items-center gap-3">
                            <a href="{{ route('user.deposit.methods') }}" class="flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white p-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                <i data-lucide="download" class="hidden sm:block w-5"></i>
                                <span>{{ __('Deposit') }}</span>
                            </a>
                            <a href="{{ route('user.withdraw.view') }}" class="flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white p-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                <i data-lucide="upload" class="hidden sm:block w-5"></i>
                                <span>{{ __('Withdraw') }}</span>
                            </a>
                            <a href="javascript:;" class="inline-flex items-center items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                <i data-lucide="chart-candlestick" class="hidden sm:block w-5"></i>
                                <span>{{ __('Trade') }}</span>
                            </a>
                            @include('frontend::.user.forex.dropdown-menu')
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
