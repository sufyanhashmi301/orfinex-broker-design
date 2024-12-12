@extends('backend.setting.system.index')
@section('title')
    {{ __('Application Details') }}
@endsection
@section('system-content')
    <div class="card bg-dark p-6 mb-6">
        <h4 class="card-title">{{ setting('site_title', 'global') }}</h4>
        <p class="card-text my-2">{{ __('Enterprise CRM Platform') }}</p>
        <ul class="flex items-center gap-3">
            <li class="badge badge-secondary">
                {{ __('Version 3.0') }}
            </li>
            <li class="badge badge-secondary">
                {{ __('Enterprise License') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header noborder">
            <h4 class="card-title">{{ __('Application Details') }}</h4>
            <span class="badge badge-secondary">{{ __('Enterprise Edition') }}</span>
        </div>
        <div class="card-body space-y-5 p-6 pt-3">
            <div>
                <p class="flex items-center text-lg font-medium dark:text-white mb-3">
                    <iconify-icon class="text-xl mr-2" icon="lucide:activity"></iconify-icon>
                    <span>{{ __('System Performance') }}</span>
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('System Uptime') }}</span>
                        <span class="badge bg-success bg-opacity-10">{{ __('99.99%') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Average Response Time') }}</span>
                        <span class="badge badge-secondary">{{ __('120ms') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Resource Utilization') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('Optimal') }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <p class="flex items-center text-lg font-medium dark:text-white mb-3">
                    <iconify-icon class="text-xl mr-2" icon="uil:processor"></iconify-icon>
                    <span>{{ __('Technology Platform') }}</span>
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Infrastructure') }}</span>
                        <span class="badge bg-success bg-opacity-10">{{ __('AWS Enterprise') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Data Center Region') }}</span>
                        <span class="badge badge-secondary">{{ __('EU-West') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Architecture') }}</span>
                        <span class="badge badge-secondary">{{ __('Microservices') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('API Gateway') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('v3.0') }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <p class="flex items-center text-lg font-medium dark:text-white mb-3">
                    <iconify-icon class="text-xl mr-2" icon="lucide:building"></iconify-icon>
                    <span>{{ __('Company Information') }}</span>
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Organization Type') }}</span>
                        <span class="badge bg-success bg-opacity-10">{{ __('Enterprise') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Industry Sector') }}</span>
                        <span class="badge badge-secondary">{{ __('Financial Technology') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Customer Tier') }}</span>
                        <span class="badge badge-secondary">{{ __('Premium Partner') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Support SLA') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('24/7 Priority') }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <p class="flex items-center text-lg font-medium dark:text-white mb-3">
                    <iconify-icon class="text-xl mr-2" icon="lucide:key"></iconify-icon>
                    <span>{{ __('License Information') }}</span>
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Subscription Tier') }}</span>
                        <span class="badge bg-success bg-opacity-10">{{ __('Enterprise Plus') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Contract Term') }}</span>
                        <span class="badge badge-secondary">{{ __('Annual') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Next Renewal') }}</span>
                        <span class="badge badge-secondary">{{ __('Dec 31, 2099') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('User Capacity') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('Enterprise (Unlimited)') }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <p class="flex items-center text-lg font-medium dark:text-white mb-3">
                    <iconify-icon class="text-xl mr-2" icon="lucide:shield"></iconify-icon>
                    <span>{{ __('Compliance & Security') }}</span>
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Security Status') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('Enhanced') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Compliance Level') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('ISO 27001') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Data Residency') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('EU Compliant') }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <p class="flex items-center text-lg font-medium dark:text-white mb-3">
                    <iconify-icon class="text-xl mr-2" icon="mdi:bar-chart"></iconify-icon>
                    <span>{{ __('Usage Analytics') }}</span>
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Active Users') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('1,234') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('API Requests') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('2.1M / month') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 rounded-lg px-2 py-3">
                        <span>{{ __('Storage Usage') }}</span>
                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ __('48%') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
