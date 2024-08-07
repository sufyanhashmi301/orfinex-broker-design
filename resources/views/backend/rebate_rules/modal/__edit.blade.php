<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editSymbolGroupModal" tabindex="-1" aria-labelledby="editRebateRule" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                            {{ __('Edit Symbol Group') }}
                        </h3>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-3">
                    <form id="editRebateRuleForm" method="post" action="">
                        @method('put')
                        @csrf
                        <div class="input-area relative">
                            <label for="title" class="form-label">{{ __('Title:') }}</label>
                            <input type="text" name="title" class="form-control mb-0" placeholder="New York" id="title" />
                        </div>
                        <div class="input-area relative">
                            <label for="symbol_groups" class="form-label">{{ __('Select Symbol Groups') }}</label>
                            <select id="symbol_groups" name="symbol_groups[]" class="select2 form-control w-full" multiple="multiple"></select>
                        </div>
                        <div class="input-area relative">
                            <label for="rule_type_id" class="form-label">{{ __('Rule Type:') }}</label>
                            <select name="rule_type_id" class="form-control">
                                <option value="">----</option>
                                <option value="1">Per Lot</option>
                            </select>
                            <div class="invalid-feedback" id="rule-type-id-error" style="display: none;"></div>
                        </div>
                        <div class="input-area relative">
                            <label for="rebate_amount" class="form-label">{{ __('Rebate Amount:') }}</label>
                            <input type="text" name="rebate_amount" class="form-control mb-0" placeholder="$55.00" id="rebate_amount" />
                            <div class="invalid-feedback" id="rebate-amount-error" style="display: none;"></div>
                        </div>
                        <div class="input-area relative">
                            <label for="per_lot" class="form-label">{{ __('Per Lot:') }}</label>
                            <input type="text" name="per_lot" class="form-control mb-0" placeholder="1" id="per_lot" />
                            <div class="invalid-feedback" id="per-lot-error" style="display: none;"></div>
                        </div>
                        <div class="input-area relative">
                            <label for="status" class="form-label">{{ __('Status:') }}</label>
                            <select name="status" class="form-control" id="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="status-error" style="display: none;"></div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Update Rebate Rule') }}
                            </button>
                            <a href="#"
                                class="btn btn-danger inline-flex items-center justify-center"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </a>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
