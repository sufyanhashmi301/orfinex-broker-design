@extends('backend.setting.payment.index')
@section('title')
    {{ __('Withdraw Methods') }}
@endsection
@section('payment-content')
    @yield('page-title')
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 gap-4 pb-1 sm:pb-0 menu-open">
            @can('automatic-withdraw-method')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.method.list','auto') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.method.list','auto') . isActive('admin.withdraw.method.create','auto'). isActive('admin.withdraw.method.edit','auto')  }}">
                        {{ __('Automatic') }}
                    </a>
                </li>
                @endcan
                @can('manual-withdraw-method')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.method.list','manual') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.method.list','manual') . isActive('admin.withdraw.method.create','manual') . isActive('admin.withdraw.method.edit','manual') }}">
                        {{ __('Manual') }}
                    </a>
                </li>
            @endcan
            @can('withdraw-schedule')
                <li class="">
                    <a href="{{ route('admin.withdraw.schedule') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.schedule') }}">
                        {{ __('Schedule') }}
                    </a>
                </li>
            @endcan
            @can('withdraw-notification')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.notificationTune') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.notificationTune') }}">
                        {{ __('Notification Tune') }}
                    </a>
                </li>
            @endcan
            @can('automatic-withdraw-method')
            <li class="nav-item">
                <a href="{{ route('admin.withdraw.miscSetting') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.withdraw.miscSetting') }}">
                    {{ __('Misc') }}
                </a>
            </li>
            @endcan
            <li class="nav-item !ml-auto">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
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
    @yield('withdraw-content')
@endsection
