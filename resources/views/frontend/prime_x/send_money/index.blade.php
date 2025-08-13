@extends('frontend::layouts.user')
@section('title')
    {{ __('Send Money') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card hidden md:block mb-6">
                <div class="card-body p-3">
                    <div class="progress-steps md:flex justify-between items-center gap-5">
                        <div class="single-step {{ $isStepOne }}">
                            <div class="progress_bar mb-5"></div>
                            <div class="">
                                <div class="text-sm text-slate-600 dark:text-slate-100 mb-2">{{ __('Step - 1') }}</div>
                                <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Payment Details') }}</h4>
                            </div>
                        </div>
                        <div class="single-step {{ $isStepTwo }}">
                            <div class="progress_bar mb-5"></div>
                            <div class="">
                                <div class="text-sm text-slate-600 dark:text-slate-100 mb-2">{{ __('Step - 2') }}</div>
                                <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Success') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @yield('send_money_content')

        </div>
    </div>
@endsection
