<!-- Reject Approved Request Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="rejectApprovedModal" tabindex="-1" aria-labelledby="rejectApprovedModalLabel" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-lg w-full pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Reject Approved Request') }}
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
                    <div
                        class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <iconify-icon icon="lucide:alert-triangle"
                                    class="text-amber-500 text-xl"></iconify-icon>
                            </div>
                            <div class="ml-3">
                                <h5 class="text-sm font-medium text-amber-800 dark:text-amber-200">{{ __('Warning') }}
                                </h5>
                                <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                                    {{ __('This action will reject an already approved request. The user will be notified about this change.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <form id="rejectApprovedForm">
                        @csrf
                        <input type="hidden" name="request_id" id="rejectApprovedRequestId">

                        <div class="input-area">
                            <label class="form-label" for="rejectApprovedReason">
                                {{ __('Reason for Rejection') }} <span class="text-danger-500">*</span>
                            </label>
                            <textarea name="rejection_reason" id="rejectApprovedReason" class="form-control" rows="4" required
                                placeholder="Please provide a clear reason for rejecting this approved request..."></textarea>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ __('This reason will be visible to the user.') }}
                            </div>
                        </div>

                        <div
                            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4 mt-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <iconify-icon icon="lucide:info" class="text-red-500 text-lg"></iconify-icon>
                                </div>
                                <div class="ml-3">
                                    <h5 class="text-sm font-medium text-red-800 dark:text-red-200">
                                        {{ __('Important Note') }}</h5>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">
                                        {{ __('Once rejected, the user will need to submit a new payment deposit request. The bank details associated with this request will be removed.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="text-right pt-10">
                        <button type="submit" form="rejectApprovedForm"
                            class="btn btn-primary inline-flex items-center justify-center mr-2"
                            id="rejectApprovedSubmitBtn">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"
                                id="rejectApprovedIcon"></iconify-icon>
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 hidden spining-icon" icon="lucide:loader"
                                id="rejectApprovedLoader"></iconify-icon>
                            <span id="rejectApprovedText">{{ __('Reject Request') }}</span>
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center"
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
