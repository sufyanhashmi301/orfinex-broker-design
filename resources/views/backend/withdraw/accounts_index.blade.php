@extends('backend.layouts.app')
@section('content')
    @yield('page-title')
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pb-1 md:pb-0 gap-4 menu-open">
            @can('withdraw-account-pending')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.pending.accounts') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.pending.accounts') }}">
                        {{ __('Pending Accounts') }}
                    </a>
                </li>
                 @endcan
                @can('withdraw-account-approve')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.approved.accounts') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.approved.accounts') }}">
                        {{ __('Approved Accounts') }}
                    </a>
                </li>
                 @endcan
                @can('withdraw-account-rejected')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.rejected.accounts') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.rejected.accounts') }}">
                        {{ __('Rejected Accounts') }}
                    </a>
                </li>
            @endcan
            <li class="nav-item !ml-auto">
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
    @yield('withdraw_content')
@endsection 