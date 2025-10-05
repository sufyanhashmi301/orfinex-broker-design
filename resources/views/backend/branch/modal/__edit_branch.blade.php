<!-- Modal for Edit Branch -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg relative w-auto pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-b-slate-200 rounded-t-md">
                <h4 class="modal-title text-xl font-medium text-slate-600 dark:text-white" id="editBranchModalLabel">
                    {{ __('Edit Branch') }}
                </h4>
                <button type="button"
                    class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                    data-bs-dismiss="modal" aria-label="Close">
                    <iconify-icon class="text-xl" icon="line-md:close"></iconify-icon>
                </button>
            </div>
            <div id="edit-branch-body">
                <!-- Edit form will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>
