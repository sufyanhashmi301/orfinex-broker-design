@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Now') }}
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
                {{ __('Deposit') }}
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('Deposit Now') }}
                    </h4>
                    <div>
                        <a href="{{ route('user.deposit.log') }}" class="btn btn-dark">
                            {{ __('Deposit History') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-6">
                    <div class="progress-steps md:flex justify-between items-center mb-7 hidden">
                        <div class="single-step {{ $isStepOne }}">
                            <div class="number">{{ __('01') }}</div>
                            <div class="content">
                                <h4 class="leading-none text-dark dark:text-white">{{ __('Deposit Amount') }}</h4>
                                <p class="text-dark dark:text-white">{{ __('Enter your deposit details') }}</p>
                            </div>
                        </div>
                        <div class="single-step {{ $isStepTwo }}">
                            <div class="number">{{ __('02') }}</div>
                            <div class="content">
                                <h4 class="leading-none text-dark dark:text-white">{{ __('Success') }}</h4>
                                <p class="text-dark dark:text-white">{{  $notify['card-header'] ??  __('Success Your Deposit') }}</p>
                            </div>
                        </div>
                    </div>
                    @yield('deposit_content')
                </div>
            </div>
        </div>
    </div>
@endsection
