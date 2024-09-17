@extends('backend.layouts.app')
@section('title')
    {{ __('setting') }}
@endsection
@section('content')
    <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5">
        <div class="card">
            <div class="card-header">
                <h4 class="text-base dark:text-white">
                    {{ __('Organization') }}
                </h4>
            </div>
            <div class="card-body p-6">
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
                        <a href="{{ route('admin.links.document-links') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Doc & Links') }}
                        </a>
                    </li>
                    <li>
                        <a href="" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Social Logins') }}
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="text-base dark:text-white">
                    {{ __('User Management') }}
                </h4>
            </div>
            <div class="card-body p-6">
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
            <div class="card-header">
                <h4 class="text-base dark:text-white">
                    {{ __('Payment') }}
                </h4>
            </div>
            <div class="card-body p-6">
                <ul class="space-y-3">
                    @canany(['automatic-gateway-manage','manual-gateway-manage','deposit-list','deposit-action',
                        'withdraw-list','withdraw-method-manage','withdraw-action','target-manage','referral-create',
                        'referral-list','referral-edit','referral-delete','ranking-list','ranking-create','ranking-edit'])
                        @canany(['automatic-gateway-manage','manual-gateway-manage','deposit-list','deposit-action'])
                            @can('automatic-gateway-manage')
                                <li>
                                    <a href="{{ route('admin.gateway.automatic') }}" class="text-sm text-slate-900 dark:text-slate-300">
                                        {{ __('Payment Gateways') }}
                                    </a>
                                </li>
                            @endcan
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
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="text-base dark:text-white">
                    {{ __('Website Setting') }}
                </h4>
            </div>
            <div class="card-body p-6">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.theme.site') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Theme') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.site') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Site Settings') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.gdpr') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('GDPR') }}
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
            <div class="card-header">
                <h4 class="text-base dark:text-white">
                    {{ __('Platform') }}
                </h4>
            </div>
            <div class="card-body p-6">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.settings.platform-api') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Platform API') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.copyTrading') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Copy Trading') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="text-base dark:text-white">
                    {{ __('Communications') }}
                </h4>
            </div>
            <div class="card-body p-6">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.settings.mail') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Email') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.plugin','system') }}" class="text-sm text-slate-900 dark:text-slate-300">
                            {{ __('Notification') }}
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
            <div class="card-header">
                <h4 class="text-base dark:text-white">
                    {{ __('Misc') }}
                </h4>
            </div>
            <div class="card-body p-6">
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
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('setting-script')
@endsection
