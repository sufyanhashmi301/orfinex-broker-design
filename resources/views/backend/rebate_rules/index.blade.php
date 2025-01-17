@extends('backend.layouts.app')
@section('title')
    {{ __('Rebate Symbol Group') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @yield('title-btns')
        </div>
    </div>

    @yield('symbol-groups-content')

    <!-- Modal for Add New Group -->

@endsection
@section('script')

@endsection
