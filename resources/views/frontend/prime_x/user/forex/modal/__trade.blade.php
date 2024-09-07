<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="tradeModal" tabindex="-1" aria-labelledby="tradeModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding
        rounded-md outline-none text-current">
            <div class="relative bg-white rounded-lg shadow dark:bg-dark">
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize">
                            {{ __('Trade') }}
                        </h3>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <ul class="account-details-list divide-y divide-slate-100 dark:divide-slate-700 h-full">
                        <li class="flex items-center py-3">
                            <span class="font-medium dark:text-white">{{ __('Live Account Name :') }}</span>
                            <span class="ml-auto dark:text-white">{{ __('Standard MT5 - Islamic') }}</span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="font-medium dark:text-white">{{ __('Account Type :') }}</span>
                            <span class="ml-auto dark:text-white">{{ __('MT5') }}</span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="font-medium dark:text-white">{{ __('Account Currency :') }}</span>
                            <span class="ml-auto dark:text-white">{{ __('USD') }}</span>
                        </li>
                    </ul>
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        @if(setting('desktop_terminal_windows_show','platform_links',false))
                            <a href="{{setting('desktop_terminal_windows_link','platform_links','javascript:void(0);')}}" target="_blank" class="w-full md:w-[48%] btn btn-sm inline-flex items-center justify-center btn-light">
                                <iconify-icon class="text-xl mr-2" icon="material-symbols:window-sharp"></iconify-icon>
                                <span>{{ __('Metatrader5 for windows') }}</span>
                            </a>
                        @endif
                        @if(setting('desktop_terminal_mac_show','platform_links',false))
                            <a href="{{setting('desktop_terminal_mac_link','platform_links','javascript:void(0);')}}" target="_blank" class="w-full md:w-[48%] btn btn-sm inline-flex items-center justify-center btn-light">
                                <iconify-icon class="text-xl mr-2" icon="fa6-brands:app-store-ios"></iconify-icon>
                                <span>{{ __('Metatrader5 for MAC') }}</span>
                            </a>
                        @endif
                        @if(setting('mobile_application_android_show','platform_links',false))
                            <a href="{{setting('mobile_application_android_link','platform_links','javascript:void(0);')}}" target="_blank" class="w-full md:w-[48%] btn btn-sm inline-flex items-center justify-center btn-light">
                                <iconify-icon class="text-xl mr-2" icon="ion:logo-google-playstore"></iconify-icon>
                                <span>{{ __('Metatrader5 for Android') }}</span>
                            </a>
                        @endif
                        @if(setting('mobile_application_Android_APK_show','platform_links',false))
                            <a href="{{setting('mobile_application_Android_APK_link','platform_links','javascript:void(0);')}}" target="_blank" class="w-full md:w-[48%] btn btn-sm inline-flex items-center justify-center btn-light">
                                <iconify-icon class="text-xl mr-2" icon="material-symbols:android"></iconify-icon>
                                <span>{{ __('Metatrader5 for Android APK') }}</span>
                            </a>
                        @endif
                        @if(setting('mobile_application_iOS_show','platform_links',false))
                            <a href="{{setting('mobile_application_iOS_link','platform_links','javascript:void(0);')}}" target="_blank" class="w-full md:w-[48%] btn btn-sm inline-flex items-center justify-center btn-light">
                                <iconify-icon class="text-xl mr-2" icon="fa6-brands:apple"></iconify-icon>
                                <span>{{ __('Metatrader5 for IOS') }}</span>
                            </a>
                        @endif
                        @if(setting('web_terminal_show','platform_links',false))
                            <a href="{{setting('web_terminal_link','platform_links','javascript:void(0);')}}" target="_blank" class="w-full md:w-[48%] btn btn-sm inline-flex items-center justify-center btn-light">
                                <iconify-icon class="text-xl mr-2" icon="mdi:web"></iconify-icon>
                                <span>{{ __('MT5 web trader') }}</span>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
