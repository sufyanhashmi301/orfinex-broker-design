@extends('frontend::wallet.index')
@section('wallet_exchange_content')
    <div class="progress-steps-form">
        <div class="transaction-status text-center px-7 py-12">
            <div class="icon h-20 w-20 bg-success-500 rounded-full flex flex-col items-center justify-center mx-auto">
                <iconify-icon icon="heroicons:check-16-solid" class="text-white text-4xl"></iconify-icon>
            </div>
            <h2 class="text-3xl my-5">{{ $notify['title']}}</h2>
            <p class="text-sm mb-3 dark:text-white">{{ $notify['p']}}</p>
            <p class="text-sm mb-3 dark:text-white">{{ $notify['strong'] }}</p>
            <div class="text-center">
                <a href="{{ $notify['action'] }}" class="btn inline-flex justify-center btn-light">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons:plus-small-20-solid"></iconify-icon>
                    </span>
                    <span>{{ $notify['a'] }}</span>
                </a>
                <a href="{{ $notify['action'] }}" class="btn inline-flex justify-center btn-light">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons:plus-small-20-solid"></iconify-icon>
                    </span>
                    <span>{{ $notify['a'] }}</span>
                </a>
                @if(setting('trust_pilot_review_show','platform_links',false))
                    <a href="{{setting('trust_pilot_review_link','platform_links','javascript:void(0);')}}" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                    <span>Review us on Trustpilot</span>
                </a>
                @endif
                <div class="mt-5">
                    <p class="text-sm">
                        If you face any issue, please visit our
                        <a href="https://support.orfinex.com/" target="_blank" class="btn-link">
                            Customer Support
                        </a>
                        or Email us at
                        <a href="mailto:support@orfinex.com" class="btn-link">
                            support@orfinex.com
                        </a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
