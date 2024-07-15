<div class="card mb-0">
    <div class="card-header">
        <h3 class="card-title">{{ __('Account Informations') }}</h3>
    </div>
    <div class="card-body p-5">
        <div class="row">
            <form action="{{route('admin.user.status-update',$user->id)}}" method="post">
                @csrf

                <div class="col-xl-12">
                    <div class="input-area">
                        <h5 class="form-label">{{ __('Account Status') }}</h5>
                        <div class="switch-field flex mb-3 overflow-hidden">
                            <input
                                type="radio"
                                id="accSta1"
                                name="status"
                                value="1"
                                @if($user->status) checked @endif
                            />
                            <label for="accSta1">{{ __('Active') }}</label>
                            <input
                                type="radio"
                                id="accSta2"
                                name="status"
                                value="0"
                                @if(!$user->status) checked @endif
                            />
                            <label for="accSta2">{{ __('Disabled') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area">
                        <h5 class="form-label">{{ __('Multi IB') }}</h5>
                        <div class="switch-field flex mb-3 overflow-hidden">
                            <input
                                type="radio"
                                id="is_multi_ib1"
                                name="is_multi_ib"
                                value="1"
                                @if($user->is_multi_ib) checked @endif
                            />
                            <label for="is_multi_ib1">{{ __('Active') }}</label>
                            <input
                                type="radio"
                                id="is_multi_ib2"
                                name="is_multi_ib"
                                value="0"
                                @if(!$user->is_multi_ib) checked @endif
                            />
                            <label for="is_multi_ib2">{{ __('Disabled') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area">
                        <h5 class="form-label">{{ __('Email Verification') }}</h5>
                        <div class="switch-field flex mb-3 overflow-hidden">
                            <input
                                type="radio"
                                id="emaSta1"
                                name="email_verified"
                                value="1"
                                @if($user->email_verified_at != null) checked @endif
                            />
                            <label for="emaSta1">{{ __('Verified') }}</label>
                            <input
                                type="radio"
                                id="emaSta2"
                                name="email_verified"
                                value="0"
                                @if($user->email_verified_at == null) checked @endif
                            />
                            <label for="emaSta2">{{ __('Unverified') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area">
                        <h5 class="form-label">{{ __('KYC Verification') }}</h5>
                        <div class="switch-field flex mb-3 overflow-hidden">
                            <input
                                type="radio"
                                id="kyc1"
                                name="kyc"
                                value="1"
                                @if($user->kyc == 1) checked @endif
                            />
                            <label for="kyc1">{{ __('Verified') }}</label>
                            <input
                                type="radio"
                                id="kyc2"
                                name="kyc"
                                value="0"
                                @if($user->kyc != 1) checked @endif
                            />
                            <label for="kyc2">{{ __('Unverified') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area">
                        <h5 class="form-label">{{ __('2FA Verification') }}</h5>
                        <div class="switch-field flex mb-3 overflow-hidden">
                            <input
                                type="radio"
                                id="2fa1"
                                name="two_fa"
                                value="1"
                                @if($user->two_fa) checked @endif
                            />
                            <label for="2fa1">{{ __('Active') }}</label>
                            <input
                                type="radio"
                                id="2fa2"
                                name="two_fa"
                                value="0"
                                @if(!$user->two_fa) checked @endif
                            />
                            <label for="2fa2">{{ __('Disabled') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area">
                        <h5 class="form-label">{{ __('Deposit Status') }}</h5>
                        <div class="switch-field flex mb-3 overflow-hidden">
                            <input
                                type="radio"
                                id="depo1"
                                name="deposit_status"
                                value="1"
                                @if($user->deposit_status) checked @endif
                            />
                            <label for="depo1">{{ __('Active') }}</label>
                            <input
                                type="radio"
                                id="depo2"
                                name="deposit_status"
                                value="0"
                                @if(!$user->deposit_status) checked @endif
                            />
                            <label for="depo2">{{ __('Disabled') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area">
                        <h5 class="form-label">{{ __('Withdraw Status') }}</h5>
                        <div class="switch-field flex mb-3 overflow-hidden">
                            <input
                                type="radio"
                                id="wid1"
                                name="withdraw_status"
                                value="1"
                                @if($user->withdraw_status) checked @endif
                            />
                            <label for="wid1">{{ __('Active') }}</label>
                            <input
                                type="radio"
                                id="wid2"
                                name="withdraw_status"
                                value="0"
                                @if(!$user->withdraw_status) checked @endif
                            />
                            <label for="wid2">{{ __('Disabled') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area">
                        <h5 class="form-label">{{ __('Send Money Status') }}</h5>
                        <div class="switch-field flex mb-3 overflow-hidden">
                            <input
                                type="radio"
                                id="trans1"
                                name="transfer_status"
                                value="1"
                                @if($user->transfer_status) checked @endif
                            />
                            <label for="trans1">{{ __('Active') }}</label>
                            <input
                                type="radio"
                                id="trans2"
                                name="transfer_status"
                                value="0"
                                @if(!$user->transfer_status) checked @endif
                            />
                            <label for="trans2">{{ __('Disabled') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="profile-card-single">
                        <h5 class="heading">{{ __('Forex Accounts Create limit') }}</h5>
                        <input type="text" name="account_limit" value="{{$user->account_limit}}"
                               oninput="this.value = validateDouble(this.value)"
                               class="form-control">
                    </div>
                </div>
                
                <div class="input-area">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center w-full">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
