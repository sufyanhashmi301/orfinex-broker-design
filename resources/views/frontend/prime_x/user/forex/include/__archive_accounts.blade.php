@if(count($archiveForexAccounts) == 0)
    <div class="card basicTable_wrapper items-center justify-center py-10 px-10">
        <div class="flex items-center justify-center flex-col gap-3">
            <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                {{ __("You don't have any Violated account.") }}
            </p>
        </div>
    </div>
@else
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach($archiveForexAccounts as $account)
        <div class="card lg:h-full border dark:border-slate-700 trading-account-card">
            <div class="card-body rounded-md p-6">
                <div class="grid-view-layout">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="mb-0 dark:text-white">{{$account->account_name}}</h5>
                        @include('frontend::.user.forex.dropdown-menu')
                    </div>
                    <ul class="divide-y divide-slate-100 dark:divide-slate-700 h-full">
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Number') }}
                            </span>
                            <span class="flex-1 text-right text-slate-600 dark:text-slate-300">
                                {{$account->login}}
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Platform') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                                {{ __('MT5') }}
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Balance') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                                {{get_mt5_account_balance($account->login)}} {{$account->currency}}
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Leverage') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                                {{$account->leverage}}
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Equity') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                                {{get_mt5_account_equity($account->login)}}
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="list-view-layout">
                    <div class="flex items-center">
                        <span class="badge bg-primary text-primary-900 bg-opacity-30 capitalize">
                            {{ucfirst(data_get($account,'account_type'))}}
                        </span>
                        <span class="badge bg-secondary-500 text-secondary-900 bg-opacity-30 capitalize mx-1">MT5</span>
                        <span class="badge bg-secondary-500 text-secondary-900 bg-opacity-30 capitalize mr-1">
                            {{$account->schema->title}}
                        </span>
                        <h6 class="mb-0">
                            {{$account->account_name}} / {{$account->login}}
                        </h6>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <p class="account-balance mb-0 dark:text-white">
                            <span class="text-lg font-semibold">{{$account->balance}}</span>
                            <span>{{$account->currency}}</span>
                        </p>
                        <div class="action-btns flex items-center gap-3">
                            <a href="{{route('user.deposit.amount')}}" class="btn btn-sm btn-outline-dark mt-0">
                                <span class="flex items-center">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="octicon:download-16"></iconify-icon>
                                    <span>{{ __('Deposit') }}</span>
                                </span>
                            </a>
                            <a href="{{route('user.withdraw.view')}}" class="btn btn-sm btn-outline-dark mt-0">
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
