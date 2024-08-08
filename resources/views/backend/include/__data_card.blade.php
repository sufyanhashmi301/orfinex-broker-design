<div class="grid md:grid-cols-4 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:users"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Registered User') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        {{ $data['register_user'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:user-check"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Active Users') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        {{ $data['active_user'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:user-cog"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Site Staff') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        {{ $data['total_staff'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:wallet"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Total Deposits') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ $currencySymbol }} <span class="count">{{ round($data['total_deposit'],2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="grid md:grid-cols-3 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body p-6">
            <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                {{ __('Total Withdraw') }}
            </div>
            <div class="flex items-center text-slate-900 dark:text-white text-xl font-medium">
                {{ $currencySymbol }}<span class="count">{{ round($data['total_withdraw'],2) }}</span>
                <span class="text-sm text-success-500 ml-1">+452%</span>
            </div>
            <div class="">
                
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                {{ __('Total Referral') }}
            </div>
            <div class="flex items-center text-slate-900 dark:text-white text-xl font-medium">
                {{ $data['total_referral'] }}
                <span class="text-sm text-success-500 ml-1">+452%</span>
            </div>
            <div class="">
                
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                {{ __('Total Send') }}
            </div>
            <div class="flex items-center text-slate-900 dark:text-white text-xl font-medium">
                {{ $currencySymbol }}<span class="count">{{ round($data['total_send'],2) }}</span>
                <span class="text-sm text-success-500 ml-1">+452%</span>
            </div>
            <div class="">
                
            </div>
        </div>
    </div>
</div>
<div class="grid md:grid-cols-5 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:droplet"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Total Investment') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ $currencySymbol }}<span class="count">{{ round($data['total_investment'],2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:package-plus"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Live Accounts') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        16
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:sprout"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Demo Accounts') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        12
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:webhook"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Total Gateways') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        {{ $data['total_gateway'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:help-circle"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Total Ticket') }}
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        {{ $data['total_ticket'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
