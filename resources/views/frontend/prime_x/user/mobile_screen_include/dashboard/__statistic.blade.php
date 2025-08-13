<div class="card all-feature-mobile mb-3 mobile-screen-show">
    <div class="card-header">
        <h4 class="card-title">{{ __('All Statistic') }}</h4>
    </div>
    <div class="card-body p-3">
        <div class="col-12">
            <div class="all-cards-mobile">
                <div class="contents space-y-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                    <iconify-icon class="text-xl" icon="lucide:arrow-left-right"></iconify-icon>
                                </div>
                                <div class="flex-1 content">
                                    <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('All Transactions') }}</div>
                                    <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $dataCount['total_transaction'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                    <iconify-icon class="text-xl" icon="lucide:download"></iconify-icon>
                                </div>
                                <div class="flex-1 content">
                                    <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Total Deposit') }}</div>
                                    <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $currencySymbol }} {{ $dataCount['total_deposit'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                    <iconify-icon class="text-xl" icon="lucide:box"></iconify-icon>
                                </div>
                                <div class="flex-1 content">
                                    <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Total Investment') }}</div>
                                    <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $currencySymbol }} {{ $dataCount['total_investment'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="moretext-2 hidden">
                    <div class="contents space-y-3">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:credit-card"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Total Profit') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium"> {{ $currencySymbol }} {{ $dataCount['total_profit'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:log-in"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Total Transfer') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $currencySymbol }} {{ $dataCount['total_transfer'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:send"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Total Withdraw') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium"> {{ $currencySymbol }} {{ $dataCount['total_withdraw'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:users-2"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Referral Bonus') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium"> {{ $currencySymbol }} {{ $dataCount['total_referral_profit'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:anchor"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Deposit Bonus') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $currencySymbol }} {{ $dataCount['deposit_bonus'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:archive"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Investment Bonus') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $currencySymbol }} {{ $dataCount['investment_bonus'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:gift"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Total Referral') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $dataCount['total_referral'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:award"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Rank Achieved') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $dataCount['rank_achieved'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-[#ff0000] text-white">
                                        <iconify-icon class="text-xl" icon="lucide:alert-triangle"></iconify-icon>
                                    </div>
                                    <div class="flex-1 content">
                                        <div class="text-slate-600 dark:text-slate-100 text-sm mb-1 font-medium">{{ __('Total Ticket') }}</div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">{{ $dataCount['total_ticket'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="centered text-center mt-4">
                    <button class="moreless-button-2 btn btn-sm btn-dark">{{ __('Load more') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
