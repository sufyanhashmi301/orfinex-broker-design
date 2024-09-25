<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="loginCredentialsModal" tabindex="-1" aria-labelledby="loginCredentialsModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
        rounded-md outline-none text-current">
            <div class="flex items-start justify-between gap-3 p-5">
                <div>
                    <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                        {{ __('Login Credentials') }}
                    </h3>
                    <p class="text-sm dark:text-white">
                        {{ __('Edit details for your Login Credentials') }}
                    </p>
                </div>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6">
                <div class="space-y-5">
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Login') }}</label>
                        <div class="relative">
                            <input class="form-control !pr-9" value="{{data_get($invest,'login')}}" id="copyModalLogin" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200" data-target="copyModalLogin">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Master Password') }}</label>
                        <div class="relative">
                            <input class="form-control !pr-9" type="password" id="passwordModal" value="{{data_get($invest,'main_password')}}" readonly>
                            <button class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password dark:text-slate-200" toggle="#passwordModal">
                                <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Read-only Password') }}</label>
                        <div class="relative">
                            <input class="form-control !pr-9" type="password" id="masterPassModal" value="" readonly>
                            <button class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password dark:text-slate-200" toggle="#masterPassModal">
                                <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Server') }}</label>
                        <div class="relative">
                            <input class="form-control !pr-9" type="text" value="{{data_get($invest->forexSchemaPhaseRule->forexSchemaPhase,'server')}}" id="copyServerModal" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button dark:text-slate-200" data-target="copyServerModal">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="action-btns text-right mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Save') }}
                    </button>
                    <a
                        href="#"
                        class="btn btn-outline-dark inline-flex items-center justify-center"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
