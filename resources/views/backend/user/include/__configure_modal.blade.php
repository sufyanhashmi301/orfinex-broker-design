<!-- Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="configureModal"
    tabindex="-1"
    aria-labelledby="configureModal"
    aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Columns Configuration') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="popup-body-text p-6 pt-0">
                    <form method="post" action="" class="space-y-5">
                        <div class="input-area">
                            <input type="text" name="" class="form-control" placeholder="Search...">
                        </div>
                        <h5 class="text-xl font-medium dark:text-white capitalize mb-1">General Info</h5>
                        <div class="input-area">
                            <div class="border border-slate-100 dark:border-slate-700">
                                <div class="p-1 bg-slate-100 dark:bg-slate-700">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" value="" id="userID" checked>
                                        <label class="form-check-label fw-normal" for="userID">
                                            User ID
                                        </label>
                                    </div>
                                </div>
                                <div class="p-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" value="" id="country" checked>
                                        <label class="form-check-label fw-normal" for="country">
                                            Country
                                        </label>
                                    </div>
                                </div>
                                <div class="p-1 bg-slate-100 dark:bg-slate-700">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" value="" id="email" checked>
                                        <label class="form-check-label fw-normal" for="email">
                                            Email
                                        </label>
                                    </div>
                                </div>
                                <div class="p-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" value="" id="balance" checked>
                                        <label class="form-check-label fw-normal" for="balance">
                                            Balance
                                        </label>
                                    </div>
                                </div>
                                <div class="p-1 bg-slate-100 dark:bg-slate-700">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" value="" id="type" checked>
                                        <label class="form-check-label fw-normal" for="type">
                                            Type
                                        </label>
                                    </div>
                                </div>
                                <div class="p-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" value="" id="status" checked>
                                        <label class="form-check-label fw-normal" for="status">
                                            Status
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="flex mb-2 items-end">
                                <h5 class="text-xl font-medium dark:text-white capitalize mb-0 mr-2">Selected columns 6/13</h5>
                                <a href="" class="text-sm text-primary">Clear selection</a>
                            </div>
                            <p class="text-sm mb-3">Order columns by drag-n-drop. Remove columns by clicking on the cross icon.</p>
                            <div class="flex flex-wrap gap-2">
                                <div class="flex items-center bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-sm">
                                    <span class="mr-1">User Id</span>
                                    <a href="" class="text-dark leading-none">
                                        <iconify-icon icon="lucide:x"></iconify-icon>
                                    </a>
                                </div>
                                <div class="flex items-center bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-sm">
                                    <span class="mr-1">Country</span>
                                    <a href="" class="text-dark leading-none">
                                        <iconify-icon icon="lucide:x"></iconify-icon>
                                    </a>
                                </div>
                                <div class="flex items-center bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-sm">
                                    <span class="mr-1">Email</span>
                                    <a href="" class="text-dark leading-none">
                                        <iconify-icon icon="lucide:x"></iconify-icon>
                                    </a>
                                </div>
                                <div class="flex items-center bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-sm">
                                    <span class="mr-1">Balance</span>
                                    <a href="" class="text-dark leading-none">
                                        <iconify-icon icon="lucide:x"></iconify-icon>
                                    </a>
                                </div>
                                <div class="flex items-center bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-sm">
                                    <span class="mr-1">Type</span>
                                    <a href="" class="text-dark leading-none">
                                        <iconify-icon icon="lucide:x"></iconify-icon>
                                    </a>
                                </div>
                                <div class="flex items-center bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-sm">
                                    <span class="mr-1">Status</span>
                                    <a href="" class="text-dark leading-none">
                                        <iconify-icon icon="lucide:x"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="input-area">
                            <div class="input-group relative flex flex-wrap items-stretch w-100">
                                <label class="flex items-center justify-center px-2 border border-slate-200 dark:border-slate-700" for="">
                                    Page limit
                                </label>
                                <select class="form-control w-100" name="">
                                    <option selected>Choose...</option>
                                    <option value="10">10</option>
                                    <option value="20" selected>20</option>
                                    <option value="30">30</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-right pt-3">
                            <button type="button" class="btn btn-dark inline-flex intems-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:save"></iconify-icon>
                                <span>Save changes</span>
                            </button>
                            <button type="button" class="btn btn-danger inline-flex intems-center justify-center" data-bs-dismiss="modal">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
