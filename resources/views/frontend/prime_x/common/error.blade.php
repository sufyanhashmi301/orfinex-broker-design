@extends('frontend::layouts.user')
@section('content')
    <div class="card basicTable_wrapper px-7 py-12">
        <div class="flex flex-col items-center">
            <div class="relative mb-10">
                <img src="{{ asset('frontend/images/error-page__img.svg') }}" alt="">
            </div>
            <h2 class="text-3xl dark:text-white mb-5">
                {{ __('Oops, Something Went Wrong!') }}
            </h2>
            <p class="max-w-xl text-center text-slate-500 text-sm mb-3 dark:text-gray-300">
                {{ __('It seems like your transaction couldn’t be completed. Don’t worry—there might be a simple issue. Please try again, or contact our support team for assistance. We’re here to help if you need us! Feel free to try again or contact support.') }}
            </p>
            <blockquote class="border-0 text-slate-700 dark:text-slate-300 text-base my-5">
                {{ __('Our greatest glory is not in never falling, but in rising every time we fall.') }}
                <span class="text-sm text-slate-400 text-right block mt-3">
                    {{ __('– Confucius') }}
                </span>
            </blockquote>
            <div class="flex items-center gap-3">
                <a href="" class="btn btn-primary inline-flex justify-center">
                    {{ __('Retry Payment') }}
                </a>
                <a href="" class="btn btn-primary inline-flex justify-center">
                    {{ __('Contact Support') }}
                </a>
            </div>
        </div>
    </div>
@endsection
