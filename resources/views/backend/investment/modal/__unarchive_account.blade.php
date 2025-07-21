<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="unarchiveAccount" tabindex="-1" aria-labelledby="unarchiveAccount" aria-hidden="true">
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
                            {{ __('you want to reactivate this account!.') }}
                        </p>
                        <p>{{ __("If you unarchive this account you can update info/use") }}</p>
                    </div>
                    <div class="action-btns mt-10">
                        <input type="hidden" class="update-archive-login" name="login">
                        <button type="button" class="btn btn-primary inline-flex items-center justify-center mr-2 reactivate-account">
                            {{ __('Unarchive Account') }}
                        </button>
                        <a href="#" class="btn btn-outline-dark inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                            {{ __('Close') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
