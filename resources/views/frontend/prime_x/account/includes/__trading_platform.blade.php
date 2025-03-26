<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="tradingPlatformsModal" tabindex="-1" aria-labelledby="tradingPlatformsModal" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
        rounded-md outline-none text-current">
            <div class="flex items-start justify-between gap-3 p-5">
                <div>
                    <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                        {{ __('Platform: ') }}{{ str_replace('_', ' ', setting('active_trader_type', 'features')) }}
                    </h3>
                    {{-- <p class="text-sm dark:text-white">
                        {{ __('Download out platform on any OS') }}
                    </p> --}}
                </div>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6">
                <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-3 mb-5">
                    <!-- BEGIN: Group Chart5 -->
                    @if(setting('desktop_terminal_windows_show','platform_links',false))
                        <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-300" icon="material-symbols:window-sharp"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for windows</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('desktop_terminal_windows_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('desktop_terminal_mac_show','platform_links',false))
                        <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-300" icon="fa6-brands:app-store-ios"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for MAC</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('desktop_terminal_mac_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_android_show','platform_links',false))
                        <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-300" icon="ion:logo-google-playstore"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for Android</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_android_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_Android_APK_show','platform_links',false))
                        <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-300" icon="material-symbols:android"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for Android APK</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_Android_APK_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(setting('mobile_application_iOS_show','platform_links',false))
                        <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                                <iconify-icon class="dark:text-slate-300" icon="fa6-brands:apple"></iconify-icon>
                            </div>
                            <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                                Metatrader 5 <br>
                                <span class="text-slate-400 text-sm font-normal">for IOS</span>
                            </span>
                            <div class="mt-5">
                                <a href="{{setting('mobile_application_iOS_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                    <span class="mr-1">Download</span>
                                    <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    @endif
                    <!-- END: Group Chart5 -->
                </div>

                @if(setting('web_terminal_show','platform_links',false))
                    <h4 class="text-lg font-medium dark:text-white mb-3">{{ __('Web') }}</h4>
                    <div class="card border border-slate-100 dark:border-slate-700 rounded p-4 mb-5">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl">
                                <iconify-icon class="dark:text-slate-300" icon="mdi:web"></iconify-icon>
                            </div>
                            <span class="text-base text-slate-600 font-medium dark:text-white">Web</span>
                            <a href="{{setting('web_terminal_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300 ml-auto" target="_blank">
                                <span class="mr-1">Open</span>
                                <iconify-icon icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif

                <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-slate-800 bg-opacity-[14%] text-slate-800 dark:bg-slate-500 dark:bg-opacity-[14%] dark:text-slate-300">
                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                        <iconify-icon class="text-xl flex-0" icon="lucide:info"></iconify-icon>
                        <div class="flex-1">
                            <h4 class="text-base mb-1">{{ __('Caution') }}</h4>
                            <p>{{ __('We recommend using the desktop platform as webtrader does not share history.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="action-btns text-right mt-10">
                    {{-- <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Save') }}
                    </button> --}}
                    <a
                        href="#"
                        class="btn btn-dark inline-flex items-center justify-center"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                        {{-- <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon> --}}
                        {{ __('Close') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
