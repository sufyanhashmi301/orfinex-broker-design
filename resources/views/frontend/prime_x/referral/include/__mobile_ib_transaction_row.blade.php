@php use App\Enums\TxnStatus; use App\Enums\TxnType; use Carbon\Carbon; @endphp

@foreach($transactions as $transaction)
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
                    {{ toSiteTimezone($transaction->created_at, 'M d, Y h:i A') }}
                </div>
            </div>
        </div>
        <div class="transaction-right text-right">
            <div class="transaction-amount font-semibold dark:text-white mb-1">
                {{ txn_type($transaction->type, ['+','-']) }}{{ number_format($transaction->amount, 2) }}
            </div>
            <div class="transaction-fee dark:text-white mb-1">
                -{{ number_format($transaction->charge, 2) }} {{ $currency }}
            </div>
            <div class="transaction-gateway dark:text-white mb-1">
                {{ $transaction->method ?? '-' }}
            </div>
            <div class="transaction-status">
                @if($transaction->status == App\Enums\TxnStatus::Pending)
                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                @elseif($transaction->status == App\Enums\TxnStatus::Success)
                    <span class="badge badge-success">{{ __('Success') }}</span>
                @elseif($transaction->status == App\Enums\TxnStatus::Failed)
                    <span class="badge badge-danger">{{ __('Canceled') }}</span>
                @endif
            </div>
        </div>
    </div>
@endforeach