<p class="text-slate-400 dark:text-slate-50 text-sm mb-1">{{ __('Recent Transactions') }}</p>
@if(count($recentTransactions) == 0)
    <div class="card flex items-center justify-center flex-col p-4">
        <svg width="42" height="43" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <p class="text-sm text-slate-600 dark:text-slate-100 my-3">
            {{ __("You don't have any transactions yet.") }}
        </p>
    </div>
@else
    <div class="card all-feature-mobile mb-3 mobile-screen-show">
        <div class="card-body p-3">
            <div class="all-feature-mobile mobile-transactions mb-3 mobile-screen-show">
                <div class="contents space-y-3">
                    @foreach($recentTransactions as $transaction )
                        <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                            <div class="transaction-left w-3/4">
                                <div class="transaction-des">
                                    <div class="transaction-title font-semibold mb-1 dark:text-white">{{ $transaction->description }}</div>
                                    <div class="transaction-id mb-1 dark:text-white">{{ $transaction->tnx }}</div>
                                    <div class="transaction-date mb-1 dark:text-white">{{ $transaction->created_at }}</div>
                                </div>
                            </div>
                            <div class="transaction-right text-right">
                                <div class="transaction-amount {{ txn_type($transaction->type->value,['add','sub']) }} font-semibold mb-1 dark:text-white">
                                    {{ txn_type($transaction->type->value,['+','-']).$transaction->amount .' '.$currency }}
                                </div>
                                <div class="transaction-fee sub mb-1 dark:text-white">-{{  $transaction->charge.' '. $currency .' '.__('Fee') }} </div>
                                <div class="transaction-gateway mb-1 dark:text-white">{{ $transaction->method }}</div>
                                <div class="transaction-status">
                                    @if($transaction->status->value == \App\Enums\TxnStatus::Pending->value)
                                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                                    @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Success->value)
                                        <span class="badge badge-success">{{ __('Success') }}</span>
                                    @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Failed->value)
                                        <span class="badge badge-danger">{{ __('Canceled') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
