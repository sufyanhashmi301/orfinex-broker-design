<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="balanceOperation" tabindex="-1" aria-labelledby="balanceOperationModalLabel" aria-modal="true"
    role="dialog">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="balanceOperationLabel">
                    Adjust Account Balance
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="{{ route('admin.account.balance-operation.update') }}" method="post" class="space-y-4">
                    @csrf

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Account Balance Operation</label>
                      <select name="operation" class="form-control capitalize" id="" >
                        <option value="add">Add Balance</option>
                        <option value="remove" selected>Remove Balance</option>
                      </select>
                    </div>

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Amount</label>
                      <input type="number" value="0" required name="amount" class="form-control">
                    </div>

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Affect Trading Stats</label>
                      <select name="affect_stats" class="form-control capitalize" id="" >
                        <option value="1">Yes</option>
                        <option value="0" selected>No</option>
                      </select>
                    </div>

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Comments</label>
                      <textarea name="comments" id="" cols="30" rows="2" class="form-control" required>Equity/Balance Adjustment</textarea>
                    </div>

                    <input type="hidden" id="account_id_for_balance_operation" name="account_type_investment_id" value="">
                    
                    <div class="action-btns text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>