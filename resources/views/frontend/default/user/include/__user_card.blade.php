<div class="grid grid-cols-12 gap-5 mb-5">
    <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
        <div class="bg-no-repeat bg-cover bg-center p-5 rounded-[6px] relative flex items-center"
             style="background-image: url({{ asset('frontend/images/all-img/widget-bg-3.png') }})">
            <div class="flex-1">
                <h4 class="text-xl font-medium text-white mb-1">
                    <span class="block font-normal">Hello!</span>
                    <span class="block">{{auth()->user()->full_name}}</span>
                </h4>
                @if(setting('user_ranking', 'permission',false))
                    <div class="flex items-center text-sm text-white text-opacity-80">
                        <iconify-icon class="text-xl mr-1" icon="heroicons:check-badge-16-solid"></iconify-icon>
                        <span>Your Rank: {{ $user->rank->ranking }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-none">
                <a href="{{ route('user.ranking-badge') }}" class="btn-light bg-white btn-sm btn">Details</a>
            </div>
        </div>
    </div>
    <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
        <div class="p-4 card">
            <div class="grid md:grid-cols-3 col-span-1 gap-4">
                <!-- BEGIN: Group Chart2 -->
                <div class="py-[18px] px-4 rounded-[6px] bg-[#E5F9FF] dark:bg-slate-900	 ">
                    <div class="flex items-center space-x-6 rtl:space-x-reverse">
                        <div class="flex-none">
                            <div id="wline1"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                Balance
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium">
                                {{$dataCount['total_forex_balance']}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-[18px] px-4 rounded-[6px] bg-[#FFEDE5] dark:bg-slate-900	 ">
                    <div class="flex items-center space-x-6 rtl:space-x-reverse">
                        <div class="flex-none">
                            <div id="wline2"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                Equity
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium">
                                {{$dataCount['total_forex_equity']}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-[18px] px-4 rounded-[6px] bg-[#EAE5FF] dark:bg-slate-900	 ">
                    <div class="flex items-center space-x-6 rtl:space-x-reverse">
                        <div class="flex-none">
                            <div id="wline3"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                Success Points
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium">
                                0
                            </div>
                        </div>
                    </div>
                </div>

                <!-- END: Group Chart2 -->
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
