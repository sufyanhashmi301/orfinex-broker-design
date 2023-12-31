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
            <a href="{{ $notify['action'] }}" class="btn inline-flex justify-center btn-light">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons:plus-small-20-solid"></iconify-icon>
                <span>{{ $notify['a'] }}</span>
            </a>
        </div>
    </div>
@endsection
