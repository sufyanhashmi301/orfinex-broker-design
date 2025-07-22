@extends('backend.setting.platform.index')
@section('title')
    {{ __('Platform Groups') }}
@endsection
@section('platform-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('risk-book-list')
            <a href="{{route('admin.platform.riskBook')}}" class="btn btn-sm btn-white inline-flex items-center justify-center">
                {{ __('All Risk Book') }}
            </a>
            @endcan
            @can('manual-group-create')
            <a href="javascript:;" class="btn btn-sm btn-dark inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#createManualGroupModal">
                {{ __('Add Group Manually') }}
            </a>
            @endcan
        </div>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            @can('mt5-group-list')
            <li class="nav-item">
                <a href="{{ route('admin.platformGroups') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platformGroups') }}">
                    {{ __('Meta Trader 5') }}
                </a>
            </li>
            @endcan
            @can('manual-group-list')
            <li class="nav-item">
                <a href="{{ route('admin.manual.platformGroups') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.manual.platformGroups') }}">
                    {{ __('Manual') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
    @yield('platform-group-content')

    <!-- Modal for create manual group -->
    @can('manual-group-create')
        @include('backend.platform_group.modal.__create_manual_group')
    @endcan

    <!-- Modal for update manual group -->
    @can('manual-group-edit')
        @include('backend.platform_group.modal.__edit_manual_group')
    @endcan
    <!-- Modal for delete manual group -->
    @can('manual-group-delete')
        @include('backend.platform_group.modal.__delete')
    @endcan
@endsection
