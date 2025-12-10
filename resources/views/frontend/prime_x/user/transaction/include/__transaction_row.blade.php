@php
    use App\Enums\TxnStatus;
    use App\Enums\TxnType;
@endphp

@foreach ($transactions as $transaction)
    <tr>
        <td class="table-td">
            <div class="flex items-center">
                <div class="flex-none">
                    <div
                        class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                        @switch($transaction->type->value)
                            @case('send_money')
                                <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                            @break

                            @case('send_money_internal')
                                <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                            @break

                            @case('receive_money')
                                <iconify-icon icon="ph:arrow-left-bold"></iconify-icon>
                            @break

                            @case('deposit')
                                <iconify-icon icon="octicon:download-16"></iconify-icon>
                            @break

                            @case('manual_deposit')
                                <iconify-icon icon="octicon:download-16"></iconify-icon>
                            @break

                            @case('investment')
                                <iconify-icon icon="fluent:arrow-swap-24-regular"></iconify-icon>
                            @break

                            @case('withdraw')
                                <iconify-icon icon="akar-icons:arrow-back"></iconify-icon>
                            @break

                            @default()
                            <iconify-icon icon="lucide:backpack"></iconify-icon>
                        @endswitch
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <h4 class="text-sm font-medium text-slate-600 dark:text-slate-100 whitespace-nowrap">
                        {{ $transaction->description }} @if (!in_array($transaction->approval_cause, ['none', '']))
                            <span class="toolTip onTop optional-msg"
                                data-tippy-content="{{ $transaction->approval_cause }}">
                                <iconify-icon icon="lucide:mail"></iconify-icon>
                            </span>
                        @endif
                    </h4>
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-200">
                        {{ $transaction->display_time->format('M d, Y h:i A') }}
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
            <strong
                class="{{ in_array($transaction->type, [TxnType::Subtract, TxnType::SendMoney, TxnType::Withdraw, TxnType::WithdrawAuto, TxnType::SendMoneyInternal]) ? 'text-danger' : 'text-success' }}">
                {{ (in_array($transaction->type, [TxnType::Subtract, TxnType::SendMoney, TxnType::Withdraw, TxnType::WithdrawAuto, TxnType::SendMoneyInternal, TxnType::BonusSubtract]) ? '-' : '+') . $transaction->amount . ' ' . $transaction->currency }}
            </strong>
        </td>
        <td class="table-td">
            {{ transaction_method_name($transaction) }}
        </td>
        <td class="table-td">
            {{ $transaction->charge }} {{ $currency }}
        </td>
        <td class="table-td">
            @switch($transaction->status->value)
                @case('pending')
                    <span class="badge-warning bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Pending') }}</span>
                @break

                @case('success')
                    <span class="badge-success bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Success') }}</span>
                @break

                @case('failed')
                    <span class="badge-danger bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Canceled') }}</span>
                @break

                @case('review')
                    <span class="badge-info bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Review') }}</span>
                @break

                @case('expired')
                    <span class="bg-slate-500 text-white capitalize rounded px-2 py-1">{{ __('Expired') }}</span>
                @break
            @endswitch
        </td>

    </tr>
@endforeach
