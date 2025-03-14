<!-- Change Account Type Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="changeAccountType" tabindex="-1" aria-labelledby="changeAccountType" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="relative bg-white rounded-lg shadow dark:bg-dark">
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize" id="modalTitle">
                            {{ __('Change Account Type') }}
                        </h3>
                        <p class="dark:text-white">
                            {{ __('Account:') }}
                            <span class="fw-bold" id="update-account-type-modal-login"></span>
                        </p>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">{{ __('Close modal') }}</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <form id="change-account-type-form">
                        @csrf
                        <input type="hidden" name="login" id="update-account-type-modal-login">
                        <div class="input-area relative">
                            <label class="form-label" for="account-type">{{ __('Account Type:') }}</label>
                            <div class="select2-lg">
                                <select class="select2 form-control !text-lg w-full mt-2 py-2" name="account_type" id="account-type">
                                    <option value="real" id="option-real">{{ __('Real') }}</option>
                                    <option value="demo" id="option-demo">{{ __('Demo') }}</option>
                                </select>
                                <small class="dark:text-slate-300 mt-1">
                                    {{ __('Choose the account type you want to set for this account.') }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="flex items-center mt-4">
                            <button type="button" class="btn btn-primary inline-flex items-center justify-center mr-2" id="submit-account-type">
                                {{ __('Change Account Type') }}
                            </button>
                            <a href="#" class="btn btn-outline-dark inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>