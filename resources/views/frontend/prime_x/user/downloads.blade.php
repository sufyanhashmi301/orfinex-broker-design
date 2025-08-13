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
                                <iconify-icon class="dark:text-slate-100" icon="material-symbols:window-sharp"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                {{ __('Metatrader 5') }} <br>
                                <span class="text-slate-400 text-sm font-normal">{{ __('for windows') }}</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('desktop_terminal_windows_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-100" target="_blank">
                                    <span class="mr-1">{{ __('Download') }}</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('desktop_terminal_mac_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-100" icon="fa6-brands:app-store-ios"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                {{ __('Metatrader 5') }} <br>
                                <span class="text-slate-400 text-sm font-normal">{{ __('for MAC') }}</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('desktop_terminal_mac_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-100" target="_blank">
                                    <span class="mr-1">{{ __('Download') }}</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_android_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-100" icon="ion:logo-google-playstore"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                {{ __('Metatrader 5') }} <br>
                                <span class="text-slate-400 text-sm font-normal">{{ __('for Android') }}</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_android_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-100" target="_blank">
                                    <span class="mr-1">{{ __('Download') }}</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_Android_APK_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-100" icon="material-symbols:android"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                {{ __('Metatrader 5') }} <br>
                                <span class="text-slate-400 text-sm font-normal">{{ __('for Android APK') }}</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_Android_APK_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-100" target="_blank">
                                    <span class="mr-1">{{ __('Download') }}</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_iOS_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-100" icon="fa6-brands:apple"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                {{ __('Metatrader 5') }} <br>
                                <span class="text-slate-400 text-sm font-normal">{{ __('for IOS') }}</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_iOS_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-100" target="_blank">
                                    <span class="mr-1">{{ __('Download') }}</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('web_terminal_show','platform_links',false))
                        <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-100" icon="mdi:web"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                {{ __('Metatrader 5') }} <br>
                                <span class="text-slate-400 text-sm font-normal">{{ __('web trader') }}</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('web_terminal_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-100" target="_blank">
                                    <span class="mr-1">{{ __('Download') }}</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
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
