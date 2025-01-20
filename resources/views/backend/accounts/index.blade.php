@extends('backend.layouts.app')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">
            <li class="nav-item">
                <a href="{{ route('admin.accounts.index', ['status' => 'all']) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == 'all' ? 'active' : '' }} ">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts.index', ['status' => \App\Enums\InvestmentStatus::PENDING ]) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == \App\Enums\InvestmentStatus::PENDING ? 'active' : '' }} ">
                    Pending
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts.index', ['status' => \App\Enums\InvestmentStatus::ACTIVE ]) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == \App\Enums\InvestmentStatus::ACTIVE ? 'active' : '' }}">
                    Active
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts.index', ['status' => \App\Enums\InvestmentStatus::PASSED ]) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == \App\Enums\InvestmentStatus::PASSED ? 'active' : '' }}">
                    Passed
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts.index', ['status' => \App\Enums\InvestmentStatus::VIOLATED ]) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == \App\Enums\InvestmentStatus::VIOLATED ? 'active' : '' }}">
                    Violated
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts.index', ['status' => \App\Enums\InvestmentStatus::EXPIRED ]) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == \App\Enums\InvestmentStatus::EXPIRED ? 'active' : '' }}">
                    Expired
                </a>
            </li>

            <li class="nav-item !ml-auto">
                <a href="javascript:void(0);" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
                    <span class="flex items-center">
                        <span>{{ __('More') }}</span>
                        <iconify-icon icon="lucide:chevron-down" class="text-xl ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                    </span>
                </a>
            </li>
        </ul>

        <div class="hidden mt-5" id="filters_div">
            <form id="filter-form" method="GET" action="{{ route('admin.accounts.index') }}">
                <div class="flex justify-between flex-wrap items-center">
                    <div class="flex-1 inline-flex sm:space-x-3 space-x-2 ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0" style="max-width: 400px">
                        <div class="flex-1 input-area relative">
                            <input type="text" name="search" id="search" class="form-control h-full" placeholder="Search by Login, Name, or Email">
                        </div>
                        <div class="input-area relative">
                            <button type="submit" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                                {{ __('Filter') }}
                            </button>
                        </div>
                    </div>
                    <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        
                        
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('backend.accounts.includes.__accounts')

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
@endsection
