<!-- Modal for IB Member Confirmation -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="confirmModal"
    tabindex="-1"
    aria-labelledby="confirmModal"
    aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-8 text-center space-y-5">
                <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-success text-success bg-opacity-30">
                    <iconify-icon class="text-4xl" icon="lucide:check"></iconify-icon>
                </div>
                <div class="title">
                    <h4 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Confirm Successful IB Member') }}
                    </h4>
                </div>
                <p class="text-center dark:text-slate-300">
                    {{ __('Please confirm that the user has been made an IB Member. Malicious clicks will lead to account freezing.') }}
                </p>
                <div class="action-btns text-center">
                    <button type="button" class="btn btn-dark inline-flex items-center justify-center mr-2" id="confirmBtn">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Confirm') }}
                    </button>
                    <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
