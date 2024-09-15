@extends('frontend::deposit.index')
@section('deposit_content')
    <div class="card progress-steps-form">
        <div class="transaction-status text-center px-7 py-12">
            <div class="icon h-20 w-20 bg-success-500 rounded-full flex flex-col items-center justify-center mx-auto">
                <iconify-icon icon="heroicons:check-16-solid" class="text-white text-4xl"></iconify-icon>
            </div>
            <h2 class="text-3xl my-5 dark:text-white">{{$notify['title']}}</h2>
            <p class="text-sm mb-3 dark:text-white">{{$notify['p']}}</p>
            <p class="text-sm mb-3 dark:text-white">{{ $notify['strong'] }}</p>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-center items-center">
                <a href="{{ $notify['action'] }}" class="btn btn-dark inline-flex justify-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    <span>{{ $notify['a'] }}</span>
                </a>
                @if(setting('trust_pilot_review_show','platform_links',false))
                    <a href="{{setting('trust_pilot_review_link','platform_links','javascript:void(0);')}}" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                        <span>Review us on Trustpilot</span>
                    </a>
                @endif
            </div>
            <div class="mt-5">
                <p class="text-sm dark:text-slate-300">
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
@endsection

