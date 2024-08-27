<option value="{{ auth()->user()->multi_ib_login }}" data-type="ib-account" class="inline-block font-Inter font-normal text-sm text-slate-600">
    {{ auth()->user()->multi_ib_login }} - {{ __('MIB') }} ({{ get_mt5_account_equity(auth()->user()->multi_ib_login)   }} {{$currency}})
</option>
