<!-- Approve Payment Deposit Request Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="approveModal"
     tabindex="-1"
     aria-labelledby="approveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-2xl w-full pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Approve Payment Deposit Request') }}
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
                <form id="approveForm">
                    @csrf
                    <input type="hidden" name="request_id" id="approveRequestId">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="input-area">
                            <label class="form-label" for="bank_name">
                                {{ __('Bank Name') }} <span class="text-danger-500">*</span>
                            </label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" required>
                        </div>
                        
                        <div class="input-area">
                            <label class="form-label" for="account_name">
                                {{ __('Account Name') }} <span class="text-danger-500">*</span>
                            </label>
                            <input type="text" name="account_name" id="account_name" class="form-control" required>
                        </div>
                        
                        <div class="input-area">
                            <label class="form-label" for="account_number">
                                {{ __('Account Number') }} <span class="text-danger-500">*</span>
                            </label>
                            <input type="text" name="account_number" id="account_number" class="form-control" required>
                        </div>
                        
                        <div class="input-area">
                            <label class="form-label" for="routing_number">
                                {{ __('Routing Number') }}
                            </label>
                            <input type="text" name="routing_number" id="routing_number" class="form-control">
                        </div>
                        
                        <div class="input-area">
                            <label class="form-label" for="swift_code">
                                {{ __('SWIFT Code') }}
                            </label>
                            <input type="text" name="swift_code" id="swift_code" class="form-control">
                        </div>
                        
                        <div class="input-area md:col-span-2">
                            <label class="form-label" for="bank_address">
                                {{ __('Bank Address') }}
                            </label>
                            <textarea name="bank_address" id="bank_address" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="input-area md:col-span-2">
                            <label class="form-label" for="additional_instructions">
                                {{ __('Additional Instructions') }}
                            </label>
                            <textarea name="additional_instructions" id="additional_instructions" class="form-control" rows="3" placeholder="Any additional instructions for the user..."></textarea>
                        </div>
                    </div>
                </form>
                <div class="text-right pt-3">
                    <button type="submit" form="approveForm" class="btn btn-dark inline-flex items-center justify-center mr-2" id="approveSubmitBtn">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check" id="approveIcon"></iconify-icon>
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 hidden spining-icon" icon="lucide:loader" id="approveLoader"></iconify-icon>
                        <span id="approveText">{{ __('Approve & Send Bank Details') }}</span>
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
