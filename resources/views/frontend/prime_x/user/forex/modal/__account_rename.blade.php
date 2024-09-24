<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="accountRename" tabindex="-1" aria-labelledby="accountRename" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="relative bg-white rounded-lg shadow dark:bg-dark">
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize" id="modalTitle">
                            {{ __('Rename Your Account') }}
                        </h3>
                        <p class="dark:text-white">
                            {{ __('Account:') }}
                            <span class="fw-bold update-name-modal-login"></span>
                        </p>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <input type="hidden" name="login" id="update-name-modal-login"  class="form-control update-name-modal-login"  >
                    <form action="" method="post">
                        @csrf
                        <label class="form-label" for="">{{ __('New nickname:') }}</label>
                        <div class="input-form-group">
                            <input type="text" class="form-control" name="nickname" id="update-name-modal-name" >
                            <small class="dark:text-slate-300 mt-1">{{ __('The name you want to update to remember.') }}</small>
                        </div>
                        <div class="action-btns mt-4">
                            <button type="submit" class="btn btn-dark mr-2" id="submit-name">
                                {{ __('Rename Account') }}
                            </button>
                            <a href="#" class="btn btn-danger inline-flex" data-bs-dismiss="modal" aria-label="Close">
                                {{ __('Close') }}
                            </a>
                        </div>
                        <div class="divider border-b dark:border-slate-700 my-5"></div>
                        <div class="flex">
                            <p class="text-xs dark:text-slate-300 mb-0">
                                {{ __('If you have multiple accounts, you can give each one a unique name to make navigation easier.') }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
