@extends('backend.layouts.app')
@section('content')
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            @can('withdraw-list')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.pending') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.pending') }}">
                        {{ __('Pending Withdraws') }}
                    </a>
                </li>
            @endcan
            @can('withdraw-method-manage')

                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.method.list','auto') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.method.list','auto') . isActive('admin.withdraw.method.create','auto'). isActive('admin.withdraw.method.edit','auto')  }}">
                        {{ __('Automatic Method') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.method.list','manual') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.method.list','manual') . isActive('admin.withdraw.method.create','manual') . isActive('admin.withdraw.method.edit','manual') }}">
                        {{ __('Manual Method') }}
                    </a>
                </li>
            @endcan
            @can('withdraw-schedule')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.schedule') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.schedule') }}">
                        {{ __('Withdraw Schedule') }}
                    </a>
                </li>
            @endcan
            @can('withdraw-list')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.history') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.history') }}">
                        {{ __('Withdraw History') }}
                    </a>
                </li>
            @endcan
        </ul>
    </div>
    @yield('withdraw_content')
@endsection
