@extends('backend.setting.platform.index')
@section('title')
    {{ __('Platform API Settings') }}
@endsection
@section('platform-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            @if(!Route::is(['admin.platform_api.db-synchronization', 'admin.platform_api.x9trader']))
                <li class="nav-item">
                    <a href="{{ route('admin.platform_api.ctrader') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.ctrader') }}">
                        {{ __('CTrader') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.platform-api') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.platform-api') }}">
                        {{ __('MetaTrader') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.webterminal') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.webterminal') }}">
                        {{ __('Web Terminal') }}
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route('admin.platform_api.db-synchronization') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.db-synchronization') }}">
                        {{ __('Metatrader 5') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.platform_api.x9trader') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.x9trader') }}">
                        {{ __('X9 Trader') }}
                    </a>
                </li>
            @endif
        </ul>
    </div>
    @yield('platform-api-content')

@endsection
