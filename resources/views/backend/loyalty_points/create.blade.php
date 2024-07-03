@extends('backend.layouts.app')
@section('title')
    {{ __('Loyalty Points') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-9 col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Loyalty Points') }}</h4>
                </div>
                <div class="card-body p-6">
                    <form action="" method="post" class="space-y-5">
                        @csrf
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">Points Redeemed (Per USD)</label>
                            <div class="lg:col-span-8 col-span-12">
                                <input type="number" min="0" name="cash_out_redeem" class="form-control" placeholder="" value="100">
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                <strong>Invited Users Points</strong>
                            </label>
                            <div class="lg:col-span-8 col-span-12"></div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">Invited Registered</label>
                            <div class="lg:col-span-8 col-span-12 loy-div">
                                <input type="number" min="0" name="invite_register_loyalty_points" class="form-control" placeholder="" value="20">
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">Invited KYC Verified</label>
                            <div class="lg:col-span-8 col-span-12 loy-div">
                                <input type="number" min="0" name="kyc_verified_loyalty_points" class="form-control" placeholder="" value="20">
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Invited First Deposit Amount(per USD for Points)
                            </label>
                            <div class="lg:col-span-8 col-span-12 loy-div">
                                <input type="number" min="0" name="first_deposit_loyalty_points" class="form-control" placeholder="" value="20">
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">Invited First Deposit Amount(Fixed Points)</label>
                            <div class="lg:col-span-8 col-span-12 loy-div">
                                <input type="number" min="0" name="first_deposit_loyalty_points_fixed" class="form-control" placeholder="" value="">
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Invited First Deposit Amount(Percentage Deposit Points)
                            </label>
                            <div class="lg:col-span-8 col-span-12 loy-div">
                                <input type="number" min="0" name="first_deposit_loyalty_points_percentage" class="form-control" placeholder="" value="">
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label for="" class="lg:col-span-4 col-span-12 form-label">
                                Invited First Deposit Amount(Fixed Points - For above range - 1)
                            </label>
                            <div class="lg:col-span-8 col-span-12">
                                <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                    <div class="site-input-groups">
                                        <label for="" class="form-control-label">Points</label>
                                        <input type="number" min="0" name="first_deposit_loyalty_points_fixed_above_range_1_points" class="form-control" placeholder="" value="">
                                    </div>
                                    <div class="site-input-groups">
                                        <label for="" class="form-control-label">Above range amount</label>
                                        <input type="number" min="0" name="first_deposit_loyalty_points_fixed_above_range_1_range" class="form-control" placeholder="" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Invited Recurring Deposit Amount (per USD for Points)
                            </label>
                            <div class="lg:col-span-8 col-span-12 loy-div">
                                <input type="number" min="0" name="recurring_deposit_loyalty_points" class="form-control" placeholder="" value="5">
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Own Activity Points
                            </label>
                            <div class="lg:col-span-8 col-span-12"></div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Enable My Referral
                            </label>
                            <div class="lg:col-span-8 col-span-12">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" value="" name="enable_my_referal" checked="checked" class="sr-only peer">
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">Enable My Rewards</label>
                            <div class="lg:col-span-8 col-span-12">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" value="" name="enable_my_rewards" checked="checked" class="sr-only peer">
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Own First Deposit Loyalty Points (Per USD Points)
                            </label>
                            <div class="lg:col-span-8 col-span-12">
                                <input type="number" min="0" name="own_first_deposit_loyality_points" class="form-control" placeholder="" value="10">
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Own Recurrning Deposit Loyalty Points (Per USD for Points)
                            </label>
                            <div class="lg:col-span-8 col-span-12">
                                <input type="number" min="0" name="own_recurring_deposit_loyalty_points" class="form-control" placeholder="" value="5">
                            </div>
                        </div>
                        <hr class="my-5">
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Own Trading points
                            </label>
                            <div class="lg:col-span-8 col-span-12">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" class="hidden" name="enable_trades_daily_cron">
                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                            Enable Trades Daily CRON
                                        </span>
                                    </label>
                                </div>
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" class="hidden" name="enable_trades_securities_points">
                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                            Trade Points allotment based on securities only
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label for="" class="lg:col-span-4 col-span-12 form-label">
                                Own traded Lot (Per Lot Points)
                            </label>
                            <div class="lg:col-span-8 col-span-12">
                                <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                    <div class="site-input-groups">
                                        <label for="" class="form-control-label">Points</label>
                                        <input type="number" min="0" name="own_traded_lot_loyality_points" class="form-control" placeholder="" value="">
                                    </div>
                                    <div class="site-input-groups">
                                        <label for="" class="form-control-label">Volume</label>
                                        <input type="number" min="0" name="own_traded_lot_loyality_volume" class="form-control" placeholder="" value="">
                                    </div>
                                </div>
                                <p class="text-xs mt-2">
                                    (Note: If above checkobx is checked then this points will be ignored.)
                                </p>
                            </div>
                        </div>
                        <hr class="my-5">
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label class="lg:col-span-4 col-span-12 form-label">
                                Client Trading Points
                            </label>
                            <div class="lg:col-span-8 col-span-12">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" class="hidden" name="enable_trades_daily_cron_clients">
                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                            Enable Trades Daily CRON
                                        </span>
                                    </label>
                                </div>
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" class="hidden" name="enable_trades_securities_points_clients">
                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                            Trade Points allotment based on securities only
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 items-center gap-5">
                            <label for="" class="lg:col-span-4 col-span-12 form-label">
                                Client traded Lot (Per Lot Points)
                            </label>
                            <div class="lg:col-span-8 col-span-12">
                                <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                    <div class="site-input-groups">
                                        <label for="" class="form-control-label">Points</label>
                                        <input type="number" min="0" name="client_traded_lot_loyality_points" class="form-control" placeholder="" value="20">
                                    </div>
                                    <div class="site-input-groups">
                                        <label for="" class="form-control-label">Volume</label>
                                        <input type="number" min="0" name="client_traded_lot_loyality_volume" class="form-control" placeholder="" value="2">
                                    </div>
                                </div>
                                <p class="text-xs mt-2">
                                    (Note: If above checkobx is checked then this points will be ignored.)
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection