@extends('frontend::layouts.user')
@section('title')
    {{ __('Downloads') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Downloads') }}
            </li>
        </ul>
    </div>
    <div class="card p-6">
        <div class="grid grid-cols-12 gap-5">
            <div class="xl:col-span-12 col-span-12">
                <div class="grid lg:grid-cols-6 sm:grid-cols-2 grid-cols-1 gap-3">
                    <!-- BEGIN: Group Chart5 -->
                    @if(setting('desktop_terminal_windows_show','platform_links',false))
                        <a href="{{setting('desktop_terminal_windows_link','platform_links','javascript:void(0);')}}" target="_blank" class="bg-slate-50 dark:bg-slate-900 rounded p-4 text-center transition-all ring-offset-4 dark:ring-offset-slate-800 ring-slate-100 dark:ring-black-900 hover:ring-2">
                            <div class="mx-auto h-12 w-12 flex flex-col items-center justify-center rounded-full bg-white text-3xl mb-4">
                                <iconify-icon icon="material-symbols:window-sharp"></iconify-icon>
                            </div>
                            <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader5 for windows
                            </span>
                        </a>
                    @endif
                    @if(setting('desktop_terminal_mac_show','platform_links',false))
                        <a href="{{setting('desktop_terminal_mac_link','platform_links','javascript:void(0);')}}" target="_blank" class="bg-slate-50 dark:bg-slate-900 rounded p-4 text-center transition-all ring-offset-4 dark:ring-offset-slate-800 ring-slate-100 dark:ring-black-900 hover:ring-2">
                            <div class="mx-auto h-12 w-12 flex flex-col items-center justify-center rounded-full bg-white text-3xl mb-4">
                                <iconify-icon icon="fa6-brands:app-store-ios"></iconify-icon>
                            </div>
                            <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader5 for MAC
                            </span>
                        </a>
                    @endif
                    @if(setting('mobile_application_android_show','platform_links',false))
                        <a href="{{setting('mobile_application_android_link','platform_links','javascript:void(0);')}}" target="_blank" class="bg-slate-50 dark:bg-slate-900 rounded p-4 text-center transition-all ring-offset-4 dark:ring-offset-slate-800 ring-slate-100 dark:ring-black-900 hover:ring-2">
                            <div class="mx-auto h-12 w-12 flex flex-col items-center justify-center rounded-full bg-white text-3xl mb-4">
                                <iconify-icon icon="ion:logo-google-playstore"></iconify-icon>
                            </div>
                            <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader5 for Android
                            </span>
                        </a>
                    @endif
                    @if(setting('mobile_application_Android_APK_show','platform_links',false))
                        <a href="{{setting('mobile_application_Android_APK_link','platform_links','javascript:void(0);')}}" target="_blank" class="bg-slate-50 dark:bg-slate-900 rounded p-4 text-center transition-all ring-offset-4 dark:ring-offset-slate-800 ring-slate-100 dark:ring-black-900 hover:ring-2">
                            <div class="mx-auto h-12 w-12 flex flex-col items-center justify-center rounded-full bg-white text-3xl mb-4">
                                <iconify-icon icon="material-symbols:android"></iconify-icon>
                            </div>
                            <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader5 for Android APK
                            </span>
                        </a>
                    @endif
                    @if(setting('mobile_application_iOS_show','platform_links',false))
                        <a href="{{setting('mobile_application_iOS_link','platform_links','javascript:void(0);')}}" target="_blank" class="bg-slate-50 dark:bg-slate-900 rounded p-4 text-center transition-all ring-offset-4 dark:ring-offset-slate-800 ring-slate-100 dark:ring-black-900 hover:ring-2">
                            <div class="mx-auto h-12 w-12 flex flex-col items-center justify-center rounded-full bg-white text-3xl mb-4">
                                <iconify-icon icon="fa6-brands:apple"></iconify-icon>
                            </div>
                            <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader5 for IOS
                            </span>
                        </a>
                    @endif
                    @if(setting('web_terminal_show','platform_links',false))
                        <a href="{{setting('web_terminal_link','platform_links','javascript:void(0);')}}" target="_blank" class="bg-slate-50 dark:bg-slate-900 rounded p-4 text-center transition-all ring-offset-4 ring-offset-transparent ring-slate-100 dark:ring-black-900 hover:ring-2">
                            <div class="mx-auto h-12 w-12 flex flex-col items-center justify-center rounded-full bg-white text-3xl mb-4">
                                <iconify-icon icon="mdi:web"></iconify-icon>
                            </div>
                            <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                MT5 web trader
                            </span>
                        </a>
                    @endif
                    <!-- END: Group Chart5 -->
                </div>
            </div>

        </div>
    </div>
@endsection
