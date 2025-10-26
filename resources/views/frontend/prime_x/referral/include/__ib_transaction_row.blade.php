@php use App\Enums\TxnStatus; use App\Enums\TxnType; use Carbon\Carbon; @endphp

@foreach($transactions as $transaction)
    <tr>
        <td class="table-td">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                        <iconify-icon icon="lucide:backpack"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                        {{ $transaction->description }} @if(!in_array($transaction->approval_cause,['none',""]))
                            <span class="toolTip onTop optional-msg" data-tippy-content="{{ $transaction->approval_cause }}">
                                <iconify-icon icon="lucide:mail"></iconify-icon>
                            </span>
                        @endif
                    </h4>
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                        {{ Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>
        </td>
        <td class="table-td">
            {{ $transaction->tnx }}
        </td>
        <td class="table-td">
            {{ $transaction->target_id }}
        </td>
        <td class="table-td">
            <strong class="{{in_array($transaction->type,[TxnType::Subtract,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto,TxnType::SendMoneyInternal]) ?  'text-danger' : 'text-success'}}">
                {{ (in_array($transaction->type,[TxnType::Subtract,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto,TxnType::SendMoneyInternal,TxnType::BonusSubtract]) ? '-': '+' ).$transaction->amount }}
            </strong>
        </td>
        <td class="table-td">
            {{ transaction_method_name($transaction) }}
        </td>
        <td class="table-td">
            {{ $transaction->charge }} {{ $currency }}
        </td>
        <td class="table-td">
            @switch($transaction->status)
                @case('pending')
                <span class="badge-warning bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Pending') }}</span>
                @break
                @case('success')
                <span class="badge-success bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Success') }}</span>
                @break
                @case('failed')
                <span class="badge-danger bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Canceled') }}</span>
                @break
            @endswitch
        </td>

    </tr>
@endforeach
