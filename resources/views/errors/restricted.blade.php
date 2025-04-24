@extends('errors.layout')
@section('title')
    {{ __('Not Found') }}
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
                {{ __('Temporary Upgradation in Progress') }}
            </h3>
            <p>{{ __("This is a temporary upgradation, but don't worry – we’ll be back live very soon.") }}</p>
            <p>{{ __("Thank you for your patience and understanding.") }}</p>
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
