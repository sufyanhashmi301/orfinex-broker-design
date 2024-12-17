@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Successful') }}
@endsection
@section('content')
    <div class="card hidden md:block mb-6">
        <div class="card-body p-3">
            <div class="progress-steps md:flex justify-between items-center gap-5">
                <div class="single-step">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 1') }}</div>
                        <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Withdraw Amount') }}</h4>
                    </div>
                </div>
                <div class="single-step current">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 2') }}</div>
                        <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Success') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Withdraw Request Notification') }}</h3>
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
                    <h2 class="text-3xl my-5">{{ __('Success Notification Title') }}</h2>
                    <p class="text-sm mb-3 dark:text-white">{{ __('Success Notification Description') }}</p>
                    <p class="text-sm mb-3 dark:text-white">{{ __('Strong Notification Message') }}</p>
                    <a href="{{ $notify['action'] }}" class="btn inline-flex justify-center btn-light">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons:plus-small-20-solid"></iconify-icon>
                        <span>{{ $notify['a'] }}</span>
                    </a>
                    @php
                        $trustpilot = plugin_active('Trustpilot');
                    @endphp
                    @if($trustpilot && $trustpilot->status)
                        @php
                            $trustpilotData = json_decode($trustpilot->data, true);
                        @endphp
                        <a href="{{ $trustpilotData['link'] }}" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                            <span>{{ __('Review us on Trustpilot') }}</span>
                        </a>
                    @endif
                    <div class="mt-5">
                        <p class="text-sm dark:text-slate-300">
                            {{ __('If you face any issue, please visit our') }}
                            <a href="{{setting('customer_support_link','platform_links','javascript:void(0);')}}" target="_blank" class="btn-link">
                                {{ __('Customer Support') }}
                            </a>
                            {{ __('or Email us at') }}
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
