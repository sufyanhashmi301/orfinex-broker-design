<!-- resources/views/modals/edit-level1-modal.blade.php -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editPlugin" tabindex="-1" aria-labelledby="editPlugin" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="popup-body-text p-6 pt-5 edit-plugin-section">
                    <form id="editStatusForm" action="" method="post" class="space-y-4">
                        @csrf
                        <div class="max-w-xs">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Status:') }}</label>
                                <div class="switch-field flex mb-3 overflow-hidden">
                                    <input
                                        type="radio"
                                        id="active-status"
                                        name="status"
                                        value="1"
                                    />
                                    <label for="active-status">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="deactivate-status"
                                        name="status"
                                        value="0"
                                    />
                                    <label for="deactivate-status">{{ __('Deactivate') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-area text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
