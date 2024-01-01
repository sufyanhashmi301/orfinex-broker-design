@extends('frontend::send_money.index')
@section('send_money_content')
    <div class="progress-steps-form">
        <div class="transaction-status text-center px-7 py-12">
            <div class="icon h-20 w-20 bg-success-500 rounded-full flex flex-col items-center justify-center mx-auto">
                <iconify-icon icon="heroicons:check-16-solid" class="text-white text-4xl"></iconify-icon>
            </div>
            <h2 class="text-3xl my-5">{{ $notify['title']}}</h2>
            <p class="text-sm mb-3 dark:text-white">{{ $notify['p']}}</p>
            <p class="text-sm mb-3 dark:text-white">{{ $notify['strong'] }}</p>
            <a href="{{ $notify['action'] }}" class="btn inline-flex justify-center btn-light">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons:plus-small-20-solid"></iconify-icon>
                <span>{{ $notify['a'] }}</span>
            </a>
            <a href="https://www.trustpilot.com/review/orfinex.com" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                <span>Review us on Trustpilot</span>
            </a>
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
@endsection
