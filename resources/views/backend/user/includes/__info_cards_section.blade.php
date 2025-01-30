<div class="innerMenu">
    <div class="card overflow-hidden mb-5">
    <div class="card-body py-1">
        <div class="grid md:grid-cols-3 col-span-1 gap-px bg-slate-100 dark:bg-slate-700">
            <div class="bg-white dark:bg-slate-800 p-4">
                <div class="text-center space-y-2">
                    <p
                        class="flex items-center justify-center text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                        Allotted Funds
                        <iconify-icon class="toolTip onTop ml-1" icon="lucide:info"
                            data-tippy-content="Accumulative allotted funds of all the active accounts."></iconify-icon>
                    </p>
                    <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ number_format($all_accounts->pluck('accountTypePhaseRule')->flatten()->sum('allotted_funds'), 0) }}
                        {{ $currency }}
                    </h6>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4">
                <div class="text-center space-y-2">
                    <p
                        class="flex items-center justify-center text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                        Wallets Balance
                        <iconify-icon class="toolTip onTop ml-1" icon="lucide:info"
                            data-tippy-content="Funds available in payout and affiliate wallets."></iconify-icon>
                    </p>
                    <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ number_format($wallets_balance, 2) }} {{ $currency }}
                    </h6>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4">
                <div class="text-center space-y-2">
                    <p
                        class="flex items-center justify-center text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                        Total Withdraw
                        <iconify-icon class="toolTip onTop ml-1" icon="lucide:info"
                            data-tippy-content="Total amount withdrawn by user (only approved)."></iconify-icon>
                    </p>
                    <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ number_format($total_approved_withdraws, 2) }} {{ $currency }}
                    </h6>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="grid md:grid-cols-3 grid-cols-1 gap-4 mb-5">
    <!-- BEGIN: Group Chart -->
    <div class="card">
        <div class="card-body pt-4 pb-3 px-4">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900">
                        <iconify-icon icon="lucide:check"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div
                        class="flex items-center text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        Active Accounts
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        {{ $all_accounts->where('status', \App\Enums\InvestmentStatus::ACTIVE)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body pt-4 pb-3 px-4">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900">
                        <iconify-icon icon="lucide:x"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div
                        class="flex items-center text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        Violated Accounts
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        {{ $all_accounts->where('status', \App\Enums\InvestmentStatus::VIOLATED)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body pt-4 pb-3 px-4">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900">
                        <iconify-icon icon="lucide:clock"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div
                        class="flex items-center text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        Pending Accounts
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        {{ $all_accounts->where('status', \App\Enums\InvestmentStatus::PENDING)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Group Chart -->
    </div>
</div>
