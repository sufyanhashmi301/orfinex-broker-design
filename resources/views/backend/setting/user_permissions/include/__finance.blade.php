<form action="" method="post">
    @csrf
    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Deposit with KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="deposit_with_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active" name="deposit_with_kyc" value="1">
                    <label for="active">Enable</label>
                    <input type="radio" id="disable" name="deposit_with_kyc" value="0" checked="">
                    <label for="disable">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Restrict Deposit without KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="deposit_without_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-2" name="deposit_without_kyc" value="1">
                    <label for="active-2">Enable</label>
                    <input type="radio" id="disable-2" name="deposit_without_kyc" value="0" checked="">
                    <label for="disable-2">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Withdrawal with KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="withdrawal_with_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-3" name="withdrawal_with_kyc" value="1">
                    <label for="active-3">Enable</label>
                    <input type="radio" id="disable-3" name="withdrawal_with_kyc" value="0" checked="">
                    <label for="disable-3">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Restrict Withdrawal without KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="withdrawal_without_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-4" name="withdrawal_without_kyc" value="1">
                    <label for="active-4">Enable</label>
                    <input type="radio" id="disable-4" name="withdrawal_without_kyc" value="0" checked="">
                    <label for="disable-4">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Internal Transfer with KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="internal_ransfer_with_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-5" name="internal_ransfer_with_kyc" value="1">
                    <label for="active-5">Enable</label>
                    <input type="radio" id="disable-5" name="internal_ransfer_with_kyc" value="0" checked="">
                    <label for="disable-5">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Restrict Internal Transfer without KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="internal_ransfer_without_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-6" name="internal_ransfer_without_kyc" value="1">
                    <label for="active-6">Enable</label>
                    <input type="radio" id="disable-6" name="internal_ransfer_without_kyc" value="0" checked="">
                    <label for="disable-6">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable External Transfer with KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="external_transfer_with_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-7" name="external_transfer_with_kyc" value="1">
                    <label for="active-7">Enable</label>
                    <input type="radio" id="disable-7" name="external_transfer_with_kyc" value="0" checked="">
                    <label for="disable-7">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Restrict External Transfer without KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="external_transfer_without_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-8" name="external_transfer_without_kyc" value="1">
                    <label for="active-8">Enable</label>
                    <input type="radio" id="disable-8" name="external_transfer_without_kyc" value="0" checked="">
                    <label for="disable-8">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Max Pending Withdraw Request</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="max_pending_withdraw_request">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-9" name="max_pending_withdraw_request" value="1">
                    <label for="active-9">Enable</label>
                    <input type="radio" id="disable-9" name="max_pending_withdraw_request" value="0" checked="">
                    <label for="disable-9">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Payment Method Order</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="payment_method_order">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-10" name="payment_method_order" value="1">
                    <label for="active-10">Enable</label>
                    <input type="radio" id="disable-10" name="payment_method_order" value="0" checked="">
                    <label for="disable-10">Disabled</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="offset-sm-5 col-sm-7">
            <button type="submit" class="site-btn-sm primary-btn w-100">
                Save Changes
            </button>
        </div>
    </div>
</form>