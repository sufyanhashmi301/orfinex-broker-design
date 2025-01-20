@extends('backend.setting.user_management.index')
@section('title')
    {{ __('Customer Setting') }}
@endsection
@section('user-management-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        @yield('title-btns')
    </div>
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 sm:pb-0 gap-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.risk-profile-tag.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.risk-profile-tag*') }}">
                    {{ __('Risk Profile Tags') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.customer-groups.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.customer-groups.index') }}">
                    {{ __('Customer Groups') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.customer.permissions') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.customer.permissions') }}">
                    {{ __('Permission') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.page.setting') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.page.setting') }}">
                    {{ __('Registration Setting') }}
                </a>
            </li>
        </ul>
    </div>
    @yield('customer-content')

@endsection
