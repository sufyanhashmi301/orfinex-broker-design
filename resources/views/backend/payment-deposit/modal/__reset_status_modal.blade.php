<!-- Reset Status Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="resetStatusModal" tabindex="-1" aria-labelledby="resetStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-md w-full pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Reset Request Status') }}
                    </h3>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white"
                        data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-0">
                    <form id="resetStatusForm">
                        <input type="hidden" id="resetStatusRequestId" name="request_id">

                        <div class="mb-4">
                            <div
                                class="bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <iconify-icon icon="lucide:alert-triangle"
                                            class="w-5 h-5 text-warning-600 dark:text-warning-400"></iconify-icon>
                                    </div>
                                    <div class="ltr:ml-3 rtl:mr-3">
                                        <h4 class="text-sm font-medium text-warning-800 dark:text-warning-200 mb-1">
                                            {{ __('Confirm Status Reset') }}
                                        </h4>
                                        <p class="text-sm text-warning-700 dark:text-warning-300">
                                            {{ __('This will reset the request status from "Rejected" back to "Pending" for admin review. The rejection reason will be removed.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="text-slate-600 dark:text-slate-300 text-sm">
                                {{ __('Are you sure you want to reset this request status?') }}
                            </p>
                        </div>
                    </form>
                    <div class="text-right pt-3">
                        <button type="button" id="resetStatusSubmitBtn"
                            class="btn btn-warning inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:refresh-cw"
                                id="resetStatusIcon"></iconify-icon>
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 hidden spining-icon" icon="lucide:loader"
                                id="resetStatusLoader"></iconify-icon>
                            <span id="resetStatusText">{{ __('Reset Status') }}</span>
                        </button>
                        <button type="button" class="btn btn-secondary inline-flex items-center justify-center"
                            data-bs-dismiss="modal">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
