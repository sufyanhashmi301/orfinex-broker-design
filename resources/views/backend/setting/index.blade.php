@extends('backend.layouts.app')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center gap-3 mb-6">
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
        @canany(['company-setting','social-logins-list', 'misc-setting', 'company-permissions-setting', 'departments-list', 'designations-list','all-countries-list','blacklist-countries-list','document-link-list','platform-link-list'])
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
                    @canany(['company-setting', 'misc-setting', 'company-permissions-setting', 'departments-list', 'designations-list'])
                    <li>
                        <a href="{{ route('admin.settings.company') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Company') }}
                        </a>
                    </li>
                    @endcanany
                    @canany(['all-countries-list','blacklist-countries-list'])
                    <li>
                        <a href="{{ route('admin.country.all') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Country') }}
                        </a>
                    </li>
                    @endcanany
                    @canany(['document-link-list','platform-link-list'])
                    <li>
                        <a href="{{ route('admin.links.document.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Doc & Links') }}
                        </a>
                    </li>
                    @endcanany
                    @can('social-logins-list')
                    <li>
                        <a href="{{ route('admin.social.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Social Logins') }}
                            <span class="badge-success text-xs capitalize rounded-full px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    @endcan

                </ul>
            </div>
        </div>
        @endcanany
        @canany(['ranking-list','ranking-create','ranking-edit','kyc-levels-list','lead-source-list','lead-pipeline-list','role-list','role-create','role-edit','risk-profile-list','system-tag-list','customer-group-list','ib-group-list','customer-permissions','customer-registration-settings'])
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
                    @canany(['risk-profile-list','system-tag-list','customer-group-list','ib-group-list','customer-permissions','customer-registration-settings'])
                    <li>
                        <a href="{{ route('admin.customer-groups.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Customer') }}
                        </a>
                    </li>
                    @endcanany
                    @canany(['role-list','role-create','role-edit'])
                        <li>
                            <a href="{{ route('admin.roles.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('Roles & Permissions') }}
                            </a>
                        </li>
                    @endcanany
                    @canany(['lead-source-list','lead-pipeline-list'])
                    <li>
                        <a href="{{ route('admin.lead.source.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Lead Settings') }}
                        </a>
                    </li>
                    @endcanany
                    @can('kyc-levels-list')
                    <li>
                        <a href="{{ route('admin.kyclevels.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('KYC & Compliance') }}
                        </a>
                    </li>
                    @endcan
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
        @endcanany
        @canany(['automatic-gateway-manage','manual-gateway-manage','automatic-withdraw-method','manual-withdraw-method', 'withdraw-schedule','currency-setting','internal-transfer-display','external-transfer-display','bonus-list'])
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
                        @canany(['automatic-gateway-manage','manual-gateway-manage'])
                            <li>
                                <a href="{{ route('admin.deposit.method.list','auto') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                    {{ __('Deposit Methods') }}
                                </a>
                            </li>
                            @endcanany
                            @canany(['automatic-withdraw-method','manual-withdraw-method', 'withdraw-schedule'])
                            <li>
                                <a href="{{ route('admin.withdraw.method.list','auto') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                    {{ __('Withdraw Methods') }}
                                </a>
                            </li>
                        @endcanany
                        @can('currency-setting')
                    <li>
                        <a href="{{ route('admin.settings.currency') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Currency') }}
                        </a>
                    </li>
                    @endcan
                    @canany(['internal-transfer-display','external-transfer-display'])
                    <li>
                        <a href="{{ route('admin.settings.transfers', ['type' => 'transfer_internal']) }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Transfers')}}
                        </a>
                    </li>
                    @endcanany
                    @can('bonus-list')
                    <li>
                        <a href="{{ route('admin.bonus.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Bonuses')}}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        @endcanany
        @canany(['theme-settings','branding-settings','site-settings','banner-settings','gdpr-compliance-settings','maintainance-settings'])
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
                    @can('theme-settings')
                    <li>
                        <a href="{{ route('admin.theme.site') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Theme') }}
                        </a>
                    </li>
                    @endcan

                    @can('branding-settings')
                    <li>
                        <a href="{{ route('admin.theme.global') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Branding')}}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('site-settings')
                    <li>
                        <a href="{{ route('admin.settings.site') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Site Settings') }}
                        </a>
                    </li>
                    @endcan

                    @can('banner-settings')
                    <li>
                        <a href="{{ route('admin.banners') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Banner') }}
                        </a>
                    </li>
                    @endcan

                    @can('gdpr-compliance-settings')
                    <li>
                        <a href="{{ route('admin.grpdCompliance') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('GDPR Compliance') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('maintainance-settings')
                    <li>
                        <a href="{{ route('admin.settings.site-maintenance') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Maintenance') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        @endcanany
        @canany(['custom-colors-settings','custom-fonts-settings','routes-settings','dynamic-content-settings'])
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
                    @can('custom-colors-settings')
                    <li>
                        <a href="{{ route('admin.theme.colors', ['type' => 'light_colors']) }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Custom Colors') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('custom-fonts-settings')
                    <li>
                        <a href="{{ route('admin.theme.fonts') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Custom Fonts') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('routes-settings')
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Routes')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('dynamic-content-settings')
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Dynamic Content')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        @endcanany
        @canany(['c-trader-display', 'meta-trader-display', 'x9-trader-display','db-synchronization-setting','copy-trading-setting','mt5-group-list', 'manual-group-list','risk-book-list','mt5-webterminal-display', 'x9-webterminal-display'])
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
                    @canany(['c-trader-display', 'meta-trader-display', 'x9-trader-display'])
                    <li>
                        <a href="{{ route('admin.settings.platform-api') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Platform API') }}
                        </a>
                    </li>
                    @endcanany
                    @can('db-synchronization-setting')
                    <li>
                        <a href="{{ route('admin.platform_api.db-synchronization') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('DB Synchronization') }}
                        </a>
                    </li>
                    @endcan
                    @can('copy-trading-setting')
                    <li>
                        <a href="{{ route('admin.settings.copyTrading') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Copy Trading') }}
                        </a>
                    </li>
                    @endcan
                    @canany(['mt5-group-list', 'manual-group-list'])
                    <li>
                        <a href="{{ route('admin.platformGroups') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Platform Groups') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    @endcanany
                    @can('risk-book-list')
                    <li>
                        <a href="{{ route('admin.platform.riskBook') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Risk Book') }}
                        </a>
                    </li>
                    @endcan
                    @canany(['mt5-webterminal-display', 'x9-webterminal-display'])
                    <li>
                        <a href="{{ route('admin.settings.webterminal.mt5') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Web Terminal') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    @endcanany
                </ul>
            </div>
        </div>
        @endcanany
        @canany(['email-setting', 'collab-tools-setting','admin-email-template', 'user-email-template', 'admin-sms-template', 'user-sms-template'])
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
                    @can('email-setting')
                    <li>
                        <a href="{{ route('admin.settings.mail') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Email') }}
                        </a>
                    </li>
                    @endcan
                    @can('collab-tools-setting')
                    <li>
                        <a href="{{ route('admin.settings.slack') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Collab Tools') }}
                        </a>
                    </li>
                    @endcan
                    @canany(['admin-email-template', 'user-email-template'])
                        <li>
                            <a href="{{ route('admin.email-template') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('Email Templates') }}
                                <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                    {{ __('Updated') }}
                                </span>
                            </a>
                        </li>
                    @endcanany
                    @canany(['admin-sms-template', 'user-sms-template'])
                        <li>
                            <a href="{{ route('admin.template.sms.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('SMS Templates') }}
                            </a>
                        </li>
                    @endcanany
                    <li>
                        <a href="{{ route('admin.template.notification.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Notification Templates') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        @endcanany
        @canany(['import-settings', 'export-settings', 'data-encryption-settings'])
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
                    @can('import-settings')
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Import')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('export-settings')
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Export')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('data-encryption-settings')
                    <li>
                        <a href="{{ route('admin.settings.endToEndEncryption') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Data Encryption') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        @endcanany
        @canany(['clear-cache-settings', 'application-details-settings', 'dev-mode-settings', 'changelog-settings', 'report-issue-settings'])
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
                    @can('clear-cache-settings')
                    <li class="">
                        <a href="{{ route('admin.settings.clearCache') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Clear Cache') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('application-details-settings')
                    <li class="">
                        <a href="{{ route('admin.application-info') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Application Details') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('dev-mode-settings')
                    <li class="">
                        <a href="{{ route('admin.settings.devMode') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Dev Mode') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('changelog-settings')
                    <li class="">
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Changelog') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('report-issue-settings')
                    <li class="">
                        <a href="{{ route('admin.reportIssues') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Report Issue') }}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('new') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        @endcanany
        @canany(['payment-gateways-list', 'plugins-list', 'sms-list', 'notification-list', 'api-access-setting', 'web-hooks-setting'])
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
                    @can('payment-gateways-list')
                        <li>
                            <a href="{{ route('admin.gateway.automatic') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('Payment Gateways') }}
                                <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                            </a>
                        </li>
                    @endcan
                    @can('plugins-list')
                    <li class="">
                        <a href="{{ route('admin.settings.plugin','system') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Plugins') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('sms-list')
                    <li class="">
                        <a href="{{ route('admin.settings.plugin','sms') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{__('SMS Settings') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('notification-list')
                    <li class="">
                        <a href="{{ route('admin.settings.plugin','notification') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{__('Notification Settings') }}
                            <span class="badge-warning text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('Updated') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('api-access-setting')
                    <li>
                        <a href="{{ route('admin.settings.apiAccess') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('API Access')}}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @can('web-hooks-setting')
                    <li>
                        <a href="{{ route('admin.settings.webHook') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Web Hooks')}}
                            <span class="badge-success text-xs capitalize rounded-full bg-opacity-30 px-2 py-1">
                                {{ __('New') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        @endcanany
        @canany(['all-sections-settings', 'blocklist-ip-settings', 'single-session-settings', 'blocklist-email-settings', 'login-expiry-settings','language-list','multi-factor-auth-setting','ticket-type-list','ticket-category-list'])
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
                    @canany(['all-sections-settings', 'blocklist-ip-settings', 'single-session-settings', 'blocklist-email-settings', 'login-expiry-settings'])
                    <li>
                        <a href="{{ route('admin.security.all-sections') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Security')}}
                        </a>
                    </li>
                    @endcanany
                    @can('language-list')
                        <li>
                            <a href="{{ route('admin.language.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                {{ __('Language') }}
                            </a>
                        </li>
                    @endcan
                    @can('multi-factor-auth-setting')
                    <li>
                        <a href="javascript:;" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Multi-Factor Auth')}}
                            <span class="badge-secondary text-xs capitalize rounded-full px-2 py-1">
                                {{ __('Coming Soon') }}
                            </span>
                        </a>
                    </li>
                    @endcan
                    @canany(['ticket-type-list','ticket-category-list'])
                    <li>
                        <a href="{{ route('admin.ticket.label.index') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Support Center')}}
                        </a>
                    </li>
                    @endcanany
                </ul>
            </div>
        </div>
        @endcanany
    </div>
@endsection
@section('script')
    @yield('setting-script')
@endsection
