@extends('frontend::deposit.index')
@section('deposit_content')
    <div class="card progress-steps-form">
        <div class="transaction-status flex flex-col items-center px-7 py-12">
            <div class="relative mb-10">
                <img src="{{ asset('frontend/images/success-page__img.svg') }}" alt="">
                <div class="absolute left-0 right-0 bottom-0 icon h-16 w-16 bg-primary rounded-full flex flex-col items-center justify-center mx-auto">
                    <iconify-icon icon="heroicons:check-16-solid" class="text-white text-4xl"></iconify-icon>
                </div>
            </div>
            <h5 class="text-lg text-gray-500 dark:text-gray-300 mb-2">
                {{ __('Payment Successful!') }}
            </h5>
            <h2 class="text-3xl dark:text-white mb-5">
                {{ __('Your transaction was processed successfully!') }}
            </h2>
            <p class="max-w-xl text-center text-slate-500 text-sm mb-3 dark:text-gray-300">
                <span class="block mb-1">
                    {{ __('Thank you for your trust in '. setting('site_title','global') .'.') }}
                </span>
                {{ __('Feel free to explore more of our services or check your account for the updated balance.') }}
            </p>
            <blockquote class="border-0 text-slate-700 dark:text-slate-300 text-base my-5">
                {{ __('Success is not final; failure is not fatal: It is the courage to continue that counts.') }}
                <span class="text-sm text-slate-400 text-right block mt-3">
                    {{ __('- Winston Churchill') }}
                </span>
            </blockquote>
            <div class="flex items-center gap-3">
                <a href="" class="btn btn-primary inline-flex justify-center">
                    {{ __('Go to Dashboard') }}
                </a>
                @if(setting('trust_pilot_review_show','platform_links',false))
                    <a href="{{setting('trust_pilot_review_link','platform_links','javascript:void(0);')}}" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                        <span>{{ __('Review us on Trustpilot') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
