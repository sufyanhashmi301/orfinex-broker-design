@extends('frontend::layouts.user')
@section('content')
    <div class="card basicTable_wrapper px-7 py-12">
        <div class="flex flex-col items-center">
            <div class="relative mb-10">
                <img src="{{ asset('frontend/images/canceled-page__img.svg') }}" alt="{{ __('Canceled Page Image') }}">
                <div class="absolute left-0 right-0 bottom-0 icon h-16 w-16 bg-danger rounded-full flex flex-col items-center justify-center mx-auto">
                    <iconify-icon icon="mdi:close" class="text-white text-4xl" aria-label="{{ __('Close Icon') }}"></iconify-icon>
                </div>
            </div>
            <h5 class="text-lg text-gray-500 dark:text-gray-300 mb-2">
                {{ __('Payment Canceled') }}
            </h5>
            <h2 class="text-3xl dark:text-white mb-5">
                {{ __('Your transaction has been canceled') }}
            </h2>
            <p class="max-w-xl text-center text-slate-500 text-sm mb-3 dark:text-gray-300">
                {{ __('If this was a mistake or you wish to proceed with your payment, you can always try again. Let us know if you need any assistance, or feel free to retry the payment at your convenience.') }}
            </p>
            <blockquote class="border-0 text-slate-700 dark:text-slate-100 text-base my-5">
                {{ __('The only limit to our realization of tomorrow is our doubts of today.') }}
                <span class="text-sm text-slate-400 text-right block mt-3">
                    {{ __('– Franklin D. Roosevelt') }}
                </span>
            </blockquote>
            <div class="flex items-center gap-3">
                <a href="" class="btn btn-primary inline-flex justify-center">
                    {{ __('Retry Payment') }}
                </a>
                <a href="" class="btn btn-primary inline-flex justify-center">
                    {{ __('Go to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
@endsection
