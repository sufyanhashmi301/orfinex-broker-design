@extends('backend.setting.misc.index')
@section('title')
    {{ __('Security Setting') }}
@endsection
@section('misc-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open w-full">
            @can('all-sections-settings')
            <li class="nav-item">
                <a href="{{ route('admin.security.all-sections') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.security.all-sections') }}">
                    {{ __('All Sections') }}
                </a>
            </li>
            @endcan
            @can('blocklist-ip-settings')
            <li class="nav-item">
                <a href="{{ route('admin.security.blocklist-ip') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.security.blocklist-ip') }}">
                    {{ __('Blocklist IP') }}
                </a>
            </li>
            @endcan
            @can('single-session-settings')
            <li class="nav-item">
                <a href="{{ route('admin.security.single-session') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.security.single-session') }}">
                    {{ __('Single Session') }}
                </a>
            </li>
            @endcan
            @can('blocklist-email-settings')
            <li class="nav-item">
                <a href="{{ route('admin.security.blocklist-email') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.security.blocklist-email') }}">
                    {{ __('Blocklist Email') }}
                </a>
            </li>
            @endcan
            @can('login-expiry-settings')
            <li class="nav-item">
                <a href="{{ route('admin.security.login-expiry') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.security.login-expiry') }}">
                    {{ __('Login Expiry') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
    @yield('security-content')
@endsection
