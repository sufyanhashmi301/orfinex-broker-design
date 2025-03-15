<!-- Modal Structure -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editLevel2" tabindex="-1" aria-labelledby="editLevel2" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <h5 class="modal-title" id="editLevel2Label">{{ __('Edit KYC Form') }}</h5>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="popup-body-text p-6 pt-3 ">
                    <form id="editKycForm" class="space-y-4">
                        <!-- Method and CSRF token will be dynamically added via JS -->
                        <div class="input-area">
                            <label class="form-label" for="name">{{ __('Name:') }}</label>
                            <input type="text" name="name" id="kycName" class="form-control" placeholder="KYC Type Name" required/>
                        </div>
                        <div class="">
                            <a href="javascript:void(0)" id="generate" class="btn btn-outline-dark btn-sm inline-flex items-center justify-center mb-3">
                                {{ __('Add Field option') }}
                            </a>
                        </div>
                        <div class="addOptions"></div>

                        <div class="max-w-xs">
                            <div class="input-area">
                                <label class="form-label" for="status">{{ __('Status:') }}</label>
                                <div class="switch-field flex mb-3 overflow-hidden">
                                    <input type="radio" id="active-status-1" name="status" value="1"/>
                                    <label for="active-status-1" class="dark:text-white">{{ __('Active') }}</label>
                                    <input type="radio" id="deactivate-status-1" name="status" value="0"/>
                                    <label for="deactivate-status-1" class="dark:text-white">{{ __('Deactivate') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-area text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center" id="submitBtn">
                            <span class="btn-text"> {{ __('Save Changes') }}</span>
                                <span class="btn-loader" style="display: none;">Loading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
