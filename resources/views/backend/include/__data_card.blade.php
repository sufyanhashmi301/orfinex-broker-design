<!-- Primary Stats Row -->
<div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5 mb-6">
    <div class="card stats-card bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-6">
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-14 w-14 rounded-xl flex flex-col items-center justify-center text-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800">
                        <iconify-icon icon="lucide:users"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Registered User') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-2xl font-bold">
                        {{ number_format($data['register_user']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card stats-card bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-900/10 dark:to-slate-900 border border-emerald-200 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-6">
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-14 w-14 rounded-xl flex flex-col items-center justify-center text-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800">
                        <iconify-icon icon="lucide:user-check"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Active Users') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-2xl font-bold">
                        {{ number_format($data['active_user']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card stats-card bg-gradient-to-br from-amber-50 to-white dark:from-amber-900/10 dark:to-slate-900 border border-amber-200 dark:border-slate-700 hover:border-amber-300 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-6">
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-14 w-14 rounded-xl flex flex-col items-center justify-center text-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-500 ring-1 ring-amber-200 dark:ring-amber-800">
                        <iconify-icon icon="lucide:user-cog"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Staff / Team') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-2xl font-bold">
                        @if(auth()->user()->hasRole('Super-Admin'))
                            {{ $data['total_staff'] }}
                        @else
                            1
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card stats-card bg-gradient-to-br from-green-50 to-white dark:from-green-900/10 dark:to-slate-900 border border-green-200 dark:border-slate-700 hover:border-green-300 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-6">
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-14 w-14 rounded-xl flex flex-col items-center justify-center text-2xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 ring-1 ring-green-200 dark:ring-green-800">
                        <iconify-icon icon="lucide:wallet"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Total Deposits') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-bold">
                        {{ $currencySymbol }}<span class="count">{{ number_format($data['total_deposit'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Stats Row -->
<div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mb-6">
    <div class="card enhanced-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all duration-300">
        <div class="card-body p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400">
                        <iconify-icon icon="lucide:arrow-down-circle" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-slate-500 dark:text-slate-400 text-sm font-medium">
                            {{ __('Total Withdraw') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-slate-900 dark:text-white text-2xl font-bold">
                {{ $currencySymbol }}<span class="count">{{ number_format($data['total_withdraw'], 2) }}</span>
            </div>
        </div>
    </div>

    <div class="card enhanced-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all duration-300">
        <div class="card-body p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400">
                        <iconify-icon icon="lucide:users" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-slate-500 dark:text-slate-400 text-sm font-medium">
                            {{ __('Total Referral') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-slate-900 dark:text-white text-2xl font-bold count">
                {{ number_format($data['total_referral']) }}
            </div>
        </div>
    </div>

    <div class="card enhanced-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all duration-300">
        <div class="card-body p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-cyan-50 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400">
                        <iconify-icon icon="lucide:send" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-slate-500 dark:text-slate-400 text-sm font-medium">
                            {{ __('Total Transfers') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-slate-900 dark:text-white text-2xl font-bold">
                {{ $currencySymbol }}<span class="count">{{ number_format($data['total_send'], 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Stats Row -->
<div class="grid xl:grid-cols-5 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
    <div class="card enhanced-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-orange-300 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-5">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 ring-1 ring-orange-200 dark:ring-orange-800">
                        <iconify-icon icon="lucide:gift"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Total IB Bonus') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-bold">
                        {{ $currencySymbol }}<span class="count">{{ number_format($data['total_ib_bonus'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card enhanced-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-green-300 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-5">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 ring-1 ring-green-200 dark:ring-green-800">
                        <iconify-icon icon="lucide:trending-up"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Live Accounts') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-lg font-bold">
                        {{ number_format($data['total_live_forex_accounts']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card enhanced-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-5">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800">
                        <iconify-icon icon="lucide:play-circle"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Demo Accounts') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-lg font-bold">
                        {{ number_format($data['total_demo_forex_accounts']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card enhanced-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-5">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-200 dark:ring-indigo-800">
                        <iconify-icon icon="lucide:credit-card"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Total Gateways') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-lg font-bold">
                        {{ $data['total_gateway'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card enhanced-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600 transition-all duration-300">
        <div class="card-body p-5">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 ring-1 ring-slate-200 dark:ring-slate-700">
                        <iconify-icon icon="lucide:help-circle"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">
                        {{ __('Total Ticket') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-lg font-bold">
                        {{ $data['total_ticket'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>