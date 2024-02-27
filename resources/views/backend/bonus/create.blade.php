@extends('backend.layouts.app')
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">
                                {{ __('Create New Bonus')}}
                            </h2>
                            <a href="{{ url('admin/bonus') }}" class="title-btn">
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
                            <form action="" class="row" method="post" enctype="">
                                <div class="col-xl-12">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Bonus Name:</label>
                                        <input type="text" class="box-input" name="bonus_name" id="">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Start Date:</label>
                                        <input type="date" class="box-input" name="start_date" id="">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Last Date:</label>
                                        <input type="date" class="box-input" name="last_date" id="">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Type:</label>
                                        <select name="type" class="form-select" id="gateway-select">
                                            <option value="">In Percentage</option>
                                            <option value="">In Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Percentage / Amount:</label>
                                        <input type="text" class="box-input" name="amount" id="">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Process:</label>
                                        <select name="process" class="form-select" id="process">
                                            <option value="">On Deposit</option>
                                            <option value="">On Birthday</option>
                                            <option value="">On Low Balance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Applicable by:</label>
                                        <select name="applicable_by" class="form-select" id="applicable_by">
                                            <option value="">Auto Apply</option>
                                            <option value="">Client Apply</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Bonus Removal:</label>
                                        <select name="type" class="form-select" id="gateway-select">
                                            <option value="">In Percentage</option>
                                            <option value="">In Amount</option>
                                            <option value="">Full Bonus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Description:</label>
                                        <textarea name="" class="form-textarea" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Terms & Condition Link:</label>
                                        <input type="url" name="" class="box-input">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">KYC Verified Only:</label>
                                        <div class="switch-field same-type">
                                            <input type="radio" id="kyc_verified" name="kyc_verified" value="1" checked="">
                                            <label for="kyc_verified">Yes</label>
                                            <input type="radio" id="radio-six" name="kyc_verified" value="0">
                                            <label for="radio-six">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">First Deposit Only:</label>
                                        <div class="switch-field same-type">
                                            <input type="radio" id="radio-seven" name="first_deposit" value="1" checked="">
                                            <label for="radio-seven">Yes</label>
                                            <input type="radio" id="radio-eight" name="first_deposit" value="0">
                                            <label for="radio-eight">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">Status:</label>
                                        <div class="switch-field same-type">
                                            <input type="radio" id="radio-nine" name="status" value="1" checked="">
                                            <label for="radio-nine">Active</label>
                                            <input type="radio" id="radio-ten" name="status" value="0">
                                            <label for="radio-ten">Deactivate</label>
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
