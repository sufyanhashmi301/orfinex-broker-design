@extends('backend.layouts.app')
@section('title')
    {{ __('All Accounts') }}
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
                <a href="{{route('admin.accounts.challengeAccounts')}}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.accounts.challengeAccounts') }}">
                    {{ __('Challenge Accounts') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts.fundedAccounts') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.accounts.fundedAccounts') }}">
                    {{ __('Funded Accounts') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts.directFundedAccounts') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.accounts.directFundedAccounts') }}">
                    {{ __('Direct Funded Accounts') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts.trialAccounts') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.accounts.trialAccounts') }}">
                    {{ __('Trial Accounts') }}
                </a>
            </li>
        </ul>
    </div>
    @yield('accounts-content')
@endsection
