<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="emailEditModal" tabindex="-1" aria-labelledby="accountRename" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                <div class="flex items-center justify-between rounded-t p-6 pb-0 dark:border-slate-600">
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-8 pt-0 space-y-4">
                    <div class="text-center">
                        <h3 class="text-xl font-medium dark:text-white capitalize mb-2">
                            {{ __('Update Email') }}
                        </h3>
                        <p class="dark:text-white">
                            {{ __('Enter the Email you would like to associate with your account below.') }}
                        </p>
                    </div>
                    <form action="" method="post">
                        @csrf
                        <div class="input-area phone-input-wrapper my-7">
                            <input type="text" class="form-control !text-lg" value="{{ $user->email }}">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center w-2/3">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
