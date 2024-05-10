<div class="form-select-img select2-lg">
    <select name="account_from" class="select2 form-control !text-lg w-full" data-ui="lg">
        <option value="{{the_hash(\App\Enums\AccountClassification::WALLETS)}}_{{the_hash(\App\Enums\AccountBalanceType::MAIN)}}" selected
        data-image="{{ asset('assets/images/wallet-icon.png') }}"
        data-des="<li><span class='fw-medium'>Balance : </span><span class='text-primary'>{{account_balance()}}</span></li>">{{__('Main Wallet')}}</option>
        <option value="{{the_hash(\App\Enums\AccountClassification::WALLETS)}}_{{the_hash(\App\Enums\AccountBalanceType::AFFILIATE_WALLET)}}"
        data-image="{{ asset('assets/images/wallet-icon.png') }}"
        data-des="<li><span class='fw-medium'>Balance : </span><span class='text-primary'>{{account_balance(\App\Enums\AccountBalanceType::AFFILIATE_WALLET)}}</span></li>">{{__('Affiliate Wallet')}}</option>
    </select>
</div>
