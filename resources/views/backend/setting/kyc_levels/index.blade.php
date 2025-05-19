@extends('backend.setting.user_management.index')
@section('title')
    {{ __('KYC & Compliance Setting') }}
@endsection
@section('user-management-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        @yield('title-btns')
    </div>
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open w-full">
             @can('kyc-levels-list')
            <li class="nav-item">
                <a href="{{ route('admin.kyclevels.index') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyclevels.index') }}">
                    {{ __('KYC & Compliance') }}
                </a>
            </li>
            @endcan
           
            <li class="nav-item">
                <a href="{{ route('admin.settings.kyclevels.misc') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.kyclevels.misc') }}">
                    {{ __('Permissions') }}
                </a>
            </li>
            
        </ul>
    </div>
    @yield('kyc-levels-content')

@endsection
