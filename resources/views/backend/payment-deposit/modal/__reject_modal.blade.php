<!-- Reject Payment Deposit Request Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="rejectModal"
     tabindex="-1"
     aria-labelledby="rejectModalLabel"
     aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-lg w-full pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Reject Payment Deposit Request') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-0">
                    <form id="rejectForm">
                        @csrf
                        <input type="hidden" name="request_id" id="rejectRequestId">
                        
                        <div class="input-area">
                            <label class="form-label" for="rejection_reason">
                                {{ __('Rejection Reason') }} <span class="text-danger-500">*</span>
                            </label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" required placeholder="Please provide a reason for rejecting this request..."></textarea>
                        </div>
                    </form>
                    <div class="text-right pt-10">
                        <button type="submit" form="rejectForm" class="btn btn-primary inline-flex items-center justify-center mr-2" id="rejectSubmitBtn">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x" id="rejectIcon"></iconify-icon>
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 hidden spining-icon" icon="lucide:loader" id="rejectLoader"></iconify-icon>
                            <span id="rejectText">{{ __('Reject Request') }}</span>
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
