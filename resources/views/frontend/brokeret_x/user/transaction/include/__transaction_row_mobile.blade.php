@foreach($transactions as $transaction)
<div class="flex justify-between gap-3 rounded-xl border border-gray-200 bg-white p-4 px-2 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="transaction-left flex-1 min-w-0">
        <div class="transaction-des">
            <div class="transaction-title text-sm font-semibold mb-2 text-gray-900 dark:text-white">
                {{ $transaction->description }}
            </div>
            <div class="transaction-id text-xs text-gray-500 dark:text-gray-400 mb-1 font-mono">
                {{ $transaction->tnx }}
            </div>
            <div class="transaction-date text-xs text-gray-500 dark:text-gray-400 mb-1">
                {{ $transaction->display_time->format('M d, Y h:i A') }}
            </div>
        </div>
    </div>
    <div class="transaction-right text-right ml-4 flex-shrink-0">
        <div class="transaction-amount {{ txn_type($transaction->type->value,['add','sub']) }} font-semibold mb-2 text-sm dark:text-white">
            {{ txn_type($transaction->type->value, ['+','-']) }}{{ number_format($transaction->amount, 2) }} {{ $currency }}
        </div>
        <div class="transaction-fee sub mb-1 text-xs text-gray-500 dark:text-gray-400">
            -{{  $transaction->charge.' '. $currency .' '.__('Fee') }}
        </div>
        <div class="transaction-gateway mb-2 text-xs text-gray-600 dark:text-gray-300">
            {{ $transaction->method ?? '-' }}
        </div>
        <div class="transaction-status">
            @if($transaction->status->value == App\Enums\TxnStatus::Pending->value)
                <x-badge variant="warning" style="light" size="sm">
                    {{ __('Pending') }}
                </x-badge>
            @elseif($transaction->status->value == App\Enums\TxnStatus::Success->value)
                <x-badge variant="success" style="light" size="sm">
                    {{ __('Success') }}
                </x-badge>
            @elseif($transaction->status->value == App\Enums\TxnStatus::Failed->value)
                <x-badge variant="error" style="light" size="sm">
                    {{ __('Canceled') }}
                </x-badge>
            @endif
        </div>
    </div>
</div>
@endforeach
