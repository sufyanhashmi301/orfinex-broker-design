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
                Temporary Upgradation in Progres
            </h3>
            <p>

                This is a temporary upgradation, but don't worry – we’ll be back live very soon.

                Thank you for your patience and understanding.</p>
        </div>
    </div>
    <div class="max-w-[300px] mx-auto w-full">
        <a href="{{route('home')}}" class="btn btn-dark dark:bg-slate-800 block text-center">
            {{ __('Back to Home') }}
        </a>
    </div>
@endsection
