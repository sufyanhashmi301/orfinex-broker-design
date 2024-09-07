@extends('backend.layouts.app')
@section('title')
    {{ __('All Customers') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add Customer') }}
            </a>
        </div>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">
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
            <li class="nav-item">
                <a href="{{ route('admin.user.with_balance') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.user.with_balance') }}">
                    {{ __('With Balance') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.user.without_balance') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.user.without_balance') }}">
                    {{ __('Without Balance') }}
                </a>
            </li>
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
    @yield('customers-content')
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.filter-toggle-btn').click(function() {
                const $content = $('#filters_div');

                if ($content.hasClass('hidden')) {
                    $content.removeClass('hidden').slideDown();
                } else {
                    $content.slideUp(function() {
                        $content.addClass('hidden');
                    });
                }
            });
        });
    </script>
    @yield('customers-script')
@endsection
