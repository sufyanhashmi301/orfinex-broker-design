@extends('backend.layouts.app')
@section('content')
    @yield('page-title')
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            @can('withdraw-list')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.pending') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.pending') }}">
                        {{ __('Pending Payouts') }}
                    </a>
                </li>
            @endcan
            @can('withdraw-list')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.history') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.history') }}">
                        {{ __('Payout History') }}
                    </a>
                </li>
            @endcan
            <li class="nav-item !ml-auto">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
                <span class="flex items-center">
                    <span>{{ __('More') }}</span>
                    <iconify-icon icon="lucide:chevron-down" class="text-xl ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                </span>
                </a>
            </li>
        </ul>

        <div class="hidden mt-5" id="filters_div">
            @yield('filters')
        </div>
    </div>
    @yield('withdraw_content')
@endsection
