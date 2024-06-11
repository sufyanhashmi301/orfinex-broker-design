@extends('frontend::layouts.user')
@section('title')
    {{ __('KYC') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('KYC') }}
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="price-table rounded-[6px] shadow-base dark:bg-slate-800 p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-white">
            <div class="overlay absolute right-0 top-0 w-full h-full z-[-1]">
                <img src="" alt="" class="ml-auto block">
            </div>
        
            <header class="mb-6">
                <h4 class="text-xl mb-5  text-slate-900 dark:text-slate-300  ">
                    Basic KYC
                </h4>
                <div class="space-x-4 relative flex items-center mb-5 rtl:space-x-reverse text-slate-900 dark:text-slate-300  ">
                    <span class="text-xs bg-warning-100 text-warning-500 font-medium px-2 py-1 rounded-full inline-block dark:bg-slate-700 uppercase h-auto">
                        Semi Instant
                    </span>
                </div>
                <p class="text-sm" :class=" item.bg === 'bg-slate-900' ? 'text-slate-100' : 'text-slate-500 dark:text-slate-300' ">
                    3 to 6 Hours
                </p>
            </header>
            <div class="price-body space-y-8">
                <p class=" text-sm leading-5 dark:text-slate-300 ">
                    Unlock all standard operations with our Basic KYC - just submit your documents and you're set to go.
                </p>
                <div>
                    <a href="{{route('user.kyc.basic')}}" class="btn block-btn btn-outline-dark dark:border-slate-400 ">
                        Submit Now
                    </a>
                </div>
            </div>
        </div>
        <div class="price-table rounded-[6px] shadow-base dark:bg-slate-800 p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-slate-900">
            <div class="overlay absolute right-0 top-0 w-full h-full z-[-1]">
                <img src="" alt="" class="ml-auto block">
            </div>
        
            <div class="text-sm font-medium bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-300 py-2 text-center absolute ltr:-right-[43px] rtl:-left-[43px] top-6 px-10 transform ltr:rotate-[45deg] rtl:-rotate-45">
                Coming Soon
            </div>
        
            <header class="mb-6">
                <h4 class="text-xl mb-5  text-slate-100  ">
                    Advance KYC
                </h4>
                <div class="space-x-4 relative flex items-center mb-5 rtl:space-x-reverse  text-slate-100  ">
                    <span class="text-xs bg-warning-50 text-warning-500 font-medium px-2 py-1 rounded-full inline-block dark:bg-slate-700 uppercase h-auto">
                        Instant     
                    </span>
                </div>
                <p class="text-sm text-white">
                    1 to 3 Minutes
                </p>
            </header>
            <div class="price-body space-y-8">
                <p class=" text-sm leading-5  text-slate-100 ">
                    Elevate your access to major operations like External Transfers and Withdrawals with our Advanced KYC process.
                </p>
                <div>
                    <a href="javascript:;" class="btn block-btn text-slate-100 border-slate-300 border ">
                        Coming Soon
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

