<div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 place-content-center mb-5">
    <div class="card">
        <div class="card-body p-6">
            <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                Balance
            </div>
            <div class="flex items-center text-slate-900 dark:text-white text-2xl font-medium">
                $<span>{{$dataCount['total_forex_balance']}}</span>
                <span class="text-sm text-success-500 ml-1">+452%</span>
            </div>
            <div class="">
                <div id="balance-chart" class="w-full"></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                Equity
            </div>
            <div class="text-slate-900 dark:text-white text-2xl font-medium">
                $<span>{{$dataCount['total_forex_equity']}}</span>
                <span class="text-sm text-success-500 ml-1">+452%</span>
            </div>
            <div class="">
                <div id="equity-chart"></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                Rewards
            </div>
            <div class="text-slate-900 dark:text-white text-2xl font-medium">
                <span>0</span>
                <span class="text-sm text-success-500 ml-1">+452%</span>
            </div>
            <div class="">
                <div id="success-points"></div>
            </div>
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
