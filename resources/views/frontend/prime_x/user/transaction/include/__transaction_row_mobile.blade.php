@foreach ($transactions as $transaction)
    <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
        <div class="transaction-left w-3/4">
            <div class="transaction-des">
                <div class="transaction-title font-semibold dark:text-white mb-1">
                    {{ $transaction->description }}
                </div>
                <div class="transaction-id dark:text-white mb-1">
                    {{ $transaction->tnx }}
                </div>
                <div class="transaction-date dark:text-white mb-1">
                    {{ $transaction->display_time->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>
        <div class="transaction-right text-right">
            <div class="transaction-amount font-semibold dark:text-white mb-1">
                {{ txn_type($transaction->type->value, ['+', '-']) }}{{ number_format($transaction->amount, 2) }}
                {{ $currency }}
            </div>
            <div class="transaction-fee dark:text-white mb-1">
                -{{ number_format($transaction->charge, 2) }} {{ $currency }}
            </div>
            <div class="transaction-gateway dark:text-white mb-1">
                {{ $transaction->method ?? '-' }}
            </div>
            <div class="transaction-status">
                @if ($transaction->status->value == App\Enums\TxnStatus::Pending->value)
                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                @elseif($transaction->status->value == App\Enums\TxnStatus::Success->value)
                    <span class="badge badge-success">{{ __('Success') }}</span>
                @elseif($transaction->status->value == App\Enums\TxnStatus::Failed->value)
                    <span class="badge badge-danger">{{ __('Canceled') }}</span>
                @elseif($transaction->status->value == App\Enums\TxnStatus::Review->value)
                    <span class="badge badge-info">{{ __('Review') }}</span>
                @elseif($transaction->status->value == App\Enums\TxnStatus::Expired->value)
                    <span class="badge bg-slate-500 text-white">{{ __('Expired') }}</span>
                @endif
            </div>
        </div>
    </div>
@endforeach
