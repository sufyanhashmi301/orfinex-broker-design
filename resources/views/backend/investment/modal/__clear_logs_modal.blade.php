<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="deleteLogConfirmation" tabindex="-1" aria-labelledby="deleteLogConfirmation" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="p-6 py-10">
                <div class="text-center">
                    <div class="space-y-3">
                        <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                            <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                        </div>
                        <h3 class="text-2xl mb-0">{{ __('Are You Sure!') }}</h3>
                        <p class="lead my-3">
                            {{ __('you want to clear all logs!.') }}
                        </p>
                        <p>{{ __("If you clear all logs you can't restore them.") }}</p>
                    </div>
                    <div class="action-btns mt-10">
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center mr-2 confirm-clear-logs">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Confirm') }}
                        </button>
                        <button type="button" class="btn btn-primary inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
