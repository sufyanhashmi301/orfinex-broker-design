@extends('backend.setting.index')
@section('title')
    {{ __('Email Setting') }}
@endsection
@section('setting-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.settings.mail') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.mail') }}">
                    {{ __('SMTP') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.links.platform-links') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.links.platform-links') }}">
                    {{ __('Google Mail') }}
                </a>
            </li>
        </ul>
    </div>
    @yield('email-content')

    
    <!-- Modal for mail settings -->
    @include('backend.setting.email_setting.modal.__mail_settings')

    <!-- Modal for connection check -->
    @include('backend.setting.email_setting.modal.__connection_check')

@endsection