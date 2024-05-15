@extends('frontend::layouts.user')
@section('title')
    {{ __('Contracts') }}
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
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Contracts') }}
            </li>
        </ul>
    </div>

    <div class="max-w-xl text-center py-10 mx-auto space-y-5">
        <div class="w-20 h-20 bg-danger-500 text-white rounded-full inline-flex items-center justify-center">
            <iconify-icon icon="fa6-solid:box-open" class="text-5xl"></iconify-icon>
        </div>
        <h4 class="text-3xl text-slate-900 dark:text-white">
            {{ __("No active or pending contracts") }}
        </h4>
        <a href="{{ route('user.pricing.plans') }}" class="btn btn-dark inline-flex items-center justify-center">
            Start a new challenge
        </a>
    </div>

@endsection