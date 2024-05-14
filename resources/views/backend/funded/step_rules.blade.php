@extends('backend.layouts.app')
@section('content')
<div class="main-content">
    <div class="page-title">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="title-content">
                        <h2 class="title">
                            {{ __('Step 1 Rules')}}
                        </h2>
                        <a href="{{ url('admin/challenges') }}" class="title-btn">
                            <i icon-name="corner-down-left"></i>
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="site-card">
                    <div class="site-card-body">
                        <form action="{{ route('admin.step-rules.create') }}" method="post" class="row">
                            @csrf
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label for="" class="box-input-label">Initial Deposit</label>
                                    <input type="text" class="box-input" name="initial_deposit" placeholder="1000 USD">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label for="" class="box-input-label">{{__('Profit Target')}}</label>
                                    <input type="text" class="box-input" name="profit_target" placeholder="1000 USD">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label for="" class="box-input-label">{{__('Daily Drawdown')}}</label>
                                    <input type="text" class="box-input" name="daily_drawdown" placeholder="1000 USD">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{__('Daily Drawdown Type')}}</label>
                                    <select class="form-select" name="daily_drawdown_type">
                                        <option value="">{{__('From Max Balance')}}</option>
                                        <option value="">{{__('From Max Equity')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label for="" class="box-input-label">{{__('Max Drawdown')}}</label>
                                    <input type="text" class="box-input" name="" placeholder="1000 USD">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{__('Max Drawdown Type')}}</label>
                                    <select class="form-select" name="">
                                        <option value="">{{__('From Max Balance')}}</option>
                                        <option value="">{{__('From Max Equity')}}</option>
                                        <option value="">{{__('From Assigned Balance')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input me-2" type="checkbox" id="maxTradeAllowed" data-target="maxTradeAllowedInput">
                                        <label class="form-check-label" for="maxTradeAllowed">
                                            {{__('Max Trade Allowed')}}
                                        </label>
                                    </div>
                                    <div class="input-group" id="maxTradeAllowedInput" style="display: none">
                                        <input type="number" class="box-input form-control" placeholder="10">
                                        <span class="input-group-text">Days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="site-input-groups">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input me-2" type="checkbox" id="minTradingDays" data-target="minTradingDaysInput">
                                                <label class="form-check-label" for="minTradingDays">
                                                    {{__('Min Trading Days')}}
                                                </label>
                                            </div>
                                            <div class="input-group" id="minTradingDaysInput" style="display: none">
                                                <input type="number" class="box-input form-control" placeholder="10">
                                                <span class="input-group-text">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="site-input-groups">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input me-2" type="checkbox" id="maxTradingDays" data-target="maxTradingDaysInput">
                                                <label class="form-check-label" for="maxTradingDays">
                                                    {{__('Max Trading Days')}}
                                                </label>
                                            </div>
                                            <div class="input-group" id="maxTradingDaysInput" style="display: none">
                                                <input type="number" class="box-input form-control" placeholder="10">
                                                <span class="input-group-text">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{__('Stop Loss')}}</label>
                                    <select class="form-select" name="">
                                        <option value="">{{__('Must')}}</option>
                                        <option value="">{{__('Mix')}}</option>
                                        <option value="">{{__('No Check')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{__('Take Profit')}}</label>
                                    <select class="form-select" name="">
                                        <option value="">{{__('Must')}}</option>
                                        <option value="">{{__('Mix')}}</option>
                                        <option value="">{{__('No Check')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input me-2" type="checkbox" id="expiryPeriod" data-target="expiryPeriodInput">
                                        <label class="form-check-label" for="expiryPeriod">
                                            {{__('Expiry Period')}}
                                        </label>
                                    </div>
                                    <div class="input-group" id="expiryPeriodInput" style="display: none">
                                        <input type="number" class="box-input form-control" placeholder="10">
                                        <span class="input-group-text">Days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <button type="submit" class="site-btn primary-btn w-100">
                                    {{ __('Update Rules') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
            $('.form-check-input').click(function () {
                var targetId = $(this).attr('data-target');
                if ($(this).is(':checked')) {
                    $('#' + targetId).show();
                }else{
                    $('#' + targetId).hide();
                }
            })
        })
</script>
@endsection
