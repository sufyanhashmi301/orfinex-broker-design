@extends('backend.setting.misc.index')
@section('title')
    {{ __('All Support Tickets') }}
@endsection
@section('misc-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        @yield('header-btn')
    </div>
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            @can('ticket-status-list')
            <li class="nav-item">
                <a href="{{ route('admin.ticket.statuses.index') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.ticket.statuses.index') }}">
                    {{ __('Ticket Status') }}
                </a>
            </li>
            @endcan
            @can('ticket-priority-list')
            <li class="nav-item">
                <a href="{{ route('admin.ticket.priorities.index') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.ticket.priorities.index') }}">
                    {{ __('Ticket Priority') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
    @yield('ticket-content')
@endsection
