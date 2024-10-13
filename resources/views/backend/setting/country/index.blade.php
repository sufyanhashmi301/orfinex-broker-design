@extends('backend.setting.organization.index')
@section('title')
    {{ __('Country Setting') }}
@endsection
@section('organization-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        @yield('title-btn')
    </div>
    <div class="innerMenu card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.country.all') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.country.all') }}">
                    {{ __('All Countries') }}
                </a>
            </li>
            @can('black-list-countries')
                <li class="nav-item">
                    <a href="{{route('admin.blackListCountry.index')}}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{isActive('admin.blackListCountry.index')}}">
                        {{ __('Blacklist Countries') }}
                    </a>
                </li>
            @endcan
        </ul>
    </div>
    @yield('country-content')
@endsection
