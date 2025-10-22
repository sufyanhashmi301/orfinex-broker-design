<!-- Re-approve Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="reApproveModal" tabindex="-1" aria-labelledby="reApproveModalLabel" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-2xl w-full pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Re-approve Request & Attach Bank Details') }}
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
                    <form id="reApproveForm">
                        <input type="hidden" id="reApproveRequestId" name="request_id">

                        <div class="mb-4">
                            <div
                                class="bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <iconify-icon icon="lucide:check-circle"
                                            class="w-5 h-5 text-success-600 dark:text-success-400"></iconify-icon>
                                    </div>
                                    <div class="ltr:ml-3 rtl:mr-3">
                                        <h4 class="text-sm font-medium text-success-800 dark:text-success-200 mb-1">
                                            {{ __('Re-approve Rejected Request') }}
                                        </h4>
                                        <p class="text-sm text-success-700 dark:text-success-300">
                                            {{ __('This will change the request status from "Rejected" to "Approved" and attach the bank details below for the user.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Bank Name -->
                            <div class="input-area">
                                <label for="reApproveBankName" class="form-label">
                                    {{ __('Bank Name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="reApproveBankName" name="bank_name" class="form-control"
                                    placeholder="{{ __('Enter bank name') }}" required>
                            </div>

                            <!-- Account Name -->
                            <div class="input-area">
                                <label for="reApproveAccountName" class="form-label">
                                    {{ __('Account Name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="reApproveAccountName" name="account_name" class="form-control"
                                    placeholder="{{ __('Enter account holder name') }}" required>
                            </div>

                            <!-- Account Number -->
                            <div class="input-area">
                                <label for="reApproveAccountNumber" class="form-label">
                                    {{ __('Account Number') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="reApproveAccountNumber" name="account_number"
                                    class="form-control" placeholder="{{ __('Enter account number') }}" required>
                            </div>

                            <!-- Routing Number -->
                            <div class="input-area">
                                <label for="reApproveRoutingNumber" class="form-label">
                                    {{ __('Routing Number') }}
                                </label>
                                <input type="text" id="reApproveRoutingNumber" name="routing_number"
                                    class="form-control" placeholder="{{ __('Enter routing number (optional)') }}">
                            </div>

                            <!-- SWIFT Code -->
                            <div class="input-area">
                                <label for="reApproveSwiftCode" class="form-label">
                                    {{ __('SWIFT Code') }}
                                </label>
                                <input type="text" id="reApproveSwiftCode" name="swift_code" class="form-control"
                                    placeholder="{{ __('Enter SWIFT code (optional)') }}">
                            </div>

                            <!-- Bank Address -->
                            <div class="input-area">
                                <label for="reApproveBankAddress" class="form-label">
                                    {{ __('Bank Address') }}
                                </label>
                                <textarea id="reApproveBankAddress" name="bank_address" class="form-control" rows="3"
                                    placeholder="{{ __('Enter bank address (optional)') }}"></textarea>
                            </div>
                        </div>

                        <!-- Additional Instructions -->
                        <div class="input-area mt-4">
                            <label for="reApproveAdditionalInstructions" class="form-label">
                                {{ __('Additional Instructions') }}
                            </label>
                            <textarea id="reApproveAdditionalInstructions" name="additional_instructions" class="form-control" rows="3"
                                placeholder="{{ __('Enter any additional instructions for the user (optional)') }}"></textarea>
                        </div>
                    </form>
                    <div class="text-right pt-10">
                        <button type="button" id="reApproveSubmitBtn"
                            class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"
                                id="reApproveIcon"></iconify-icon>
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 hidden spining-icon" icon="lucide:loader"
                                id="reApproveLoader"></iconify-icon>
                            <span id="reApproveText">{{ __('Re-approve Request') }}</span>
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
