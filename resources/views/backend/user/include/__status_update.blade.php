<div class="card mb-0">
    <div class="card-header">
        <h3 class="card-title">{{ __('Account Informations') }}</h3>
    </div>
    <div class="card-body p-5">
        <div class="row">
            <form action="{{route('admin.user.status-update',$user->id)}}" method="post" class="space-y-5">
                @csrf

                <div class="col-xl-12">
                    <div class="input-area flex items-center justify-between">
                        <h5 class="form-label">{{ __('Account Status') }}</h5>
                        <div class="form-switch ps-0">
                            <input 
                                class="form-check-input"
                                type="hidden" 
                                value="0" 
                                name="status"
                            />
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="status" 
                                    value="1" 
                                    @if($user->status) checked @endif
                                    class="sr-only peer" 
                                />
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area flex items-center justify-between">
                        <h5 class="form-label">{{ __('Multi IB') }}</h5>
                        <div class="form-switch ps-0">
                            <input 
                                class="form-check-input"
                                type="hidden" 
                                value="0" 
                                name="is_multi_ib"
                            />
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="is_multi_ib" 
                                    value="1" 
                                    @if($user->is_multi_ib) checked @endif
                                    class="sr-only peer" 
                                />
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area flex items-center justify-between">
                        <h5 class="form-label">{{ __('Email Verification') }}</h5>
                        <div class="form-switch ps-0">
                            <input 
                                class="form-check-input"
                                type="hidden" 
                                value="0" 
                                name="email_verified"
                            />
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="email_verified" 
                                    value="1" 
                                    @if($user->email_verified_at != null) checked @endif
                                    class="sr-only peer" 
                                />
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area flex items-center justify-between">
                        <h5 class="form-label">{{ __('KYC Verification') }}</h5>
                        <div class="form-switch ps-0">
                            <input 
                                class="form-check-input"
                                type="hidden" 
                                value="0" 
                                name="kyc"
                            />
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="kyc" 
                                    value="1" 
                                    @if($user->kyc == 1) checked @endif
                                    class="sr-only peer" 
                                />
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area flex items-center justify-between">
                        <h5 class="form-label">{{ __('2FA Verification') }}</h5>
                        <div class="form-switch ps-0">
                            <input 
                                class="form-check-input"
                                type="hidden" 
                                value="0" 
                                name="two_fa"
                            />
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="two_fa" 
                                    value="1" 
                                    @if($user->two_fa) checked @endif
                                    class="sr-only peer" 
                                />
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area flex items-center justify-between">
                        <h5 class="form-label">{{ __('Deposit Status') }}</h5>
                        <div class="form-switch ps-0">
                            <input 
                                class="form-check-input"
                                type="hidden" 
                                value="0" 
                                name="deposit_status"
                            />
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="deposit_status" 
                                    value="1" 
                                    @if($user->deposit_status) checked @endif
                                    class="sr-only peer" 
                                />
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area flex items-center justify-between">
                        <h5 class="form-label">{{ __('Withdraw Status') }}</h5>
                        <div class="form-switch ps-0">
                            <input 
                                class="form-check-input"
                                type="hidden" 
                                value="0" 
                                name="withdraw_status"
                            />
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="withdraw_status" 
                                    value="1" 
                                    @if($user->withdraw_status) checked @endif
                                    class="sr-only peer" 
                                />
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="input-area flex items-center justify-between">
                        <h5 class="form-label">{{ __('Send Money Status') }}</h5>
                        <div class="form-switch ps-0">
                            <input 
                                class="form-check-input" 
                                id="trans1"
                                type="hidden" 
                                value="0" 
                                name="transfer_status"
                            />
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="transfer_status" 
                                    value="1" 
                                    @if($user->transfer_status) checked @endif
                                    class="sr-only peer" 
                                />
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 mb-3">
                    <div class="input-area profile-card-single">
                        <h5 class="form-label">{{ __('Account Limit (Max)') }}</h5>
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
