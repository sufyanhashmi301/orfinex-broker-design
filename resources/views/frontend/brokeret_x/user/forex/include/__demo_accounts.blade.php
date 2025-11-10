@if(count($demoForexAccounts) == 0)
    <x-frontend::empty-state icon="inbox">
        <x-slot name="subtitle">
            {{ __('You don\'t have any Demo account.') }}
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
        @foreach($demoForexAccounts as $account)
            <div class="rounded-lg border border-gray-200 dark:border-gray-800 trading-account-card" 
                 x-bind:class="viewMode === 'list' ? 'w-full' : 'lg:h-full'">
                <div class="grid-view-layout">
                    <div class="flex items-center justify-between border-b dark:border-slate-700 p-3">
                        <div class="flex items-center gap-2">
                            <x-frontend::badge variant="light" style="light" size="sm">
                                {{ $account->schema->title }}
                            </x-frontend::badge>
                            <h5 class="text-theme-sm tracking-wide font-medium mb-0 dark:text-white/90">{{ $account->account_name }}</h5>
                        </div>
                        <div x-data="{openDropDown: false}" class="dropdown-menu relative leading-none h-fit">
                            <x-frontend::forms.button @click="openDropDown = !openDropDown" type="button" variant="secondary" icon="more-vertical" iconOnly />
                            @include('frontend::user.forex.dropdown-menu')
                        </div>
                    </div>
                    <ul class="h-full p-3">
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ __('Number') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-right text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ $account->login }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ __('Account Type') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-right text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ $account->account_type }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ __('Platform') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-theme-sm tracking-wide text-right text-gray-600 dark:text-gray-300">
                                {{ __('MT5') }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ __('Server') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-theme-sm tracking-wide text-right text-gray-600 dark:text-gray-300">
                                {{ $account->server }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ __('Balance') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-theme-sm tracking-wide text-right text-gray-600 dark:text-gray-300">
                                {{ get_mt5_account_balance($account->login) }} {{ $account->currency }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ __('Leverage') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-theme-sm tracking-wide text-right text-gray-600 dark:text-gray-300">
                                {{ $account->leverage }}
                            </span>
                        </li>
                        <li class="flex items-baseline relative overflow-hidden py-2.5">
                            <span class="text-theme-sm tracking-wide text-gray-600 dark:text-gray-300">
                                {{ __('Equity') }}
                            </span>
                            <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                            <span class="text-theme-sm tracking-wide text-right text-gray-600 dark:text-gray-300">
                                {{ get_mt5_account_equity($account->login) }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="flex flex-col gap-3 list-view-layout p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <div>
                            <x-frontend::badge variant="light" style="light" size="sm">
                                {{ ucfirst(data_get($account,'account_type')) }}
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
                    <div class="flex flex-col sm:flex-row gap-3 gap-y-5 sm:justify-between sm:items-center mt-5">
                        <p class="account-balance mb-0 text-gray-800 dark:text-white/90">
                            <span class="text-3xl font-medium">{{ get_mt5_account_balance($account->login) }}</span>
                            <span>{{ $account->currency }}</span>
                        </p>

                        <div class="sm:hidden">
                            <div class="action-btns flex items-center justify-between gap-3">
                                <button type="button" class="flex flex-col items-center gap-2 text-theme-sm"
                                    @click.prevent="$store.modals.open('depositDemo', {
                                        login: '{{ $account->login }}'
                                    })">
                                    <div class="w-12 h-12 rounded-full bg-brand-500 flex items-center justify-center">
                                        <i data-lucide="download" class="w-5"></i>
                                    </div>
                                    {{ __('Set Balance') }}
                                </button>
                                <a href="javascript:;" class="flex flex-col items-center gap-2 text-theme-sm">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <i data-lucide="chart-candlestick" class="w-5"></i>
                                    </div>
                                    {{ __('Trade') }}
                                </a>
                                <div x-data="{openDropDown: false}" class="dropdown-menu relative leading-none h-fit">
                                    <button @click="openDropDown = !openDropDown" type="button" class="flex flex-col items-center gap-2 text-theme-sm">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <i data-lucide="more-vertical" class="w-5"></i>
                                        </div>
                                        {{ __('More') }}
                                    </button>
                                    @include('frontend::user.forex.dropdown-menu')
                                </div>
                            </div>
                        </div>

                        <div class="hidden sm:block">
                            <div class="action-btns flex items-center gap-3">
                                <x-frontend::forms.button type="button" variant="primary" size="md" icon="download" iconPosition="left"
                                    @click.prevent="$store.modals.open('depositDemo', {
                                        login: '{{ $account->login }}'
                                    })">
                                    {{ __('Set Balance') }}
                                </x-frontend::forms.button>
                                <x-frontend::link-button href="javascript:;" variant="outline" size="md" icon="chart-candlestick" iconPosition="left">
                                    {{ __('Trade') }}
                                </x-frontend::link-button>
                                <div x-data="{openDropDown: false}" class="dropdown-menu relative leading-none h-fit">
                                    <x-frontend::forms.button @click="openDropDown = !openDropDown" type="button" variant="secondary" icon="more-vertical" iconOnly />
                                    @include('frontend::user.forex.dropdown-menu')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
