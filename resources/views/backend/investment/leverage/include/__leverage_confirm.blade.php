<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="confirmLeverageAction"
    tabindex="-1"
    aria-labelledby="confirmLeverageActionLabel"
    aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-8 text-center space-y-5">
                <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-warning text-warning bg-opacity-30">
                    <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                </div>
                <div class="title">
                    <h4 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Confirm Action') }}
                    </h4>
                </div>
                <form id="leverageActionForm">
                    @csrf
                    <input type="hidden" name="action" id="action_type">
                    <input type="hidden" name="id" id="leverage_id">
                    <div class="modal-body">
                        <p>Are you sure you want to <span id="actionMessage"></span> this leverage update?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
