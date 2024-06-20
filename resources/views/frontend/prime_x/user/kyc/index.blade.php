@extends('frontend::user.setting.index')
@section('title')
    {{ __('KYC') }}
@endsection
@section('settings-content')

    <div class="card">
        <div class="card-body p-6">
            <div class="mb-4">
                <h4 class="card-title mb-2">{{ __('Basic KYC') }}</h4>
                <p class="block font-normal text-sm text-slate-500">
                    {{ __("Ensure Trust and Security: Complete your KYC to access all features.") }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="price-table rounded-[6px] shadow-base dark:bg-slate-800 p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-white">
                    <header class="mb-6">
                        <h4 class="text-xl text-slate-500 dark:text-slate-300 mb-3">
                            KYC Settings
                        </h4>
                        <div class="space-x-4 relative flex items-center justify-between mb-5 rtl:space-x-reverse text-slate-900 dark:text-slate-300">
                            <h4 class="text-[32px] leading-10 font-medium">
                                Basic KYC
                            </h4>
                            <span class="text-xs bg-primary font-medium px-2 py-1 rounded-full inline-block dark:bg-slate-700 uppercase h-auto">
                                Semi Instant
                            </span>
                        </div>
                        <p class="text-base text-success-500">
                            3 to 6 Hours
                        </p>
                    </header>
                    <div class="price-body space-y-8">
                        <p class="leading-5 text-slate-500 dark:text-slate-300">
                            Unlock all standard operations with our Basic KYC - just submit your documents and you're set to go.
                        </p>
                        <div>
                            <a href="{{route('user.kyc.basic')}}" class="btn block-btn btn-dark">
                                Submit Now
                            </a>
                        </div>
                    </div>
                </div>
                <div class="price-table rounded-[6px] shadow-base dark:bg-slate-800 p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-white">
                    <header class="mb-6">
                        <h4 class="text-xl text-slate-500 dark:text-slate-300 mb-3">
                            KYC Settings
                        </h4>
                        <div class="space-x-4 relative flex items-center justify-between mb-5 rtl:space-x-reverse text-slate-900 dark:text-slate-300">
                            <h4 class="text-[32px] leading-10 font-medium">
                                Advance KYC
                            </h4>
                            <span class="text-xs bg-primary font-medium px-2 py-1 rounded-full inline-block dark:bg-slate-700 uppercase h-auto">
                                Instant
                            </span>
                        </div>
                        <p class="text-base text-success-500">
                            1 to 3 Minutes
                        </p>
                    </header>
                    <div class="price-body space-y-8">
                        <p class="leading-5 text-slate-500 dark:text-slate-300">
                            Elevate your access to major operations like External Transfers and Withdrawals with our Advanced KYC process.
                        </p>
                        <div>
                            <a href="javascript:;" class="btn block-btn btn-light" disabled>
                                Coming Soon
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

