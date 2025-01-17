@extends('backend.setting.organization.index')
@section('title')
    {{ __('Links Setting') }}
@endsection
@section('organization-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        @yield('page-title')
    </div>
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open w-full">
            @can('document-link-list')
                <li class="nav-item">
                    <a href="{{ route('admin.links.document.index') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.links.document.index') }}">
                        {{ __('Document links') }}
                    </a>
                </li>
            @endcan

            @can('platform-link-list')
                <li class="nav-item">
                    <a href="{{ route('admin.links.platform.index') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.links.platform.index') }}">
                        {{ __('Platform links') }}
                    </a>
                </li>
            @endcan

            @can('social-link-list')
                <li class="nav-item">
                    <a href="{{ route('admin.links.social.index') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.links.social.index') }}">
                        {{ __('Social links') }}
                    </a>
                </li>
            @endcan
        </ul>
    </div>
    @yield('links-content')
@endsection
