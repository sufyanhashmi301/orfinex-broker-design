@extends('frontend::layouts.user')
@section('title')
    {{ __('Fund Plans') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            Money well funded
        </h4>
        <div class="">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-slate-600 dark:text-slate-100 font-Inter font-normal">Challenge Funding</span>
                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                    <input type="checkbox" value="" checked="checked" class="sr-only peer">
                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                </label>
                <span class="text-sm text-slate-600 dark:text-slate-100 font-Inter font-normal">Direct Funding</span>
            </div>
        </div>
    </div>
    <div class="card p-4 shadow rounded-xl mb-3">
        <div class="tab-content" id="plans-tab-content">
            <div class="tab-pane fade show active" id="challenge-tab-pane" role="tabpanel" aria-labelledby="challenge-tab">
                <div class="grid md:grid-cols-2 grid-cols-1">
                    <div class="text-center space-x-3 space-y-3 md:space-y-0">
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center challenge-btn active" data-challenge="two_step_challenge" id="step-challenge__2">
                            2 Step Challenge
                        </button>
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center challenge-btn" data-challenge="single_step_challenge" id="step-challenge__1">
                            1 Step Challenge
                        </button>
                    </div>
                    <div class="text-center space-x-3 space-y-3 md:space-y-0" id="phaseButtons" style="">
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center phase-btn active" data-phase="1">
                            Phase 1
                        </button>
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center phase-btn" data-phase="2">
                            Phase 2
                        </button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="direct-tab-pane" role="tabpanel" aria-labelledby="direct-tab">
                <div class="text-center md:text-start space-x-3 space-y-3 md:space-y-0">
                    <button class="btn btn-sm btn-outline-dark leverage-btn active" data-leverage="5">
                        Leverage 1:5
                    </button>
                    <button class="btn btn-sm btn-outline-dark leverage-btn" data-leverage="10">
                        Leverage 1:10
                    </button>
                    <button class="btn btn-sm btn-outline-dark leverage-btn" data-leverage="20">
                        Leverage 1:20
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="append-data">
        <div class="relative hidden md:block">
            <div class="payment-plans flex flex-col w-full relative">
                <div class="payment-plans-head mb-3">
                    <div class="payment-plans-row flex gap-3 items-end w-full">
                        <div class="payment-plans-cell w-1/5"></div>
                        <div class="card payment-plans-cell flex flex-col justify-center w-1/5 text-center rounded-xl p-4 bg-white">
                            <h5 class="title text-slate-900 dark:text-white">Beginner</h5>
                            <div class="pricing-amount">
                                <div class="amount text-slate-600 dark:text-slate-100 mb-2">
                                    <strike>$597</strike> /<span> $478</span>
                                </div>
                                <form class="form" action="" method="POST" id="funded-scheme-form">
                                    <a href="javascript:void(0);" data-url="" class="btn btn-sm btn-dark inline-flex items-center justify-center w-full invest-btn">
                                        Get Funded
                                    </a>
                                </form>
                            </div>
                        </div>
                        <div class="card payment-plans-cell flex flex-col justify-center w-1/5 text-center rounded-xl p-4 bg-white">
                            <h5 class="title text-slate-900 dark:text-white">Skilled</h5>
                            <div class="pricing-amount">
                                <div class="amount text-slate-600 dark:text-slate-100 mb-2">
                                    <strike>$1017</strike> /<span> $814</span>
                                </div>
                                <form class="form" action="" method="POST" id="funded-scheme-form">
                                    <a href="javascript:void(0);" data-url="" class="btn btn-sm btn-dark inline-flex items-center justify-center w-full invest-btn">
                                        Get Funded
                                    </a>
                                </form>
                            </div>
                        </div>
                        <div class="card payment-plans-cell flex flex-col justify-center w-1/5 text-center rounded-xl p-4 bg-white">
                            <h5 class="title text-slate-900 dark:text-white">Specialist</h5>
                            <div class="pricing-amount">
                                <div class="amount text-slate-600 dark:text-slate-100 mb-2">
                                    <strike>$1497</strike> /<span> $1198</span>
                                </div>
                                <form class="form" action="" method="POST" id="funded-scheme-form">
                                    <a href="javascript:void(0);" data-url="" class="btn btn-sm btn-dark inline-flex items-center justify-center w-full invest-btn">
                                        Get Funded
                                    </a>
                                </form>
                            </div>
                        </div>
                        <div class="card payment-plans-cell flex flex-col justify-center w-1/5 text-center rounded-xl p-4 bg-white">
                            <h5 class="title text-slate-900 dark:text-white">Champion</h5>
                            <div class="pricing-amount">
                                <div class="amount text-slate-600 dark:text-slate-100 mb-2">
                                    <strike>$2397</strike> /<span> $1918</span>
                                </div>
                                <form class="form" action="" method="POST" id="funded-scheme-form">
                                    <a href="javascript:void(0);" data-url="" class="btn btn-sm btn-dark inline-flex items-center justify-center w-full invest-btn">
                                        Get Funded
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card payment-plans-body">
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
                                    10,000 $
                                    <span class=""></span>
                                </span>
                            </div>
                            <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                                <span>
                                    25,000 $
                                    <span class=""></span>
                                </span>
                            </div>
                            <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                                <span>50,000 $</span>
                            </div>
                            <div class="payment-plans-cell text-slate-600 dark:text-slate-100 w-1/5 text-center px-3">
                                <span>100,000 $</span>
                            </div>
                        </div>
                        <div class="plan-details text-slate-600 dark:text-slate-100 p-4 hidden">
                            <span>Buying power refers to the maximum amount of margin that can be utilized, serving as an
                                indicator of one's leverage potential. It provides insight into the extent to which an
                                individual or entity can increase their purchasing or funded capacity through borrowed
                                funds.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection