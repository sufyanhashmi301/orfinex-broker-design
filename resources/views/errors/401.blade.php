@extends('errors.layout')
@section('title')
    {{ __('Unauthorized') }}
@endsection
@section('content')
    <div class="">
        <a href="{{route('admin.dashboard')}}" class="items-center">
            <img src="{{ getFilteredPath(setting('site_logo','theme'), 'fallback/branding/mobile-admin-logo.png') }}" class="black_logo max-w-xs" alt="Logo"/>
            <img src="{{ getFilteredPath(setting('site_logo_light','theme'), 'fallback/branding/mobile-admin-logo.png') }}" class="white_logo max-w-xs" alt="Logo"/>
        </a>
    </div>
    <div class="w-full my-8">
        <div class="notfound">
            <h3>
                {{ __('Unauthorized') }}
            </h3>
            <h1>
                <span>4</span>
                <span>0</span>
                <span>1</span>
            </h1>
        </div>
    </div>
    @php
        $adminPrefix = trim(setting('site_admin_prefix', 'global'), '/');
        $prefix = request()->segment(1);
    @endphp
    <div class="max-w-[300px] mx-auto w-full">
        <a href="{{ $prefix === $adminPrefix ? route('admin.dashboard') : route('home') }}" class="btn btn-dark dark:bg-slate-800 block text-center">
            {{ __('Back to Home') }}
        </a>
    </div>
@endsection
