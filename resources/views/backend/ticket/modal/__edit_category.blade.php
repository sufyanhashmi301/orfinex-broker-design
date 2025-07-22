<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between p-5">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize">
                            {{ __('Update Ticket Category') }}
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-2">
                            {{ __('Changing the category will be reflect in all the departments and layouts where this category is being used') }}
                        </p>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-3" id="edit-category-body">

                </div>
            </div>
        </div>
    </div>
</div>
