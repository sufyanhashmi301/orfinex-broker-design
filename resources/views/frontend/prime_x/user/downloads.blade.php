@extends('frontend::layouts.user')
@section('title')
    {{ __('Downloads') }}
@endsection
@section('content')
    <div class="card p-6">
        <div class="grid grid-cols-12 gap-5">
            <div class="xl:col-span-12 col-span-12">
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-3">
                    <!-- BEGIN: Group Chart5 -->
                    @if(setting('desktop_terminal_windows_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon icon="material-symbols:window-sharp"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for windows</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('desktop_terminal_windows_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.04883 6.32666L6.43549 7.71333L7.82216 6.32666" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.43555 2.16666V7.67541" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.8327 6.5975C10.8327 8.99167 9.20768 10.9308 6.49935 10.9308C3.79102 10.9308 2.16602 8.99167 2.16602 6.5975" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('desktop_terminal_mac_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon icon="fa6-brands:app-store-ios"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for MAC</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('desktop_terminal_mac_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.04883 6.32666L6.43549 7.71333L7.82216 6.32666" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.43555 2.16666V7.67541" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.8327 6.5975C10.8327 8.99167 9.20768 10.9308 6.49935 10.9308C3.79102 10.9308 2.16602 8.99167 2.16602 6.5975" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_android_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon icon="ion:logo-google-playstore"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for Android</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_android_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.04883 6.32666L6.43549 7.71333L7.82216 6.32666" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.43555 2.16666V7.67541" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.8327 6.5975C10.8327 8.99167 9.20768 10.9308 6.49935 10.9308C3.79102 10.9308 2.16602 8.99167 2.16602 6.5975" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_Android_APK_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon icon="material-symbols:android"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for Android APK</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_Android_APK_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.04883 6.32666L6.43549 7.71333L7.82216 6.32666" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.43555 2.16666V7.67541" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.8327 6.5975C10.8327 8.99167 9.20768 10.9308 6.49935 10.9308C3.79102 10.9308 2.16602 8.99167 2.16602 6.5975" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_iOS_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon icon="fa6-brands:apple"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for IOS</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_iOS_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.04883 6.32666L6.43549 7.71333L7.82216 6.32666" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.43555 2.16666V7.67541" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.8327 6.5975C10.8327 8.99167 9.20768 10.9308 6.49935 10.9308C3.79102 10.9308 2.16602 8.99167 2.16602 6.5975" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('web_terminal_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon icon="mdi:web"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">web trader</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('web_terminal_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.04883 6.32666L6.43549 7.71333L7.82216 6.32666" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.43555 2.16666V7.67541" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.8327 6.5975C10.8327 8.99167 9.20768 10.9308 6.49935 10.9308C3.79102 10.9308 2.16602 8.99167 2.16602 6.5975" stroke="#3F3F3D" stroke-opacity="0.7" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                    <!-- END: Group Chart5 -->
                </div>
            </div>
    
        </div>
    </div>
@endsection