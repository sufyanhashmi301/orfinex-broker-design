@extends('backend.layouts.app')
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    {{ __('Create New Bonus')}}
                </h4>
                <a href="{{ url('admin/bonus') }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
            <div class="card-body p-6">
                <form action="" method="post" enctype="">
                    <div class="grid grid-cols-12 gap-5">
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Bonus Name:</label>
                                <input type="text" class="form-control" name="bonus_name" id="">
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Start Date:</label>
                                <input type="date" class="form-control" name="start_date" id="">
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Last Date:</label>
                                <input type="date" class="form-control" name="last_date" id="">
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Type:</label>
                                <select name="type" class="form-control w-100" id="gateway-select">
                                    <option value="">In Percentage</option>
                                    <option value="">In Amount</option>
                                </select>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Percentage / Amount:</label>
                                <input type="text" class="form-control" name="amount" id="">
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Process:</label>
                                <select name="process" class="form-control w-100" id="process">
                                    <option value="">On Deposit</option>
                                    <option value="">On Birthday</option>
                                    <option value="">On Low Balance</option>
                                </select>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Applicable by:</label>
                                <select name="applicable_by" class="form-control w-100" id="applicable_by">
                                    <option value="">Auto Apply</option>
                                    <option value="">Client Apply</option>
                                </select>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Bonus Removal:</label>
                                <select name="type" class="form-control w-100" id="gateway-select">
                                    <option value="">In Percentage</option>
                                    <option value="">In Amount</option>
                                    <option value="">Full Bonus</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Description:</label>
                                <textarea name="" class="form-control" rows="6"></textarea>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Terms & Condition Link:</label>
                                <input type="url" name="" class="form-control">
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">KYC Verified Only:</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input type="radio" id="kyc_verified" name="kyc_verified" value="1" checked="">
                                    <label for="kyc_verified">Yes</label>
                                    <input type="radio" id="radio-six" name="kyc_verified" value="0">
                                    <label for="radio-six">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">First Deposit Only:</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input type="radio" id="radio-seven" name="first_deposit" value="1" checked="">
                                    <label for="radio-seven">Yes</label>
                                    <input type="radio" id="radio-eight" name="first_deposit" value="0">
                                    <label for="radio-eight">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Status:</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input type="radio" id="radio-nine" name="status" value="1" checked="">
                                    <label for="radio-nine">Active</label>
                                    <input type="radio" id="radio-ten" name="status" value="0">
                                    <label for="radio-ten">Deactivate</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
