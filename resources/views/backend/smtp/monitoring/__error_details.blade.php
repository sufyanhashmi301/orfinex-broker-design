<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="detailsModal" tabindex="-1" aria-labelledby="categoryModal" aria-hidden="true">
    <div class="modal-dialog modal-lg relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-center justify-between p-5">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize">
                            {{ __('Failure Details') }}
                        </h3>
                        <p class="dark:text-white">
                            {{ __('View the details of the failure.') }}
                        </p>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-3">
                    <div class="mb-4">
                        <h6 class="text-base font-medium mb-[6px] text-slate-600 dark:text-slate-300">{{ __('Error Message') }}</h6>
                        <div id="errorMessage" class="bg-gray-100 dark:bg-gray-800 p-3 rounded text-sm"></div>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-base font-medium mb-[6px] text-slate-600 dark:text-slate-300">{{ __('Shortcodes') }}</h6>
                        <div id="shortcodes" class="bg-gray-100 dark:bg-gray-800 p-3 rounded text-xs"></div>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-base font-medium mb-[6px] text-slate-600 dark:text-slate-300">{{ __('Stack Trace') }}</h6>
                        <div id="stackTrace" class="bg-gray-100 dark:bg-gray-800 p-3 rounded text-xs overflow-auto max-h-48"></div>
                    </div>
                    <div>
                        <h6 class="text-base font-medium mb-[6px] text-slate-600 dark:text-slate-300">{{ __('Context') }}</h6>
                        <div id="context" class="bg-gray-100 dark:bg-gray-800 p-3 rounded text-xs"></div>
                    </div>
                    
                    {{-- Resend Info (if resent) --}}
                    <div id="resentInfo" class="hidden mt-4">
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <iconify-icon icon="heroicons:check-circle" class="text-2xl text-green-600 mt-1"></iconify-icon>
                                <div class="flex-1">
                                    <h5 class="font-semibold text-green-900 dark:text-green-100 mb-1">{{ __('Email Successfully Resent') }}</h5>
                                    <p class="text-sm text-green-800 dark:text-green-200" id="resentDetails"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>