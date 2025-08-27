<div class="col-span-12">
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('Recent Transactions') }}
            </h3>
            <div class="flex items-center gap-3">
                <x-text-link href="{{ route('user.history.transactions') }}" variant="ghost" size="sm" icon="arrow-right" icon-position="right">
                    {{ __('See All') }}
                </x-text-link>
            </div>
        </div>

        @if(count($recentTransactions) == 0)
            <div class="flex items-center justify-center flex-col gap-4 py-12">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg width="32" height="32" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        {{ __("No transactions yet") }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        {{ __("Start trading by making your first deposit") }}
                    </p>
                    <a href="{{ route('user.deposit.methods') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 transition-colors duration-200">
                        {{ __('Deposit Now') }}
                    </a>
                </div>
            </div>
        @else
            <!-- Desktop Table View -->
            <div class="hidden lg:block">
                <div class="w-full overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr class="border-y border-gray-100 dark:border-gray-800">
                                <th class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ __('Description') }}
                                    </span>
                                </th>
                                <th class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ __('Transaction ID') }}
                                    </span>
                                </th>
                                <th class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ __('Account') }}
                                    </span>
                                </th>
                                <th class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ __('Amount') }}
                                    </span>
                                </th>
                                <th class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ __('Gateway') }}
                                    </span>
                                </th>
                                <th class="px-5 py-3 text-left sm:px-6">
                                    <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ __('Status') }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors duration-150">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 flex items-center justify-center overflow-hidden rounded-full">
                                            @switch($transaction->type->value)
                                                @case('send_money')
                                                @case('send_money_internal')
                                                <i data-lucide="arrow-up-right" class="text-xl"></i>
                                                @break
                                                @case('receive_money')
                                                <i data-lucide="arrow-down-left" class="text-xl"></i>
                                                @break
                                                @case('deposit')
                                                @case('manual_deposit')
                                                <i data-lucide="download" class="text-xl"></i>
                                                @break
                                                @case('investment')
                                                <i data-lucide="chart-bar" class="text-xl"></i>
                                                @break
                                                @case('withdraw')
                                                <i data-lucide="upload" class="text-xl"></i>
                                                @break
                                                @case('voucher_deposit')
                                                <i data-lucide="receipt" class="text-xl"></i>
                                                @break
                                                @default
                                                <i data-lucide="banknote" class="text-xl"></i>
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
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $transaction->method }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    @if($transaction->status->value == \App\Enums\TxnStatus::Pending->value)
                                        <x-badge variant="warning" style="light" size="sm">
                                            {{ __('Pending') }}
                                        </x-badge>
                                    @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Success->value)
                                        <x-badge variant="success" style="light" size="sm">
                                            {{ __('Success') }} 
                                        </x-badge>
                                    @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Failed->value)
                                        <x-badge variant="error" style="light" size="sm">
                                            {{ __('Canceled') }}
                                        </x-badge>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="block lg:hidden space-y-3">
                @foreach($recentTransactions as $transaction)
                <div class="bg-gray-50/50 dark:bg-white/[0.02] rounded-xl p-4 border border-gray-200 dark:border-gray-700 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-lg flex items-center justify-center shadow-sm">
                                @switch($transaction->type->value)
                                    @case('send_money')
                                    @case('send_money_internal')
                                    <iconify-icon icon="heroicons:arrow-up-right" class="text-lg text-red-500"></iconify-icon>
                                    @break
                                    @case('receive_money')
                                    <iconify-icon icon="heroicons:arrow-down-left" class="text-lg text-green-500"></iconify-icon>
                                    @break
                                    @case('deposit')
                                    @case('manual_deposit')
                                    <iconify-icon icon="heroicons:plus" class="text-lg text-green-500"></iconify-icon>
                                    @break
                                    @case('investment')
                                    <iconify-icon icon="heroicons:chart-bar" class="text-lg text-blue-500"></iconify-icon>
                                    @break
                                    @case('withdraw')
                                    <iconify-icon icon="heroicons:minus" class="text-lg text-red-500"></iconify-icon>
                                    @break
                                    @default
                                    <iconify-icon icon="heroicons:banknotes" class="text-lg text-gray-500"></iconify-icon>
                                @endswitch
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900 dark:text-white/90 mb-1">
                                    {{ $transaction->description }}
                                    @if(!in_array($transaction->approval_cause,['none',""]))
                                        <span class="toolTip onTop optional-msg ml-1" data-tippy-content="{{ $transaction->approval_cause }}">
                                            <iconify-icon icon="heroicons:information-circle" class="text-blue-500"></iconify-icon>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    ID: {{ $transaction->tnx }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold mb-2 {{ $transaction->type->value !== 'subtract' && $transaction->type->value !== 'investment' && $transaction->type->value !==  'withdraw' && $transaction->type->value !==  'send_money' && $transaction->type->value !== 'send_money_internal' && $transaction->type->value !==  'bonus_refund' && $transaction->type->value !==  'bonus_subtract' ? 'text-green-600 dark:text-green-400': 'text-red-600 dark:text-red-400'}}">
                                {{ txn_type($transaction->type->value, ['+','-']) }}{{ $transaction->amount }} {{ $currency }}
                            </div>
                            @if($transaction->status->value == \App\Enums\TxnStatus::Pending->value)
                                <x-badge variant="warning" style="light" size="sm">
                                    {{ __('Pending') }}
                                </x-badge>
                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Success->value)
                                <x-badge variant="success" style="light" size="sm">
                                    {{ __('Success') }}
                                </x-badge>
                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Failed->value)
                                <x-badge variant="error" style="light" size="sm">
                                    {{ __('Canceled') }}
                                </x-badge>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ __('Account') }}: {{ $transaction->target_id }}</span>
                            <span>{{ __('Via') }}: {{ $transaction->method }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($recentTransactions->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">{{ __('No Data Found') }}</p>
                </div>
            @endif
        @endif
    </div>
</div>
