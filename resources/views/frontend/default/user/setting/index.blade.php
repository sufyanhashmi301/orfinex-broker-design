@extends('frontend::layouts.user')
@section('title')
    {{ __('Settings') }}
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
                {{ __('Settings') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Settings') }}
            </li>
        </ul>
    </div>
    <div class="space-y-5">
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-6">
                        <div class="flex space-x-3 items-center rtl:space-x-reverse">
                            <div class="flex-none h-8 w-8 rounded-full bg-slate-800 dark:bg-slate-700 text-slate-300 flex flex-col items-center
                            justify-center text-lg">
                                <iconify-icon icon="heroicons:user"></iconify-icon>
                            </div>
                            <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                Profile Setting
                            </div>
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 text-sm">
                            Shape Your Financial Identity: Your profile is your professional reflection.
                        </div>
                        <a href="{{ route('user.setting.profile') }}" class="inline-flex items-center space-x-3 rtl:space-x-reverse text-sm capitalize font-medium text-slate-600
                        dark:text-slate-300">
                            <span>Update or Change</span>
                            <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-6">
                        <div class="flex space-x-3 items-center rtl:space-x-reverse">
                            <div class="flex-none h-8 w-8 rounded-full bg-primary-500 text-slate-300 flex flex-col items-center justify-center text-lg">
                                <iconify-icon icon="heroicons:lock-closed"></iconify-icon>
                            </div>
                            <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                Security Setting
                            </div>
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 text-sm">
                            Strengthen Your Online Security: It's your primary defense.
                        </div>
                        <a href="{{ route('user.setting.security') }}" class="inline-flex items-center space-x-3 rtl:space-x-reverse text-sm capitalize font-medium text-slate-600
                        dark:text-slate-300">
                            <span>Update or Change</span>
                            <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            @if(setting('kyc_verification','permission'))
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-6">
                        <div class="flex space-x-3 rtl:space-x-reverse items-center">
                            <div class="flex-none h-8 w-8 rounded-full bg-success-500 text-white flex flex-col items-center justify-center text-lg">
                                <iconify-icon icon="heroicons:clipboard-document-list"></iconify-icon>
                            </div>
                            <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                KYC Setting
                            </div>
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 text-sm">
                            Ensure Trust and Security: Complete your KYC to access all features.
                        </div>
                        <a href="{{ route('user.kyc') }}" class="inline-flex items-center space-x-3 rtl:space-x-reverse text-sm capitalize font-medium text-slate-600
                        dark:text-slate-300">
                            <span>Update or Change</span>
                            <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-6">
                        <div class="flex space-x-3 rtl:space-x-reverse items-center">
                            <div class="flex-none h-8 w-8 rounded-full bg-slate-800 dark:bg-slate-700 text-slate-300 flex flex-col items-center
                            justify-center text-lg">
                                <iconify-icon icon="fa6-regular:handshake"></iconify-icon>
                            </div>
                            <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                Legal Agreements
                            </div>
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 text-sm">
                            Stay informed and compliant; review all legal agreements linked to your profile.
                        </div>
                        <a href="{{ route('user.agreements') }}" class="inline-flex items-center space-x-3 rtl:space-x-reverse text-sm capitalize font-medium text-slate-600
                        dark:text-slate-300">
                            <span>View Documents</span>
                            <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            @if(url('/') != 'http://brokerdemo.brokeret.com')
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-6">
                        <div class="flex space-x-3 rtl:space-x-reverse items-center">
                            <div class="flex-none h-8 w-8 rounded-full bg-success-500 text-white flex flex-col items-center justify-center text-lg">
                                <iconify-icon icon="fa6-solid:sack-dollar"></iconify-icon>
                            </div>
                            <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                Margin 4x360
                            </div>
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 text-sm">
                            Application of Account Opening of Margin 4x360
                        </div>
                        <a href="{{ route('user.margin-account') }}" class="inline-flex items-center space-x-3 rtl:space-x-reverse text-sm capitalize font-medium text-slate-600
                        dark:text-slate-300">
                            <span>Apply Now</span>
                            <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
                @endif
        </div>
    </div>

@endsection

