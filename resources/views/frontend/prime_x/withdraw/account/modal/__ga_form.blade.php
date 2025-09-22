<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="accountCreationGaModal"
     tabindex="-1"
     aria-labelledby="accountCreationGaModalLabel"
     aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body px-6 py-6 text-center">
                <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-primary text-primary bg-opacity-30 mb-5" style="--tw-bg-opacity: 0.3;">
                    <iconify-icon class="text-4xl" icon="lucide:key-round"></iconify-icon>
                </div>
                <div class="title mb-2">
                    <h4 class="text-xl font-medium dark:text-white capitalize" id="accountCreationGaModalLabel">
                        {{ __('Verify with Authenticator App') }}
                    </h4>
                </div>
                <p class="dark:text-slate-100">{{ __('Enter the 6-digit code from your Google Authenticator app.') }}</p>
                <div class="input-area mt-5">
                    <input type="password" maxlength="6" pattern="[0-9]*" inputmode="numeric" class="form-control !text-lg text-center tracking-widest" id="accountCreationGaInput" placeholder="{{ __('Enter 6-digit code') }}" />
                </div>
                <div class="text-xs text-slate-500 mt-2">{{ __('Codes refresh every 30 seconds.') }}</div>
                <div class="action-btns mt-5">
                    <button type="button" class="accountCreationGaSubmitBtn btn btn-dark inline-flex items-center justify-center mr-2">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Verify') }}
                    </button>
                    <a href="javascript:;" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


