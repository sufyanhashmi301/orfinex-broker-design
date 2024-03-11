@extends('backend.layouts.app')
@section('content')
<div class="main-content">
    <div class="page-title">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="title-content">
                        <h2 class="title">
                            {{ __('Update Challenge')}}
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
                        <form action="{{ route('admin.challenges.update', $challenge->id) }}" class="row" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="col-xl-12">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Challenge Name:</label>
                                    <input type="text" value="{{$challenge->challenge_name}}" class="box-input" name="challenge_name" id="">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Challenge Code:</label>
                                    <input type="text" value="{{$challenge->challenge_code}}" class="box-input" name="challenge_code" pattern="[a-zA-Z]{3}[0-9]{4}" placeholder="e.g. END3424" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Schema Badge:</label>
                                    <input type="text" value="{{$challenge->schema_badge}}" class="box-input" name="schema_badge" id="">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Type of Phases:</label>
                                    <select name="type_of_phases" class="form-select" id="">
                                        <option value="">-- Choose --</option>
                                        <option value="1" @if(old('type_of_phases', $challenge->type_of_phases) === 1) selected @endif>1 Step</option>
                                        <option value="2" @if(old('type_of_phases', $challenge->type_of_phases) === 2) selected @endif>2 Step</option>
                                        <option value="3" @if(old('type_of_phases', $challenge->type_of_phases) === 3) selected @endif>3 Step</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-xl-12">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Addons Applicable:</label>
                                    <div class="form-check-inline">
                                        <div class="form-check form-switch align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="no_time_limit" checked>
                                            <label class="form-check-label" for="no_time_limit">No Time Limit</label>
                                        </div>
                                    </div>
                                    <div class="form-check-inline">
                                        <div class="form-check form-switch align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="no_expiry_limit">
                                            <label class="form-check-label" for="no_expiry_limit">No Expiry Limit</label>
                                        </div>
                                    </div>
                                    <div class="form-check-inline">
                                        <div class="form-check form-switch align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="swap_free">
                                            <label class="form-check-label" for="swap_free">Swap Free / Islamic</label>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Next Stage Process:</label>
                                    <select name="next_stage_process" class="form-select" id="next_stage_process">
                                        <option value="Auto Issuance" @if(old('next_stage_process', $challenge->next_stage_process) === 'Auto Issuance') selected @endif>Auto Issuance </option>
                                        <option value="Manual Issuance" @if(old('next_stage_process', $challenge->next_stage_process) === 'Manual Issuance') selected @endif>Manual Issuance</option>
                                        <option value="Account Reset" @if(old('next_stage_process', $challenge->next_stage_process) === 'Account Reset') selected @endif>Account Reset</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Max Purchase Limit:</label>
                                    <input type="number" value="{{$challenge->max_purchase_limit}}" class="box-input" name="max_purchase_limit" id="">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Refundable:</label>
                                    <select name="refundable_by" class="form-select" id="refundable">
                                        <option value="Not applicable" @if(old('refundable_by', $challenge->refundable_by) === 'Not applicable') selected @endif>Not applicable</option>
                                        <option value="On Phase Clear" @if(old('refundable_by', $challenge->refundable_by) === 'On Phase Clear') selected @endif>On Phase Clear</option>
                                        <option value="On First Payout" @if(old('refundable_by', $challenge->refundable_by) === 'On First Payout') selected @endif>On First Payout</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Daily Risk Track:</label>
                                    <input type="time" class="box-input" value="{{$challenge->daily_risk_track}}" name="daily_risk_track" id="">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Main Risk Track:</label>
                                    <select name="main_risk_track" class="form-select" id="main_risk_track">
                                        <option value="1 Minute" @if(old('main_risk_track', $challenge->main_risk_track) === '1 Minute') selected @endif>1 Minute</option>
                                        <option value="5 Minute" @if(old('main_risk_track', $challenge->main_risk_track) === '5 Minute') selected @endif>5 Minute</option>
                                        <option value="10 Minute" @if(old('main_risk_track', $challenge->main_risk_track) === '10 Minute') selected @endif>10 Minute</option>
                                        <option value="15 Minute" @if(old('main_risk_track', $challenge->main_risk_track) === '15 Minute') selected @endif>15 Minute</option>
                                        <option value="30 Minute" @if(old('main_risk_track', $challenge->main_risk_track) === '30 Minute') selected @endif>30 Minute</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Random Risk Track:</label>
                                    <select name="random_risk_track" class="form-select" id="random_risk_track">
                                        <option value="5 times per day" @if(old('random_risk_track', $challenge->random_risk_track) === '5 times per day') selected @endif>5 Minute</option>
                                        <option value="10 times per day" @if(old('random_risk_track', $challenge->random_risk_track) === '10 times per day') selected @endif>10 times per day</option>
                                        <option value="24 times per day" @if(old('random_risk_track', $challenge->random_risk_track) === '24 times per day') selected @endif>24 times per day</option>
                                        <option value="48 times per day" @if(old('random_risk_track', $challenge->random_risk_track) === '48 times per day') selected @endif>48 times per day</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">Priority Level:</label>
                                    <select name="priority_level" class="form-select" id="">
                                        <option value="">-- Choose --</option>
                                        <option value="1" @if(old('priority_level', $challenge->priority_level) === 1) selected @endif>1 Step</option>
                                        <option value="2" @if(old('priority_level', $challenge->priority_level) === 2) selected @endif>2 Step</option>
                                        <option value="3" @if(old('priority_level', $challenge->priority_level) === 3) selected @endif>3 Step</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="site-input-groups">
                                            <label class="box-input-label" for="">Affiliate Partner Commission:</label>
                                            <div class="switch-field same-type">
                                                <input type="radio" id="radio-five" name="affiliate_partner_commission" value="1" @if(old('affiliate_partner_commission', $challenge->affiliate_partner_commission) === 1) checked @endif>
                                                <label for="radio-five">Yes</label>
                                                <input type="radio" id="radio-six" name="affiliate_partner_commission" value="0" @if(old('affiliate_partner_commission', $challenge->affiliate_partner_commission) === 0) checked @endif>
                                                <label for="radio-six">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="site-input-groups">
                                            <label class="box-input-label" for=""> Vacations / Pause:</label>
                                            <div class="switch-field same-type">
                                                <input type="radio" id="radio-seven" name="vacations" value="1" @if(old('affiliate_partner_commission', $challenge->affiliate_partner_commission) === 1) checked @endif>
                                                <label for="radio-seven">Yes</label>
                                                <input type="radio" id="radio-eight" name="vacations" value="0" @if(old('affiliate_partner_commission', $challenge->affiliate_partner_commission) === 0) checked @endif>
                                                <label for="radio-eight">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <button type="submit" class="site-btn primary-btn w-100">
                                    Save Changes
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
