@extends('backend.setting.website.index')
@section('title')
    {{ __('Theme Management') }}
@endsection
@section('website-content')

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.theme.site') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.theme.site') }}">
                    {{ __('Template Settings') }}
                </a>
            </li>
        </ul>
    </div>
    @yield('theme-content')
@endsection
