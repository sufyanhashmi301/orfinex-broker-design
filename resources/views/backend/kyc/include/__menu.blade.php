<div class="card p-4 mb-5">
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
        @canany(['kyc-list','kyc-action'])
            <li class="nav-item">
                <a href="{{ route('admin.kyc.all') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc.all') }}">
                    {{ __('All KYC Logs') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kyc.pending') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc.pending') }}">
                    {{ __('Level 2 Pending KYC') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kyc.level3.pending') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc.level3.pending') }}">
                    {{ __('Level 3 Pending KYC') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kyc.rejected') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc.rejected') }}">
                    {{ __('Rejected KYC') }}
                </a>
            </li>
        @endcanany
        @can('kyc-form-manage')
            <li class="nav-item">
                <a href="{{ route('admin.kyc-form.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc-form*') }}">
                    {{ __('KYC Form') }}
                </a>
            </li>
        @endcan
        <li class="nav-item">
            <a href="{{ route('admin.risk-profile-tag.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.risk-profile-tag*') }}">
                {{ __('Risk Profile Tag Form') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.kyclevels.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyclevels*') }}">
                {{ __('KYC Levels') }}
            </a>
        </li>
    </ul>
</div>