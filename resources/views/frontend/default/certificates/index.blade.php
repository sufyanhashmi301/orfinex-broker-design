@extends('frontend::layouts.user')
@section('title')
    {{ __('Certificates') }}
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
                {{ __('Certificates') }}
            </li>
        </ul>
    </div>

    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300  active">
                    All Certificates
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300 ">
                    Evaluation Process
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300 ">
                    Payouts
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300 ">
                    Max Allocation
                </a>
            </li>
        </ul>
    </div>

    <div class="max-w-xl text-center py-10 mx-auto space-y-5">
        <div class="w-20 h-20 bg-danger-500 text-white rounded-full inline-flex items-center justify-center">
            <iconify-icon icon="fa6-solid:box-open" class="text-5xl"></iconify-icon>
        </div>
        <h4 class="text-3xl text-slate-900 dark:text-white">
            {{ __("There's no certificates available yet.") }}
        </h4>
        <p class="text-slate-600 dark:text-slate-100">
            {{ __('But no worries, we believe that will change soon. Are you ready to pass our Challenge ?') }}
        </p>
        <a href="{{ route('user.pricing.plans') }}" class="btn btn-dark inline-flex items-center justify-center">
            Start a new challenge
        </a>
    </div>

@endsection