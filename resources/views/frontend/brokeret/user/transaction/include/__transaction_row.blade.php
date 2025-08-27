@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@foreach($transactions as $transaction)
    <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors duration-150">
        <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 flex items-center justify-center overflow-hidden rounded-full">
                    @switch($transaction->type->value)
                        @case('send_money')
                        <i data-lucide="arrow-up-right"></i>
                        @break
                        @case('send_money_internal')
                        <i data-lucide="arrow-up-right"></i>
                        @break
                        @case('receive_money')
                        <i data-lucide="arrow-down-left"></i>
                        @break
                        @case('deposit')
                        <i data-lucide="download"></i>
                        @break
                        @case('manual_deposit')
                        <i data-lucide="download"></i>
                        @break
                        @case('investment')
                        <i data-lucide="chart-bar"></i>
                        @break
                        @case('withdraw')
                        <i data-lucide="upload"></i>
                        @break
                        @case('voucher_deposit')
                        <i data-lucide="receipt"></i>
                        @break
                        @default()
                        <i data-lucide="banknote"></i>
                    @endswitch
                </div>
                <div>
                    <span class="block text-nowrap font-medium text-gray-800 text-theme-sm dark:text-white/90">
                        {{ $transaction->description }}
                    </span>
                    <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                        {{ $transaction->created_at }}
                    </span>
                </div>
            </div>
        </td>
        <td class="px-5 py-4 sm:px-6">
            <span class="text-gray-500 text-theme-sm dark:text-gray-400 font-mono">
                {{ $transaction->tnx }}
            </span>
        </td>
        <td class="px-5 py-4 sm:px-6">
            <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                {{ $transaction->target_id }}
            </span>
        </td>
        <td class="px-5 py-4 sm:px-6">
            <span class="text-theme-sm font-semibold {{ $transaction->type->value !== 'subtract' && $transaction->type->value !== 'investment' && $transaction->type->value !==  'withdraw' && $transaction->type->value !==  'send_money' && $transaction->type->value !== 'send_money_internal' && $transaction->type->value !==  'bonus_refund' && $transaction->type->value !==  'bonus_subtract' ? 'text-green-600 dark:text-green-400': 'text-red-600 dark:text-red-400'}}">
                {{ txn_type($transaction->type->value, ['+','-']) }}{{ $transaction->amount }} {{ $currency }}
            </span>
        </td>
        <td class="px-5 py-4 sm:px-6">
            <span class="text-nowrap text-gray-500 text-theme-sm dark:text-gray-400">
                {{ transaction_method_name($transaction) }}
            </span>
        </td>
        <td class="px-5 py-4 sm:px-6">
            <span class="text-nowrap text-gray-500 text-theme-sm dark:text-gray-400">
                {{ $transaction->charge }} {{ $currency }}
            </span>
        </td>
        <td class="px-5 py-4 sm:px-6">
            @switch($transaction->status->value)
                @case('pending')
                <x-badge variant="warning" style="light" size="sm">
                    {{ __('Pending') }}
                </x-badge>
                @break
                @case('success')
                <x-badge variant="success" style="light" size="sm">
                    {{ __('Success') }}
                </x-badge>
                @break
                @case('failed')
                <x-badge variant="error" style="light" size="sm">
                    {{ __('Canceled') }}
                </x-badge>
                @break
            @endswitch
        </td>
    </tr>
@endforeach
