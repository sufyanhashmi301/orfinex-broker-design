@extends('backend.layouts.app')
@section('content')
    @yield('page-title')
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            @can('automatic-gateway-manage')
                <li class="nav-item">
                    <a href="{{ route('admin.deposit.method.list','auto') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.deposit.method.list','auto') . isActive('admin.deposit.method.create','auto'). isActive('admin.deposit.method.edit','auto')  }}">
                        {{ __('Automatic Method') }}
                    </a>
                </li>
            @endcan

            @can('manual-gateway-manage')
                <li class="nav-item">
                    <a href="{{ route('admin.deposit.method.list','manual') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.deposit.method.list','manual') . isActive('admin.deposit.method.create','manual') . isActive('admin.deposit.method.edit','manual') }}">
                        {{ __('Manual Method') }}
                    </a>
                </li>
            @endcan
            @canany(['deposit-list','deposit-action'])
                <li class="nav-item">
                    <a href="{{ route('admin.deposit.manual.pending') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.deposit.manual.pending') }}">
                        {{ __('Manual Pending Deposit') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.deposit.history') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.deposit.history') }}">
                        {{ __('Deposit History') }}
                    </a>
                </li>
            @endcanany
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
    @yield('deposit_content')
@endsection
