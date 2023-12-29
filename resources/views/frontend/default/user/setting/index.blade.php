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
                {{ __('Profile Settings') }}
            </li>
        </ul>
    </div>
    <div class=" space-y-5">
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6">
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-6">
                        <div class="flex space-x-3 items-center rtl:space-x-reverse">
                            <div class="flex-none h-8 w-8 rounded-full bg-slate-800 dark:bg-slate-700 text-slate-300 flex flex-col items-center
                            justify-center text-lg">
                                <iconify-icon icon="heroicons:building-office-2"></iconify-icon>
                            </div>
                            <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                Company Settings
                            </div>
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 text-sm">
                            Set up your company profile, add your company logo, and more
                        </div>
                        <a href="#" class="inline-flex items-center space-x-3 rtl:space-x-reverse text-sm capitalize font-medium text-slate-600
                        dark:text-slate-300">
                            <span>Chnage Settings</span>
                            <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-6">
                        <div class="flex space-x-3 items-center rtl:space-x-reverse">
                            <div
                                class="flex-none h-8 w-8 rounded-full bg-primary-500 text-slate-300 flex flex-col items-center justify-center text-lg">
                                <iconify-icon icon="heroicons:credit-card"></iconify-icon>
                            </div>
                            <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                Payment Settings
                            </div>
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 text-sm">
                            Connect your bank account to your company profile, and more
                        </div>
                        <a href="#" class="inline-flex items-center space-x-3 rtl:space-x-reverse text-sm capitalize font-medium text-slate-600
                        dark:text-slate-300">
                            <span>Chnage Settings</span>
                            <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-6">
                        <div class="flex space-x-3 rtl:space-x-reverse items-center">
                            <div
                                class="flex-none h-8 w-8 rounded-full bg-success-500 text-white flex flex-col items-center justify-center text-lg">
                                <iconify-icon icon="heroicons:user"></iconify-icon>
                            </div>
                            <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                Profile Settings
                            </div>
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 text-sm">
                            Set up your profile, add your profile photo, and more
                        </div>
                        <a href="#" class="inline-flex items-center space-x-3 rtl:space-x-reverse text-sm capitalize font-medium text-slate-600
                        dark:text-slate-300">
                            <span>Chnage Settings</span>
                            <iconify-icon icon="heroicons:arrow-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--profile settings--}}
    @include('frontend::user.setting.include.__profile')

    {{--Other settings--}}
    @include('frontend::user.setting.include.__other')

@endsection

