<div class="card all-feature-mobile mb-3 mobile-screen-show">
    <div class="card-header">
        <h4 class="card-title">
            {{ __('Recent Transactions') }}
        </h4>
    </div>
    <div class="card-body p-3">
        <div class="all-feature-mobile mobile-transactions mb-3 mobile-screen-show">
            <div class="contents space-y-3">
                @foreach($recentTransactions as $transaction )
                    <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                        <div class="transaction-left w-3/4">
                            <div class="transaction-des">
                                <div class="transaction-title mb-1 dark:text-white">{{ $transaction->description }}</div>
                                <div class="transaction-id mb-1 dark:text-white">{{ $transaction->tnx }}</div>
                                <div class="transaction-date mb-1 dark:text-white">{{ $transaction->created_at }}</div>
                            </div>
                        </div>
                        <div class="transaction-right text-right">
                            <div class="transaction-amount {{ txn_type($transaction->type->value,['add','sub']) }} mb-1 dark:text-white">
                                {{txn_type($transaction->type->value,['+','-']).$transaction->amount .' '.$currency}}</div>
                            <div class="transaction-fee sub mb-1 dark:text-white">-{{  $transaction->charge.' '. $currency .' '.__('Fee') }} </div>
                            <div class="transaction-gateway mb-1 dark:text-white">{{ $transaction->method }}</div>


                            @if($transaction->status->value == \App\Enums\TxnStatus::Pending->value)
                                <div class="transaction-status text-warning">{{ __('Pending') }}</div>
                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Success->value)
                                <div class="transaction-status text-success">{{ __('Success') }}</div>
                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Failed->value)
                                <div class="transaction-status text-danger">{{ __('canceled') }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

