<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="unarchiveAccount" tabindex="-1" aria-labelledby="unarchiveAccount" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="p-6 space-y-4">
                <div class="text-center">
                    <iconify-icon icon="heroicons:question-mark-circle-16-solid" class="display-1 mb-3"></iconify-icon>
                    <h3 class="mb-0">{{ __('Are You Sure!') }}</h3>
                    <p class="lead my-3">
                        {{ __('you want to reactivate this account!.') }}
                    </p>
                    <p>{{ __("If you unarchive this account you can update info/use") }}</p>
                    <div class="action-btns mt-5">
                        <input type="hidden" class="update-archive-login" name="login">
                        <button type="button" class="btn btn-primary mr-2 reactivate-account">
                            {{ __('unarchive Account') }}
                        </button>
                        <a href="#" class="btn btn-outline-dark inline-flex" data-bs-dismiss="modal" aria-label="Close">
                            {{ __('Close') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
