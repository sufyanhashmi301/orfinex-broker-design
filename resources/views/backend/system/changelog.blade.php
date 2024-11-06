@extends('backend.setting.system.index')
@section('title')
    {{ __('Changelog') }}
@endsection
@section('system-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <iframe src="https://brokeret.com/headless/changelog" class="w-full h-screen" frameborder="0"></iframe>
@endsection
