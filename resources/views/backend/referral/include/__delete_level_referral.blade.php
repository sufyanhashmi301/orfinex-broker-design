<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="deleteReferral"
    tabindex="-1"
    aria-labelledby="deleteReferral"
    aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-8 text-center space-y-5">
                <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger-500 text-danger-500 bg-opacity-30">
                    <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                </div>
                <div class="title">
                    <h4 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Are you sure?') }}
                    </h4>
                </div>
                <p class="dark:text-slate-300">
                    You want to delete <strong class="target"></strong>?
                </p>

                <div class="action-btns text-center">
                    <form action="" method="post" id="level-delete">
                        @method('DELETE')
                        @csrf

                        <input type="hidden" name="type" class="level-type">

                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Confirm') }}
                        </button>
                        <a href="" type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
