<p class="text-gray-400 dark:text-gray-50 text-sm mb-1">{{ __('Recent Transactions') }}</p>
@if(count($recentTransactions) == 0)
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-center flex-col p-4">
            <svg width="42" height="43" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-gray-600 dark:text-gray-100 my-3">
                {{ __("You don't have any transactions yet.") }}
            </p>
        </div>
    </div>
@else
    <div class="all-feature-mobile mobile-transactions mb-3 mobile-screen-show">
        <div class="contents space-y-3">
            @foreach($recentTransactions as $transaction )
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
                                {{ $transaction->created_at }}
                            </div>
                        </div>
                    </div>
                    <div class="transaction-right text-right ml-4 flex-shrink-0">
                        <div class="transaction-amount {{ txn_type($transaction->type->value,['add','sub']) }} font-semibold mb-2 text-sm dark:text-white">
                            {{ txn_type($transaction->type->value,['+','-']).$transaction->amount .' '.$currency }}
                        </div>
                        <div class="transaction-fee sub mb-1 text-xs text-gray-500 dark:text-gray-400">-{{  $transaction->charge.' '. $currency .' '.__('Fee') }} </div>
                        <div class="transaction-gateway mb-2 text-xs text-gray-600 dark:text-gray-300">{{ $transaction->method }}</div>
                        <div class="transaction-status">
                            @if($transaction->status->value == \App\Enums\TxnStatus::Pending->value)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                    {{ __('Pending') }}
                                </span>
                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Success->value)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    {{ __('Success') }}
                                </span>
                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Failed->value)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                    {{ __('Canceled') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
