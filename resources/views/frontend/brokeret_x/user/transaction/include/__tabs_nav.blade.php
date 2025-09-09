<div class="h-11 items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 lg:inline-flex dark:bg-gray-900">
    <a href="{{ route('user.history.transactions') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md group hover:text-gray-900 dark:hover:text-white text-gray-500 dark:text-gray-400 {{ isActive('user.history.transactions') ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400' }}">
        {{ __('Transactions') }}
    </a>
    <a href="{{ route('user.history.tradingAccounts') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md group hover:text-gray-900 dark:hover:text-white text-gray-500 dark:text-gray-400 {{ isActive('user.history.tradingAccounts') ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400' }}">
        {{ __('Accounts') }}
    </a>
</div>
