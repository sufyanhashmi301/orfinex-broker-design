@extends('backend.layouts.app')
@section('content')
    @yield('page-title')
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open">
            @canany(['deposit-list'])
                <li class="nav-item">
                    <a href="{{ route('admin.deposit.manual.pending') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.deposit.manual.pending') }}">
                        {{ __('Manual Pending Deposit') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.deposit.history') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.deposit.history') }}">
                        {{ __('Deposit History') }}
                    </a>
                </li>
            @endcanany
            @can('deposit-add')
            <li class="nav-item !ml-auto">
                <a href="{{ route('admin.deposit.add') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.deposit.add') }}">
                    <span class="flex items-center">
                        <iconify-icon icon="lucide:plus" class="text-base ltr:mr-2 rtl:ml-2 font-light"></iconify-icon>
                        <span>{{ __('Add Deposit') }}</span>
                    </span>
                </a>
            </li>
            @endcan
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
                    <span class="flex items-center">
                        <span>{{ __('More') }}</span>
                        <iconify-icon icon="lucide:chevron-down" class="text-base ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                    </span>
                </a>
            </li>
        </ul>
        <div class="hidden mt-5" id="filters_div">
            @yield('filters')
        </div>
    </div>
    @yield('deposit_content')
@endsection
