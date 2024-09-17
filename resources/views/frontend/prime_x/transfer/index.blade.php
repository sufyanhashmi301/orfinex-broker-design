@extends('frontend::layouts.user')
@section('title')
    {{ __('Transfer') }}
@endsection
@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="card price-table shadow-base p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-white">
            <header class="mb-6">
                <h4 class="text-xl text-slate-500 dark:text-slate-300 mb-3">
                    Internal Transfer
                </h4>
                <div class="space-x-4 relative flex items-center justify-between mb-5 rtl:space-x-reverse text-slate-900 dark:text-white">
                    <span class="text-[32px] leading-10 font-medium">
                        Unlimited
                    </span>
                    <span class="text-xs bg-primary font-medium px-2 py-1 rounded-full inline-block uppercase h-auto">
                        Instant
                    </span>
                </div>
                <p class="text-base text-success-500">
                    Free of Cost
                </p>
            </header>
            <div class="price-body space-y-8">
                <p class=" text-base leading-5 text-slate-500">
                    Instantly transfer funds between your accounts, seamlessly and without limits.
                </p>
                <div class="bg-slate-50 dark:bg-body rounded p-4">
                    <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span>For sending funds to</span>
                        <span class="text-slate-900 dark:text-slate-300">your accounts</span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('user.send-money.internal-view') }}" class="btn block-btn btn-primary loaderBtn inline-flex items-center">
                        Transfer Now
                    </a>
                </div>
            </div>
        </div>
        <div class="card price-table shadow-base p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-white">
            <header class="mb-6">
                <h4 class="text-xl text-slate-500 dark:text-slate-300 mb-3">
                    External Transfer
                </h4>
                <div class="space-x-4 relative flex items-center justify-between mb-5 rtl:space-x-reverse text-slate-900 dark:text-white">
                    <span class="text-[32px] leading-10 font-medium">
                        $500
                    </span>
                    <span class="text-xs bg-primary font-medium px-2 py-1 rounded-full inline-block uppercase h-auto">
                        Semi Instant
                    </span>
                </div>
                <p class="text-base text-success-500">
                    1% Fee Applied
                </p>
            </header>
            <div class="price-body space-y-8">
                <p class=" text-base leading-5 text-slate-500">
                    Safely send money to friends and family with easy-to-use External Transfer feature.
                </p>
                <div class="bg-slate-50 dark:bg-body rounded p-4">
                    <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span>For sending funds to</span>
                        <span class="text-slate-900 dark:text-slate-300">your accounts</span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('user.send-money.view') }}" class="btn block-btn btn-primary loaderBtn inline-flex items-center">
                        Transfer Now
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
