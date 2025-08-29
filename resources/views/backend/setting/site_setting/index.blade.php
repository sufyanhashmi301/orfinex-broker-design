@extends('backend.setting.website.index')
@section('title')
    {{ __('Site Settings') }}
@endsection
@section('website-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        @yield('title-btns')
    </div>
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open w-full">
            @can('site-settings')
            <li class="nav-item">
                <a href="{{ route('admin.settings.site') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.site') }}">
                    {{ __('Site Settings') }}
                </a>
            </li>
            @endcan
            @can('customer-registration-settings')
            <li class="nav-item">
                <a href="{{ route('admin.page.setting') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.page.setting') }}">
                    {{ __('Registration Settings') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
    @yield('site-setting-content')
@endsection
