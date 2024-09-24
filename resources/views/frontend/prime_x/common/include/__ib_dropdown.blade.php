<option value="{{ auth()->user()->ib_login }}" data-type="ib-account" class="inline-block font-Inter font-normal text-sm text-slate-600">
    {{ auth()->user()->ib_login }} - {{ __('IB') }} ({{ get_mt5_account_equity(auth()->user()->ib_login)   }} {{$currency}})
</option>
