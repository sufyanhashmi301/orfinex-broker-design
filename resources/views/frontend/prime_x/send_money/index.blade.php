@extends('frontend::layouts.user')
@section('title')
    {{ __('Send Money') }}
@endsection
@section('content')
    <div class="flex justify-end flex-wrap items-center mb-5">
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('user.send-money.log') }}" class="btn btn-primary inline-flex items-center">
                {{ __('History') }}
            </a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <div class="progress-steps md:flex justify-between items-center mb-7 hidden">
                        <div class="single-step {{ $isStepOne }}">
                            <div class="progress_bar mb-5"></div>
                            <div class="">
                                <div class="text-sm text-slate-600 mb-2">{{ __('Step - 1') }}</div>
                                <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Payment Details') }}</h4>
                            </div>
                        </div>
                        <div class="single-step {{ $isStepTwo }}">
                            <div class="progress_bar mb-5"></div>
                            <div class="">
                                <div class="text-sm text-slate-600 mb-2">{{ __('Step - 2') }}</div>
                                <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Success') }}</h4>
                            </div>
                        </div>
                    </div>
                    @yield('send_money_content')
                </div>
            </div>
        </div>
    </div>
@endsection
