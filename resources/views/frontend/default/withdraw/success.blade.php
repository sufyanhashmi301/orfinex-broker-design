@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Successful') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right"
                                  class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right"
                              class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Withdraw') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $notify['card-header'] }}</h3>
            <div class="card-header-links">
                <a href="{{ route('user.withdraw.view') }}" class="btn btn-dark">{{ __('Withdraw request') }}</a>
            </div>
        </div>
        <div class="card-body p-6">
            <div class="progress-steps-form">
                <div class="transaction-status text-center px-7 py-12">
                    <div
                        class="icon h-20 w-20 bg-success rounded-full flex flex-col items-center justify-center mx-auto">
                        <iconify-icon icon="heroicons:check-16-solid" class="text-white text-4xl"></iconify-icon>
                    </div>
                    <h2 class="text-3xl my-5">{{$notify['title']}}</h2>
                    <p class="text-sm mb-3 dark:text-white">{{$notify['p']}}</p>
                    <p class="text-sm mb-3 dark:text-white">{{ $notify['strong'] }}</p>
                    <a href="{{ $notify['action'] }}" class="btn inline-flex justify-center btn-light">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2"
                                      icon="heroicons:plus-small-20-solid"></iconify-icon>
                        <span>{{ $notify['a'] }}</span>
                    </a>
                    @if(setting('trust_pilot_review_show','platform_links',false))
                        <a href="{{setting('trust_pilot_review_link','platform_links','javascript:void(0);')}}"
                           target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2"
                                          icon="simple-icons:trustpilot"></iconify-icon>
                            <span>Review us on Trustpilot</span>
                        </a>
                    @endif
                    <div class="mt-5">
                        <p class="text-sm">
                            If you face any issue, please visit our
                            <a href="{{setting('customer_support_link','platform_links','javascript:void(0);')}}" target="_blank" class="btn-link">
                                Customer Support
                            </a>
                            or Email us at
                            <a href="mailto:{{ setting('support_email','global')}}" class="btn-link">
                                {{ setting('support_email','global')}}
                            </a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
