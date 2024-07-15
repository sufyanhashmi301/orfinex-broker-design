@extends('backend.layouts.app')
@section('title')
    {{ __('All Customers') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{route('admin.user.index')}}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.user.index') }}">
                    {{ __('All Customers') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.user.active') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.user.active') }}">
                    {{ __('Active Customers') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.user.disabled') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.user.disabled') }}">
                    {{ __('Disabled Customers') }}
                </a>
            </li>
        </ul>
    </div>
    @yield('customers-content')
@endsection
