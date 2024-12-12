@extends('backend.setting.platform.index')
@section('title')
    {{ __('Platform API Settings') }}
@endsection
@section('platform-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <div>
            <h4 class="font-medium text-xl capitalize dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-1">
                @yield('title')
            </h4>
            <p class="text-sm text-slate-500 dark:text-slate-300">
                @yield('title-desc')
            </p>
        </div>
    </div>

    @if(!Route::is(['admin.platform_api.db-synchronization', 'admin.platform_api.dbX9trader']))
        @include('backend.setting.platform_api.include.__tabs_nav')
    @endif

    @yield('tabs-nav')

    @yield('platform-api-content')
@endsection
