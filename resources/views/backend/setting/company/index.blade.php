@extends('backend.setting.index')
@section('title')
    {{ __('Company Setting') }}
@endsection
@section('setting-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @yield('title-btn')
        </div>
    </div>

    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.settings.company') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.company') }}">
                    {{ __('Company') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.departments.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.departments*') }}">
                    {{ __('Departments') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.designations.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.designations*') }}">
                    {{ __('Designations') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.misc') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.misc') }}">
                    {{ __('Misc') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.company.permissions') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.company.permissions') }}">
                    {{ __('Permission') }}
                </a>
            </li>
        </ul>
    </div>
@yield('company-content')
@endsection
