<div class="form-select-img select2-lg">
    <select name="account_from" class="select2 form-control !text-lg w-full" data-ui="lg">
        <option value="{{the_hash(\App\Enums\AccountClassification::WALLETS)}}_{{the_hash(\App\Enums\AccountBalanceType::MAIN)}}" selected
        data-image="{{ asset('assets/images/wallet-icon.png') }}"
        data-des="<li><span class='fw-medium'>Balance : </span><span class='text-primary'>{{account_balance()}}</span></li>">{{__('Main Wallet')}}</option>
        <option value="{{the_hash(\App\Enums\AccountClassification::WALLETS)}}_{{the_hash(\App\Enums\AccountBalanceType::AFFILIATE_WALLET)}}"
        data-image="{{ asset('assets/images/wallet-icon.png') }}"
        data-des="<li><span class='fw-medium'>Balance : </span><span class='text-primary'>{{account_balance(\App\Enums\AccountBalanceType::AFFILIATE_WALLET)}}</span></li>">{{__('Affiliate Wallet')}}</option>
{{--        @foreach($forexAccounts as $forexAccount)--}}
{{--            <option value="{{ the_hash(\App\Enums\AccountClassification::FOREX)}}_{{the_hash($forexAccount->login)}}"  class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ $forexAccount->equity }} {{$currency}})</option>--}}
{{--        @endforeach--}}
    </select>
</div>
