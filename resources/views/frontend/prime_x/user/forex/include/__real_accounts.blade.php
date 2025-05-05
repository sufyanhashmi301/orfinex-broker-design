@if(count($realForexAccounts) == 0)
    <div class="card py-10 px-10">
        <div class="flex items-center justify-center flex-col gap-3">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                {{ __('You don\'t have any Real account.') }}
            </p>
        </div>
    </div>
@else
    <div class="grid xl:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach($realForexAccounts as $account)
            <div class="card lg:h-full border dark:border-slate-700 trading-account-card">
                <div class="card-body">
                    <div class="grid-view-layout">
                        <div class="flex items-center justify-between border-b dark:border-slate-700 p-3">
                            <div class="flex items-center">
                                <h5 class="text-xl mb-0 dark:text-white">{{ $account->account_name }}</h5>
                                <span class="badge badge-primary capitalize ml-2">{{ $account->schema->title }}</span>
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
                    <div class="list-view-layout p-6">
                        <div class="flex items-center">
                            <span class="badge badge-primary  bg-opacity-30 capitalize">
                                {{ ucfirst(data_get($account, 'account_type')) }}
                            </span>
                            <span class="badge bg-secondary-500 text-secondary-900 bg-opacity-30 capitalize mx-1">{{ __('MT5') }}</span>
                            <span class="badge bg-secondary-500 text-secondary-900 bg-opacity-30 capitalize mr-1">
                                {{ $account->schema->title }}
                            </span>
                            <h6 class="mb-0">
                                {{ $account->account_name }} / {{ $account->login }}
                            </h6>
                        </div>
                        <div class="flex justify-between items-center mt-3">
                            <p class="account-balance mb-0 dark:text-white">
                                <span class="text-lg font-semibold">{{ $account->balance }}</span>
                                <span>{{ $account->currency }}</span>
                            </p>
                            <div class="action-btns flex items-center gap-3">
                                <a href="{{ route('user.deposit.methods') }}" class="btn btn-sm btn-outline-dark loaderBtn mt-0">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="octicon:download-16"></iconify-icon>
                                        <span>{{ __('Deposit') }}</span>
                                    </span>
                                </a>
                                <a href="{{ route('user.withdraw.view') }}" class="btn btn-sm btn-outline-dark loaderBtn mt-0">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="octicon:upload-16"></iconify-icon>
                                        <span>{{ __('Withdraw') }}</span>
                                    </span>
                                </a>
                                <a href="javascript:;" class="btn btn-sm btn-dark mt-0">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="tabler:chart-candle"></iconify-icon>
                                        <span>{{ __('Trade') }}</span>
                                    </span>
                                </a>
                                @include('frontend::.user.forex.dropdown-menu')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
