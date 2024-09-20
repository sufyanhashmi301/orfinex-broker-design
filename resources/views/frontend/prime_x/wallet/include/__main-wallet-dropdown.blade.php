@foreach(get_all_wallets(auth()->user()->id,\App\Enums\AccountBalanceType::MAIN) as $wallet)
    <option value="{{ the_hash($wallet->wallet_id) }}" data-type="wallet"
            class="inline-block font-Inter font-normal text-sm text-slate-600">
        {{ $wallet->wallet_id }} - {{ w2n($wallet->balance) }} ({{ $wallet->amount }} {{$currency}})
    </option>
@endforeach
