@extends('backend.setting.platform.index')
@section('title')
    <div>
        <h4 class="font-medium text-xl capitalize dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-1">
            {{ __('Platform API Settings') }}
        </h4>
        <p class="text-sm text-slate-500 dark:text-slate-300">
            {{ __('') }}
        </p>
    </div>
@endsection
@section('platform-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        @yield('title')
    </div>
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open mb-6">
        @if(Route::is(['admin.platform_api.db-synchronization', 'admin.platform_api.dbX9trader']))
            <li class="nav-item">
                <a href="{{ route('admin.platform_api.db-synchronization') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.db-synchronization') }}">
                    <span class="flex items-center">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="uil:processor"></iconify-icon>
                        <span>{{ __('Metatrader 5') }}</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.platform_api.dbX9trader') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.dbX9trader') }}">
                    <span class="flex items-center">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="uil:processor"></iconify-icon>
                        <span>{{ __('X9 Trader') }}</span>
                    </span>
                </a>
            </li>
        @elseif(Route::is(['admin.settings.webterminal.mt5', 'admin.settings.webterminal.x9']))
            <li class="nav-item">
                <a href="{{ route('admin.settings.webterminal.mt5') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.webterminal.mt5') }}">
                    {{ __('Metatrader 5') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.webterminal.x9') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.webterminal.x9') }}">
                    {{ __('X9 Trader') }}
                </a>
            </li>
        @else
            <li class="nav-item">
                <a href="{{ route('admin.platform_api.ctrader') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.ctrader') }}">
                    {{ __('CTrader') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.platform-api') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.platform-api') }}">
                    {{ __('MetaTrader') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.platform_api.x9trader') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.x9trader') }}">
                    {{ __('X9 Trader') }}
                </a>
            </li>
        @endif
    </ul>
    @yield('platform-api-content')

@endsection
