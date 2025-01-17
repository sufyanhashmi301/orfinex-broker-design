<!-- Confirmation Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="resetConfirmationModal"
     tabindex="-1"
     aria-labelledby="resetConfirmationModal"
     aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-8 text-center space-y-5">
                <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                    <iconify-icon class="text-4xl" icon="lucide:shield-question"></iconify-icon>
                </div>
                <div class="title">
                    <h4 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Confirm Reset Credit of account: ') }} <span id="reset_credit_login">{{ $user->full_name ?? ''}}</span>
                    </h4>
                </div>
                <p class="dark:text-slate-300">
                    {{ __('Are you sure you want to reset the credit for this account? ') }}
                </p>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmResetBtn">Reset Credit</button>
            </div>
            </div>
        </div>
    </div>
</div>


