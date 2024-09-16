@extends('backend.setting.organization.index')
@section('title')
    {{ __('Links Setting') }}
@endsection
@section('organization-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.links.document-links') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.links.document-links') }}">
                    {{ __('Document links') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.links.platform-links') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.links.platform-links') }}">
                    {{ __('Platform links') }}
                </a>
            </li>
        </ul>
    </div>
    @yield('links-content')
@endsection
