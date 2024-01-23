<form action="" method="post">
    @csrf
    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Live account with KYC</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="live_account_with_kyc">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-11" name="live_account_with_kyc" value="1">
                    <label for="active-11">Enable</label>
                    <input type="radio" id="disable-11" name="live_account_with_kyc" value="0" checked="">
                    <label for="disable-11">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Other Live Account (After First Deposit)</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="other_live_account">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-12" name="other_live_account" value="1">
                    <label for="active-12">Enable</label>
                    <input type="radio" id="disable-12" name="other_live_account" value="0" checked="">
                    <label for="disable-12">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Delete Demo Account after 90 Days (If No Live accounts)</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="delete_emo_account_after_90_days">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-13" name="delete_emo_account_after_90_days" value="1">
                    <label for="active-13">Enable</label>
                    <input type="radio" id="disable-13" name="delete_emo_account_after_90_days" value="0" checked="">
                    <label for="disable-13">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Delete Live Accounts after 90 Days (If No Balance)</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="delete_live_accounts_after_90_days">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-14" name="delete_live_accounts_after_90_days" value="1">
                    <label for="active-14">Enable</label>
                    <input type="radio" id="disable-14" name="delete_live_accounts_after_90_days" value="0" checked="">
                    <label for="disable-14">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Read-Only Password Change</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="read_only_password_change">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-15" name="read_only_password_change" value="1">
                    <label for="active-15">Enable</label>
                    <input type="radio" id="disable-15" name="read_only_password_change" value="0" checked="">
                    <label for="disable-15">Disabled</label>
                </div>
            </div>
        </div>
    </div>

    <div class="site-input-groups row">
        <div class="col-sm-5 col-label pt-0">Enable Demo Account Deposit</div>
        <div class="col-sm-7">
            <div class="form-switch ps-0">
                <input class="form-check-input" type="hidden" value="0" name="demo_account_deposit">
                <div class="switch-field same-type m-0">
                    <input type="radio" id="active-16" name="demo_account_deposit" value="1">
                    <label for="active-16">Enable</label>
                    <input type="radio" id="disable-16" name="demo_account_deposit" value="0" checked="">
                    <label for="disable-16">Disabled</label>
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