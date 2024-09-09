<div class="card overflow-hidden mb-3">
    <div class="card-body py-1">
        <div class="grid md:grid-cols-4 col-span-1 gap-px bg-slate-100 dark:bg-slate-700">
            <div class="bg-white dark:bg-dark">
                <div class="flex flex-wrap justify-between items-baseline gap-y-2 gap-x-4 p-4">
                    <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Balance') }}
                    </div>
                    <div class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                        {{ __('Challenge') }}
                    </div>
                    <div class="w-full text-slate-900 dark:text-white text-2xl font-medium">
                        {{$dataCount['total_forex_balance']}}
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-dark">
                <div class="flex flex-wrap justify-between items-baseline gap-y-2 gap-x-4 p-4">
                    <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Equity') }}
                    </div>
                    <div class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                        {{ __('Challenge') }}
                    </div>
                    <div class="w-full text-slate-900 dark:text-white text-2xl font-medium">
                        {{$dataCount['total_forex_equity']}}
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-dark">
                <div class="flex flex-wrap justify-between items-baseline gap-y-2 gap-x-4 p-4">
                    <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Balance') }}
                    </div>
                    <div class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                        {{ __('Funded') }}
                    </div>
                    <div class="w-full text-slate-900 dark:text-white text-2xl font-medium">
                        0
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-dark">
                <div class="flex flex-wrap justify-between items-baseline gap-y-2 gap-x-4 p-4">
                    <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Equity') }}
                    </div>
                    <div class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                        {{ __('Funded') }}
                    </div>
                    <div class="w-full text-slate-900 dark:text-white text-2xl font-medium">
                        0
                    </div>
                </div>
            </div>
            <!-- END: Group Chart2 -->
        </div>
    </div>
</div>

<div class="row user-cards hidden">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-inbox"></i></div>
            <div class="content">
                <h4><span class="count">{{ $dataCount['total_transaction'] }}</span></h4>
                <p>{{ __('All Transactionssss') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-file-add"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_deposit'] }}</span></h4>
                <p>{{ __('Total Deposit') }}</p>
            </div>
        </div>
    </div>
    {{--    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">--}}
    {{--        <div class="single">--}}
    {{--            <div class="icon"><i class="anticon anticon-check-square"></i></div>--}}
    {{--            <div class="content">--}}
    {{--                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_investment'] }}</span></h4>--}}
    {{--                <p>{{ __('Total Investment') }}</p>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">--}}
    {{--        <div class="single">--}}
    {{--            <div class="icon"><i class="anticon anticon-credit-card"></i></div>--}}
    {{--            <div class="content">--}}
    {{--                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_profit'] }}</span></h4>--}}
    {{--                <p>{{ __('Total Profit') }}</p>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">--}}
    {{--        <div class="single">--}}
    {{--            <div class="icon"><i class="anticon anticon-arrow-right"></i></div>--}}
    {{--            <div class="content">--}}
    {{--                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_transfer'] }}</span></h4>--}}
    {{--                <p>{{ __('Total Transfer ') }}</p>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-money-collect"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_withdraw'] }}</span></h4>
                <p>{{ __('Total Withdraw') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-gift"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_referral_profit'] }}</span>
                </h4>
                <p>{{ __('Partner Bonus') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-account-book"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['deposit_bonus'] }}</span></h4>
                <p>{{ __('Deposit Bonus') }}</p>
            </div>
        </div>
    </div>
    {{--    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">--}}
    {{--        <div class="single">--}}
    {{--            <div class="icon"><i class="anticon anticon-gold"></i></div>--}}
    {{--            <div class="content">--}}
    {{--                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['investment_bonus'] }}</span></h4>--}}
    {{--                <p>{{ __('Investment Bonus') }}</p>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-inbox"></i></div>
            <div class="content">
                <h4 class="count">{{ $dataCount['total_referral'] }}</h4>
                <p>{{ __('Total Partner') }}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-radar-chart"></i></div>
            <div class="content">
                <h4 class="count">{{ $dataCount['rank_achieved'] }}</h4>
                <p>{{ __('Rank Achieved') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-question"></i></div>
            <div class="content">
                <h4 class="count">{{ $dataCount['total_ticket'] }}</h4>
                <p>{{ __('Total Ticket') }}</p>
            </div>
        </div>
    </div>
</div>
