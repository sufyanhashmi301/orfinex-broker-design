@extends('backend.layouts.app')
@section('title')
    {{ __('setting') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <div class="input-area relative">
            <div class="relative">
                <input type="text" class="form-control !pr-9" placeholder="Search">
                <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">
                    <iconify-icon icon="heroicons-solid:search"></iconify-icon>
                </button>
            </div>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.settings.documentation') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                {{ __('Documentation') }}
            </a>
        </div>
    </div>

    <div class="grid xl:grid-cols-5 lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5">
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons:presentation-chart-line"></iconify-icon>
                        {{ __('Organization') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.settings.company') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Company') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.country.all') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Country') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.links.document.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Doc & Links') }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Social Logins') }}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:user-round-cog"></iconify-icon>
                        {{ __('User Management') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.customer-groups.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Customer') }}
                        </a>
                    </li>
                    @canany(['role-list','role-create','role-edit'])
                        <li>
                            <a href="{{ route('admin.roles.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('Roles & Permissions') }}
                            </a>
                        </li>
                    @endcanany
                    <li>
                        <a href="{{ route('admin.kyclevels.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('KYC & Compliance') }}
                        </a>
                    </li>
                    @canany(['ranking-list','ranking-create','ranking-edit'])
                        <li>
                            <a href="{{ route('admin.ranking.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('User Rankings') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="tabler:settings-dollar"></iconify-icon>
                        {{ __('Payment') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    @canany(['automatic-gateway-manage','manual-gateway-manage','deposit-list','deposit-action',
                        'withdraw-list','withdraw-method-manage','withdraw-action','target-manage','referral-create',
                        'referral-list','referral-edit','referral-delete','ranking-list','ranking-create','ranking-edit'])
                        @canany(['automatic-gateway-manage','manual-gateway-manage','deposit-list','deposit-action'])
                            <li>
                                <a href="{{ route('admin.deposit.method.list','auto') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                    {{ __('Deposit Methods') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.withdraw.method.list','auto') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                    {{ __('Withdraw Methods') }}
                                </a>
                            </li>
                        @endcanany
                    @endcanany
                    <li>
                        <a href="{{ route('admin.settings.currency') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Currency') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.transfers', ['type' => 'internal']) }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Transfers')}}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bonus.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Bonuses')}}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons:globe-alt"></iconify-icon>
                        {{ __('Website Setting') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.theme.site') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Theme') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.theme.global') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Branding')}}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.site') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Site Settings') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.banners') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Banner') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.grpdCompliance') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('GDPR Compliance') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.site-maintenance') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Maintenance') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="gala:settings"></iconify-icon>
                        {{ __('Customization') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.theme.colors', ['type' => 'light_colors']) }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Custom Colors') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.theme.fonts') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Custom Fonts') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Routes')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Dynamic Content')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="uil:processor"></iconify-icon>
                        {{ __('Platform') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.settings.platform-api') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Platform API') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.platform_api.db-synchronization') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('DB Synchronization') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.copyTrading') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Copy Trading') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.platformGroups') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Platform Groups') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.platform.riskBook') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Risk Book') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.webterminal.mt5') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Web Terminal') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="fluent:person-chat-24-regular"></iconify-icon>
                        {{ __('Communications') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.settings.mail') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Email') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.slack') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Collab Tools') }}
                        </a>
                    </li>
                    @can('email-template')
                        <li>
                            <a href="{{ route('admin.email-template') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('Email Templates') }}
                                <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                    {{ __('Updated') }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.template.sms.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('SMS Templates') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="mdi:database-cog-outline"></iconify-icon>
                        {{ __('Data Management') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Import')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Export')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.endToEndEncryption') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Data Encryption') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="mdi:monitor-screenshot"></iconify-icon>
                        {{ __('System') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li class="">
                        <a href="{{ route('admin.settings.clearCache') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Clear Cache') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.application-info') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Application Details') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.settings.devMode') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Dev Mode') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.changelog') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Changelog') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.reportIssues') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Report Issue') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="fluent:settings-cog-multiple-24-regular"></iconify-icon>
                        {{ __('Integrations') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    @can('automatic-gateway-manage')
                        <li>
                            <a href="{{ route('admin.gateway.automatic') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('Payment Gateways') }}
                                <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                            </a>
                        </li>
                    @endcan
                    <li class="">
                        <a href="{{ route('admin.settings.plugin','system') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Plugins') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.settings.plugin','sms') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{__('SMS Settings') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.settings.plugin','notification') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{__('Notification Settings') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.apiAccess') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('API Access')}}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.webHook') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Web Hooks')}}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="border-b border-slate-100 dark:border-slate-700 p-3">
                <h4 class="text-base dark:text-white">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:layout-list"></iconify-icon>
                        {{ __('Misc') }}
                    </span>
                </h4>
            </div>
            <div class="card-body p-3">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.security.all-sections') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Security')}}
                        </a>
                    </li>
                    @can('language-setting')
                        <li>
                            <a href="{{ route('admin.language.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('Language') }}
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Multi-Factor Auth')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ticket.statuses.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Support Center')}}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @yield('setting-script')
@endsection
