<div class="relative hidden md:block">
    <div class="payment-plans flex flex-col w-full relative">
        <div class="payment-plans-head mb-3">
            <div class="payment-plans-row flex gap-3 items-end w-full">
                <div class="payment-plans-cell w-1/5"></div>
                @foreach($plans as $plan)
                    {{-- {{ dd($plan) }} --}}
                    <div class="card payment-plans-cell flex flex-col justify-center w-1/5 text-center rounded-xl p-4 bg-white">
                        <h5 class="title text-slate-900 dark:text-white">
                            {{ data_get($plan,'name') }}
                        </h5>
                        <div class="pricing-amount">
                            <div class="amount text-slate-600 dark:text-slate-100 mb-2">
                                @if($plan->is_discount == 1 && $plan->discount_price > 0)
                                    <strike>${{data_get($plan,'amount')}}</strike> /<span> ${{data_get($plan,'discount_price')}}</span>
                                @else
                                    ${{data_get($plan,'amount')}}
                                @endif
                            </div>
                            <form class="form" action="{{ route('user.pricing.invest',the_hash(data_get($plan, 'id')))  }}" method="POST" id="funded-scheme-form">
                                @csrf
                                <input type="hidden" class="funded-main-type" id="funded-main-type" name="funded_main_type" value="challenge">
                                <input type="hidden" class="funded-sub-type" id="funded-sub-type" name="funded_sub_type" value="two_step_challenge">
                                <input type="hidden" class="funded-stage" id="funded-stage" name="funded_stage" value="1">
                                <a href="javascript:void(0);"
                                data-url="{{ route('user.pricing.invest',the_hash(data_get($plan, 'id')))  }}"
                                class="btn btn-sm btn-dark inline-flex items-center invest-btn">
                                    Get Funded
                                </a>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card payment-plans-body divide-y divide-slate-100 dark:divide-slate-700">
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] cursor-pointer hover:shadow-md">
                    <div class="payment-plans-cell w-1/5 px-3">
                        <div class="flex items-center text-slate-900 dark:text-white gap-2">
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Allotted Fund
                        </div>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->amount_allotted, base_currency()) : 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->amount_allotted, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->amount_allotted, base_currency()) : 0}} $</span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->amount_allotted, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>Buying power refers to the maximum amount of margin that can be utilized, serving as an indicator of one's leverage potential. It provides insight into the extent to which an individual or entity can increase their purchasing or funded capacity through borrowed funds.</span>
                </div>
            </div>

            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] cursor-pointer hover:shadow-md">
                    <div class="payment-plans-cell w-1/5 px-3">
                        <div class="flex items-center text-slate-900 dark:text-white gap-2">
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Profit Target
                        </div>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->profit_target, base_currency()) : 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->profit_target, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->profit_target, base_currency()) : 0}} $</span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->profit_target, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>
                        At Orfinex Fund, you can get funded by reaching a small profit target in the challenge phase. For example: If you sign up for a 10K 1-Step challenge, your profit target will be 10%. Similarly, if you sign up for a 10K  2-Step challenge, your profit target for Phase 1 will be 10%. For different funding models the percentage of profit target is different. After achieving the profit target, you will be able to start trading in your Orfinex Fund Account.
                    </span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] cursor-pointer hover:shadow-md">
                    <div class="payment-plans-cell w-1/5 px-3">
                        <div class="flex items-center text-slate-900 dark:text-white gap-2">
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Daily Max Loss
                        </div>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->daily_drawdown_limit, base_currency()) : 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->daily_drawdown_limit, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->daily_drawdown_limit, base_currency()) : 0}} $</span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->daily_drawdown_limit, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>
                        Our daily loss limit specifies that you are permitted to lose 5% of your initial account balance.

                        So here is how the calculation works:

                        Current daily loss = results of closed positions of this day + result of open positions.

                        This implies that on that specific day, this number should not exceed 5% of your initial account balance.

                        For example, if you have a $100,000 account, the maximum daily loss limit is $5,000, and you are not allowed to lose more than $5,000 on any given day. Suppose you have lost a total of $3000 in your closed trades. In such a case, you cannot lose more than $2000, including floating losses. Note that swap and commission rates are included in this calculation. If you lose more than $2000, it will be considered a violation.

                        Similarly, let's say you gained $5,000 in profit in a single day. In such a case, you will be allowed to lose $5,000 (your profit) + $5,000 (your daily loss limit) = $10,000. Losing more than $10,000 (in both open and closed positions) will be considered a violation.

                        Let's look at another case. Assume, like in the previous scenario, that you lost $3000 in a single day. Then you take a trade that is now running at a floating loss of -$2500, but when you close it, it becomes positive $500. If this occurs, according to our calculations, you will have exceeded your daily loss limit the instant your loss exceeds $5000.

                        Another thing to keep in mind is that your daily loss limit will be reset at midnight according to the server time. As an example, suppose you have gained a $2000 profit in a closed trade and you have a trade that is currently running in -$6000 floating loss. In this case, you still have not violated your daily drawdown for the day because your current daily loss is +$2000 - $6000= -$4000. However, if you hold this one trade with a floating loss of $6000 after midnight, you will breach the daily loss limit because a new day begins after midnight and you have already lost $6000, which is more than your daily loss limit of $5000.
                    </span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] cursor-pointer hover:shadow-md">
                    <div class="payment-plans-cell w-1/5 px-3">
                        <div class="flex items-center text-slate-900 dark:text-white gap-2">
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Maximum Loss
                        </div>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->max_drawdown_limit, base_currency()) : 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->max_drawdown_limit, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->max_drawdown_limit, base_currency()) : 0}}$</span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->max_drawdown_limit, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>
                        The equity of the trading account must not, at any moment during the account duration, decline below 90% of the initial account balance. For an Orfinex Fund account with a balance of $100,000, it means that the account lowest possible equity can be $90,000. Again, this is a sum of both closed and open positions (account equity, not balance). The logic of the calculation is the same as with the Maximum Daily Loss; the only difference is that it’s not limited to one day but the entire duration of the testing period. The limit is inclusive of commissions and swaps. 10% of the initial account balance gives the trader enough space to prove that his/her account is suitable for the investment. It is a buffer that should keep the trader in the game even if there were some initial losses. The investor has an assurance that the trader’s account cannot decline below 90% of its value under any circumstance.
                    </span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] cursor-pointer hover:shadow-md">
                    <div class="payment-plans-cell w-1/5 px-3">
                        <div class="flex items-center text-slate-900 dark:text-white gap-2">
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Weekend Holding
                        </div>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            No
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            Yes
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>Yes</span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>Yes</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>
                        With Orfinex Fund you can hold any trading during the weekend.
                    </span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] cursor-pointer hover:shadow-md">
                    <div class="payment-plans-cell w-1/5 px-3">
                        <div class="flex items-center text-slate-900 dark:text-white gap-2">
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Scaleable Upto
                        </div>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            Not Applicable
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            Not Applicable
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>3,50,000 $</span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>7,00,000 $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>Scale Up refers to the process in which Orfinex, increases the funded program of a user who is growing their fund and retaining the earned profit within the account. Orfinex adds an amount equal to the allotted amount, up to a maximum scaling level of 700,000 USD, to support the user's expanding trading activities.</span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] cursor-pointer hover:shadow-md">
                    <div class="payment-plans-cell w-1/5 px-3">
                        <div class="flex items-center text-slate-900 dark:text-white gap-2">
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Refundable Fee *
                        </div>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            Yes
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>
                            Yes
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>Yes</span>
                    </div>
                    <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                        <span>Yes</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white w-full p-8">
                    <span>The fee is reimbursed to you with the first Profit Split when you become an Orfinex Fund Trader.</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="nk-block pt-0 relative md:hidden">
    <div class="payment-plans flex flex-col relative w-full">
        <div class="payment-plans-head mb-3">
            <div class="payment-plans-row flex items-center w-full h-[65px] flex items-center w-full h-[65px] planSlider">
                <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto w-1/2 h-full py-5"></div>
                @foreach($plans as $plan)
                    <div
                        class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide @if($loop->first) first current @elseif($loop->last) last @endif @if(data_get($plan,'is_highlighted')) bg-primary relative @endif">
                        @if(data_get($plan,'is_highlighted'))
                            <span class="text-primary p-1 d-block absolute plan-rec">Recommended</span>
                        @endif
                        <h5 class="title @if(data_get($plan,'is_highlighted')) text-white @endif">{{data_get($plan,'name')}}</h5>

                        <div class="pricing-amount">
                            <div class="amount @if(data_get($plan,'is_highlighted')) text-white @endif mb-2">
                                @if($plan->is_discount == 1 && $plan->discount_price > 0)
                                    <strike>${{data_get($plan,'amount')}}</strike> /<span> ${{data_get($plan,'discount_price')}}</span>
                                @else
                                    ${{data_get($plan,'amount')}}
                                @endif
                            </div>
                            <form class="form"
                                  action="{{ route('user.pricing.invest',the_hash(data_get($plan, 'id')))  }}"
                                  method="POST" id="funded-scheme-form">
                                @csrf
                                <input type="hidden" class="funded-main-type" id="funded-main-type" name="funded_main_type" value="challenge">
                                <input type="hidden" class="funded-sub-type" id="funded-sub-type" name="funded_sub_type"
                                       value="two_step_challenge">
                                <input type="hidden" class="funded-stage" id="funded-stage" name="funded_stage" value="1">
                                <a href="javascript:void(0);"
                                   data-url="{{ route('user.pricing.invest',the_hash(data_get($plan, 'id')))  }}"
                                   class="btn btn-block @if(data_get($plan,'is_highlighted')) btn-dark @else btn-primary @endif invest-btn">
                                    Get Funded
                                </a>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="payment-plans-body">
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] planSlider">
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto w-1/2 h-full py-5">
                        <span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Allotted Fund
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide first current">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->amount_allotted, base_currency()) : 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->amount_allotted, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->amount_allotted, base_currency()) : 0}} $</span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide last">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->amount_allotted, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>Buying power refers to the maximum amount of margin that can be utilized, serving as an indicator of one's leverage potential. It provides insight into the extent to which an individual or entity can increase their purchasing or funded capacity through borrowed funds.</span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] planSlider">
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto w-1/2 h-full py-5">
                        <span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Buying Power
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide first current">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->amount_allotted, base_currency()): 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->amount_allotted, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->amount_allotted, base_currency()) : 0}} $</span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide last">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->amount_allotted, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>Buying power refers to the maximum amount of margin that can be utilized, serving as an indicator of one's leverage potential. It provides insight into the extent to which an individual or entity can increase their purchasing or funded capacity through borrowed funds.</span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] planSlider">
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto w-1/2 h-full py-5">
                        <span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Profit Target
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide first current">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->profit_target, base_currency()) : 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->profit_target, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->profit_target, base_currency()) : 0}} $</span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide last">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->profit_target, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>In order to make a withdrawal from a Funded Account, users are typically required to achieve a specific profit target set by the account provider. Once the profit target has been reached, the withdrawal section becomes accessible, allowing the user to split the profits between themselves and the account provider.</span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] planSlider">
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto w-1/2 h-full py-5">
                        <span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Daily Max Loss
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide first current">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->daily_drawdown_limit, base_currency()) : 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->daily_drawdown_limit, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->daily_drawdown_limit, base_currency()) : 0}} $</span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide last">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->daily_drawdown_limit, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>The Daily Maximum Loss refers to the predetermined amount of loss allowed on a funded account within a single day. This limit is typically calculated based on the equity of the account from the previous day, and it is assessed and reset at midnight.</span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] planSlider">
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto w-1/2 h-full py-5">
                        <span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Maximum Loss
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide first current">
                        <span>
                            {{ isset($plans[0]) ? amount($plans[0]->max_drawdown_limit, base_currency()) : 0}} $
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>
                            {{ isset($plans[1]) ? amount($plans[1]->max_drawdown_limit, base_currency()) : 0}} $
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>{{ isset($plans[2]) ? amount($plans[2]->max_drawdown_limit, base_currency()) : 0}}$</span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide last">
                        <span>{{ isset($plans[3]) ? amount($plans[3]->max_drawdown_limit, base_currency()) : 0}} $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>The Maximum Loss refers to the predetermined limit on the total allowable loss for a funded account. This limit is typically calculated based on the highest recorded balance level in the account.</span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] planSlider">
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto w-1/2 h-full py-5">
                        <span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Weekend Holding
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide first current">
                        <span>
                            No
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>
                            Yes
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>Yes</span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide last">
                        <span>Yes</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>Weekend holding refers to the practice of allowing traders to keep a trade open from Friday to the following week, specifically until Monday. It determines whether holding a trade over the weekend is permitted or restricted by the trading platform or broker.</span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] planSlider">
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto w-1/2 h-full py-5">
                        <span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Scaleable Upto
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide first current">
                        <span>
                            Not Applicable
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>
                            Not Applicable
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>3,50,000 $</span>
                    </div>
                    <div class="payment-plans-cell border-b flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide last">
                        <span>7,00,000 $</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white border-b w-full p-8">
                    <span>Scale Up refers to the process in which Orfinex, increases the funded program of a user who is growing their fund and retaining the earned profit within the account. Orfinex adds an amount equal to the allotted amount, up to a maximum scaling level of 700,000 USD, to support the user's expanding trading activities.</span>
                </div>
            </div>
            <div class="payment-plans-row__container">
                <div class="payment-plans-row flex items-center w-full h-[65px] planSlider">
                    <div class="payment-plans-cell flex flex-col justify-center flex-auto w-1/2 h-full py-5">
                        <span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            Refundable Fee *
                        </span>
                    </div>
                    <div class="payment-plans-cell flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide first current">
                        <span>
                            No
                            <span class="" class="extension"></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>
                            No
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="payment-plans-cell flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide">
                        <span>Yes</span>
                    </div>
                    <div class="payment-plans-cell flex flex-col justify-center flex-auto text-center w-1/2 h-full py-5 planSlide last">
                        <span>Yes</span>
                    </div>
                </div>
                <div class="plan-details hidden bg-white w-full p-8">
                    <span>The service fee charged for a funding program will be reimbursed to the user during the 5th Profit Share Split. This means that after a certain number of profit sharing splits, the user will receive a refund of the service fee they initially paid.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="planSlideBtns">
        <a href="javascript:;" id="prev" class="btn btn-sm btn-dark inline-flex items-center !absolute top-1/2 -left-5">
            <iconify-icon icon="lucide:arrow-left"></iconify-icon>
        </a>
        <a href="javascript:;" id="next" class="btn btn-sm btn-dark inline-flex items-center !absolute top-1/2 -right-5">
            <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
    </div>
</div>
