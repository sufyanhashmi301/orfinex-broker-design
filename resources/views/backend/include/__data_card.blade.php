<div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5">
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
                        {{ __('Registered Users') }}
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
                        <iconify-icon icon="lucide:badge-check"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{-- {{ __('Site Staff') }} --}}
                        Total Active Accounts
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        {{-- {{ $data['total_staff'] }} --}}
                        {{ $data['total_active_accounts'] }}
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
                        <iconify-icon icon="lucide:badge-alert"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{-- {{ __('Total Payments') }} --}}
                        Total Violated Accounts
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{-- {{ $currencySymbol }} <span class="count">{{ round($data['total_deposit'],2) }}</span> --}}
                        {{ $data['total_violated_accounts'] }}
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
                {{ __('Total Payout') }}
            </div>
            <div class="flex items-center text-slate-900 dark:text-white text-xl font-medium">
                {{ $currencySymbol }}<span class="count">{{ $data['total_payout'] }}</span>
                {{-- <span class="text-sm text-success-500 ml-1">+452%</span> --}}
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
                {{ $currencySymbol }}{{ $data['total_referral'] }}
                {{-- <span class="text-sm text-success-500 ml-1">+452%</span> --}}
            </div>
            <div class="">

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                {{ __('Total Withdrawn Amount') }}
            </div>
            <div class="flex items-center text-slate-900 dark:text-white text-xl font-medium">
                {{-- {{ $currencySymbol }}<span class="count">{{ round($data['total_send'],2) }}</span> --}}
                {{ $currencySymbol }}<span class="count">0.00</span>
                {{-- <span class="text-sm text-success-500 ml-1">+452%</span> --}}
            </div>
            <div class="">

            </div>
        </div>
    </div>
</div>
<div class="grid lg:grid-cols-5 md:grid-cols-2 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body p-5">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900 text-danger-500">
                        <iconify-icon icon="lucide:sword"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{-- {{ __('Total Investment') }} --}}
                        Total Challenge Accounts
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{-- {{ $currencySymbol }}<span class="count">{{ round($data['total_investment'],2) }}</span> --}}
                        {{ $data['total_challenge_accounts'] }}
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
                        <iconify-icon icon="lucide:hand-coins"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{-- {{ __('Live Accounts') }} --}}
                        Total Funded Accounts
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        {{ $data['total_funded_accounts'] }}
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
                        {{-- {{ __('Demo Accounts') }} --}}
                        Total Trial Accounts
                    </div>
                    <div class="count text-slate-900 dark:text-white text-xl font-medium">
                        {{ $data['total_trial_accounts'] }}
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
