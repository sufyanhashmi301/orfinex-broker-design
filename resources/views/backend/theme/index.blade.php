@extends('backend.layouts.app')
@section('title')
    {{ __('Theme Management') }}
@endsection
@section('content')
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.theme.global') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.theme.global') }}">
                    {{ __('Global Settings') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.theme.site') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.theme.site') }}">
                    {{ __('Site Theme') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.theme.dynamic-landing') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.theme.dynamic-landing') }}">
                    {{ __('Site Dynamic Landing Theme') }}
                </a>
            </li>
            @can('page-manage')
            <li class="nav-item">
                <a href="{{ route('admin.page.setting') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.page.setting') }}">
                    <span>{{ __('Page') }}</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-5">
        @yield('theme-content')
    </div>
@endsection
