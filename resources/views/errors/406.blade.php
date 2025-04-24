@extends('errors.layout')
@section('title')
    {{ __('Not acceptable') }}
@endsection
@section('content')
    <div class="">
        <a href="{{route('admin.dashboard')}}" class="items-center">
            <img src="{{ asset(setting('site_logo','global')) }}" class="black_logo max-w-xs" alt="Logo"/>
            <img src="{{ asset(setting('site_logo_light','global')) }}" class="white_logo max-w-xs" alt="Logo"/>
        </a>
    </div>
    <div class="w-full my-8">
        <div class="notfound">
            <h3>
                {{ __($exception->getMessage()) ?: 'Not Acceptable' }}
            </h3>
            <h1>
                <span>4</span>
                <span>0</span>
                <span>6</span>
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
