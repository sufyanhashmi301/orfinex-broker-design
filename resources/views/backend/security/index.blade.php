@extends('backend.layouts.app')
@section('title')
    {{ __('Security Setting') }}
@endsection
@section('content')
    <div class="card p-4 mb-5">
        <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.security.all-sections') }}" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('admin.security.all-sections') }}">
                    <iconify-icon class="mr-1" icon="lucide:layout-list"></iconify-icon>
                    {{ __('All Sections') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.security.blocklist-ip') }}" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('admin.security.blocklist-ip') }}">
                    <iconify-icon class="mr-1" icon="lucide:screen-share-off"></iconify-icon>
                    {{ __('Blocklist IP') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.security.single-session') }}" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('admin.security.single-session') }}">
                    <iconify-icon class="mr-1" icon="lucide:clock"></iconify-icon>
                    {{ __('Single Session') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.security.blocklist-email') }}" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('admin.security.blocklist-email') }}">
                    <iconify-icon class="mr-1" icon="lucide:mail-x"></iconify-icon>
                    {{ __('Blocklist Email') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.security.login-expiry') }}" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('admin.security.login-expiry') }}">
                    <iconify-icon class="mr-1" icon="lucide:alarm-clock-off"></iconify-icon>
                    {{ __('Login Expiry') }}
                </a>
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-5">
        @yield('security-content')
    </div>
@endsection
