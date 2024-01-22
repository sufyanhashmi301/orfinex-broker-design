@extends('backend.layouts.app')
@section('title')
    {{ __('Loyalty Points') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="title-content">
                    <h2 class="title">{{ __('Loyalty Points') }}</h2>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-9 col-lg-12 col-md-12 col-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <form action="" method="post" class="row align-items-center">
                                @csrf
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">Points Redeemed (Per USD)</label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <input type="number" min="0" name="cash_out_redeem" class="box-input" placeholder="" value="100">
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        <strong>Invited Users Points</strong>
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12"></div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">Invited Registered</label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 loy-div">
                                        <input type="number" min="0" name="invite_register_loyalty_points" class="box-input" placeholder="" value="20">
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">Invited KYC Verified</label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 loy-div">
                                        <input type="number" min="0" name="kyc_verified_loyalty_points" class="box-input" placeholder="" value="20">
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Invited First Deposit Amount(per USD for Points)
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 loy-div">
                                        <input type="number" min="0" name="first_deposit_loyalty_points" class="box-input" placeholder="" value="20">
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">Invited First Deposit Amount(Fixed Points)</label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 loy-div">
                                        <input type="number" min="0" name="first_deposit_loyalty_points_fixed" class="box-input" placeholder="" value="">
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Invited First Deposit Amount(Percentage Deposit Points)
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 loy-div">
                                        <input type="number" min="0" name="first_deposit_loyalty_points_percentage" class="box-input" placeholder="" value="">
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label for="" class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Invited First Deposit Amount(Fixed Points - For above range - 1)
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <div class="form-row row">
                                            <div class="col-xl-6 col-md-6 col-sm-12">
                                                <div class="site-input-groups">
                                                    <label for="" class="box-input-label">Points</label>
                                                    <input type="number" min="0" name="first_deposit_loyalty_points_fixed_above_range_1_points" class="box-input" placeholder="" value="">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 col-sm-12">
                                                <div class="site-input-groups">
                                                    <label for="" class="box-input-label">Above range amount</label>
                                                    <input type="number" min="0" name="first_deposit_loyalty_points_fixed_above_range_1_range" class="box-input" placeholder="" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Invited Recurring Deposit Amount (per USD for Points)
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 loy-div">
                                        <input type="number" min="0" name="recurring_deposit_loyalty_points" class="box-input" placeholder="" value="5">
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Own Activity Points
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12"></div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Enable My Referral
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" name="enable_my_referal" value="TRUE" style="width: 2em !important">
                                        </div>
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">Enable My Rewards</label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" name="enable_my_rewards" value="TRUE" style="width: 2em !important">
                                        </div>
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Own First Deposit Loyalty Points (Per USD Points)
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <input type="number" min="0" name="own_first_deposit_loyality_points" class="box-input" placeholder="" value="10">
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Own Recurrning Deposit Loyalty Points (Per USD for Points)
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <input type="number" min="0" name="own_recurring_deposit_loyalty_points" class="box-input" placeholder="" value="5">
                                    </div>
                                </div>
                                <hr class="my-5">
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Own Trading points
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enable_trades_daily_cron" value="TRUE">
                                            <span class="form-check-label">
                                                Enable Trades Daily CRON
                                            </span>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enable_trades_securities_points" value="TRUE">
                                            <span class="form-check-label">
                                                Trade Points allotment based on securities only
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label for="" class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Own traded Lot (Per Lot Points)
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <div class="form-row row">
                                            <div class="col-xl-6 col-md-6 col-sm-12">
                                                <div class="site-input-groups">
                                                    <label for="" class="box-input-label">Points</label>
                                                    <input type="number" min="0" name="own_traded_lot_loyality_points" class="box-input" placeholder="" value="">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 col-sm-12">
                                                <div class="site-input-groups">
                                                    <label for="" class="box-input-label">Volume</label>
                                                    <input type="number" min="0" name="own_traded_lot_loyality_volume" class="box-input" placeholder="" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <small class="mt-2">
                                            (Note: If above checkobx is checked then this points will be ignored.)
                                        </small>
                                    </div>
                                </div>
                                <hr class="my-5">
                                <div class="site-input-groups row align-items-center">
                                    <label class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Client Trading Points
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enable_trades_daily_cron_clients" value="TRUE">
                                            <label class="form-check-label">
                                                Enable Trades Daily CRON
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enable_trades_securities_points_clients" value="TRUE">
                                            <label class="form-check-label">
                                                Trade Points allotment based on securities only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="site-input-groups row align-items-center">
                                    <label for="" class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-label pt-0">
                                        Client traded Lot (Per Lot Points)
                                    </label>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                        <div class="form-row row">
                                            <div class="col-xl-6 col-md-6 col-sm-12">
                                                <div class="site-input-groups">
                                                    <label for="" class="box-input-label">Points</label>
                                                    <input type="number" min="0" name="client_traded_lot_loyality_points" class="box-input" placeholder="" value="20">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 col-sm-12">
                                                <div class="site-input-groups">
                                                    <label for="" class="box-input-label">Volume</label>
                                                    <input type="number" min="0" name="client_traded_lot_loyality_volume" class="box-input" placeholder="" value="2">
                                                </div>
                                            </div>
                                        </div>
                                        <small class="mt-2">
                                            (Note: If above checkobx is checked then this points will be ignored.)
                                        </small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-sm-4 col-sm-8">
                                        <button type="submit" class="site-btn-sm primary-btn w-100">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection