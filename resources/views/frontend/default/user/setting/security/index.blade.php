@extends('frontend::layouts.user')
@section('title')
    {{ __('Security Settings') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Settings') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Security Settings') }}
            </li>
        </ul>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- 2 Factor Authentication --}}
        @include('frontend::user.setting.include.__two_fa')

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Change Password') }}</h4>
            </div>
            <div class="card-body p-6">
                <a href="{{ route('user.change.password') }}" class="btn btn-dark">
                    {{ __('Change Password') }}
                </a>
            </div>
        </div>
    </div>

@endsection