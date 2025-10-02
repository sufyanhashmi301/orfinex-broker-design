<!-- Modal for Delete Branch -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="deleteBranch" tabindex="-1" aria-labelledby="deleteBranchLabel" aria-hidden="true">
    <div class="modal-dialog relative w-auto pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-b-slate-200 rounded-t-md">
                <h4 class="modal-title text-xl font-medium text-slate-600 dark:text-white" id="deleteBranchLabel">
                    {{ __('Delete Branch') }}
                </h4>
                <button type="button"
                    class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                    data-bs-dismiss="modal" aria-label="Close">
                    <iconify-icon class="text-xl" icon="line-md:close"></iconify-icon>
                </button>
            </div>
            <form id="branchDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body relative p-4">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <iconify-icon class="text-red-600 text-2xl" icon="lucide:trash-2"></iconify-icon>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            {{ __('Delete Branch') }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            {{ __('Are you sure you want to delete') }} "<span class="font-medium branch-name"></span>"?
                        </p>

                        <!-- Usage Information -->
                        <div id="attached-usage" class="hidden mb-4">
                            <div class="usage-info"></div>
                        </div>

                        <div id="no-usage" class="hidden mb-4">
                            <div class="bg-green-50 border border-green-200 rounded p-3">
                                <p class="text-green-800 text-sm">
                                    {{ __('This branch can be safely deleted as no users or staff are currently assigned to it.') }}
                                </p>
                            </div>
                        </div>

                        <p class="text-xs text-gray-400">
                            {{ __('This action cannot be undone.') }}
                        </p>
                    </div>
                </div>
                <div
                    class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-t-slate-200 rounded-b-md">
                    <button type="button"
                        class="btn inline-flex justify-center text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 btn-sm mr-2"
                        data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" id="confirm-delete-btn"
                        class="btn inline-flex justify-center btn-danger btn-sm">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
