@extends('frontend::layouts.user')
@section('title')
    {{ __('Send Money') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6 hidden md:block mb-6">
                <div class="progress-steps md:flex justify-between items-center gap-5">
                    <div class="single-step w-full {{ $isStepOne }}">
                        <div class="w-full h-2 rounded-full bg-gray-200 dark:bg-gray-800 progress_bar mb-5"></div>
                        <div class="">
                            <div class="text-theme-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Step - 1') }}</div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ __('Payment Details') }}</h4>
                        </div>
                    </div>
                    <div class="single-step w-full {{ $isStepTwo }}">
                        <div class="w-full h-2 rounded-full bg-gray-200 dark:bg-gray-800 progress_bar mb-5"></div>
                        <div class="">
                            <div class="text-theme-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Step - 2') }}</div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ __('Success') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            @yield('send_money_content')

        </div>
    </div>
@endsection
