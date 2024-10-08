<!-- resources/views/modals/edit-level1-modal.blade.php -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editLevel1Modal" tabindex="-1" aria-labelledby="editLevel1Modal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="popup-body-text p-6 pt-5 edit-plugin-section">
                    <div class="text-center mb-5">
                        <h5 class="modal-title mb-3">{{ __('Change Status') }}</h5>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            {{ __('Choose the status for this KYC level: Active or Deactivate. Confirm your selection before saving.') }}
                        </p>
                    </div>
                    <form id="editStatusForm" action="" method="post">
                        @csrf
                        <div class="max-w-xs mx-auto mb-10">
                            <div class="input-area">
                                <div class="switch-field flex mb-3 overflow-hidden">
                                    <input
                                        type="radio"
                                        id="active-status"
                                        name="status"
                                        value="1"
                                    />
                                    <label for="active-status" class="dark:text-white">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="deactivate-status"
                                        name="status"
                                        value="0"
                                    />
                                    <label for="deactivate-status" class="dark:text-white">{{ __('Deactivate') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-area text-center">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                            <a href="" class="btn btn-danger inline-flex items-center justify-center" type="button"
                               class="btn-close"
                               data-bs-dismiss="modal"
                               aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
