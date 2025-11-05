<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="deleteAccount" tabindex="-1" aria-labelledby="deleteAccount" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="p-6 py-10">
                <div class="text-center">
                    <div class="space-y-3">
                        <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                            <iconify-icon class="text-4xl" icon="lucide:trash-2"></iconify-icon>
                        </div>
                        <h3 class="text-2xl mb-0">{{ __('Confirm Delete') }}</h3>
                        <p class="lead dark:text-slate-300 my-3">
                            {{ __('Are you sure you want to delete this account?') }}
                        </p>
                    </div>
                    <div class="action-btns mt-10">
                        <input type="hidden" class="delete-login" name="login">
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center mr-2 dropdown-delete-account">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Delete Account') }}
                            </span>
                        </button>
                        <a href="#" class="btn btn-dark inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


