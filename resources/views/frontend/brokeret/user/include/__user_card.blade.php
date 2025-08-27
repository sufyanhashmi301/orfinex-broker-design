<div class="grid rounded-2xl border border-gray-200 bg-white sm:grid-cols-2 xl:grid-cols-3 dark:border-gray-800 dark:bg-white/[0.03] mb-3">
    <div class="border-b border-gray-200 px-6 py-5 sm:border-r xl:border-b-0 dark:border-gray-800">
        <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Total Revenue') }}
        </span>
        <div class="mt-2 flex items-end gap-3">
            <h4 class="text-title-xs sm:text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{ $dataCount['total_forex_balance'] }}
            </h4>
        </div>
    </div>
    <div class="border-b border-gray-200 px-6 py-5 xl:border-r xl:border-b-0 dark:border-gray-800">
        <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Current Equity') }}
        </span>
        <div class="mt-2 flex items-end gap-3">
            <h4 class="text-title-xs sm:text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{$dataCount['total_forex_equity']}}
            </h4>
        </div>
    </div>
    <div class="px-6 py-5">
        <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Wallet Balance') }}
        </span>
        <div class="mt-2 flex items-end gap-3">
            <h4 class="text-title-xs sm:text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{ user_balance() }}
            </h4>
        </div>
    </div>
</div>