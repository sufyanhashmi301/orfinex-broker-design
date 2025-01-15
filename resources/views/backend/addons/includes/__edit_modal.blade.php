<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" tabindex="-1" id="editAddonModal{{ $addon->id }}" aria-labelledby="editAddonModal{{ $addon->id }}Label" aria-modal="true"
    role="dialog">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="editAddonLabel">
                    Edit Addon
                </h3>
                <button type="button"
                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                          dark:hover:bg-slate-600 dark:hover:text-white"
                    data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                              11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="{{ route('admin.addon.update') }}" method="post" class="space-y-5">
                    @csrf
                    <div class="site-input-area relative">
                      <label for="" class="form-label">Name</label>
                      <input type="text" name="name" class="form-control" value="{{ $addon->name }}" required="">
                    </div>  

                    <div class="site-input-area relative pt-2">
                      <label for="" class="form-label">Type</label>
                      
                      <select name="amount_type" class="form-control" id="">
                        <option value="percentage" {{ $addon->amount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ $addon->amount_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                      </select>
                    </div>

                    <div class="site-input-area relative pt-2">
                      <label for="" class="form-label">Amount</label>
                      <input type="number" name="amount" class="form-control" value="{{ $addon->amount }}" required="">
                    </div> 

                    <div class="site-input-area relative pt-2">
                      <label for="" class="form-label">Status</label>
                      
                      <select name="status" class="form-control" id="">
                        <option value="1" {{ $addon->status == 1 ? 'selected' : '' }}>Enable</option>
                        <option value="0" {{ $addon->status == 0 ? 'selected' : '' }}>Disable</option>
                      </select>
                    </div>

                    <div class="site-input-area relative pt-2">
                      <label for="" class="form-label">Description</label>
                      <textarea name="description" class="form-control" id="" cols="30" rows="2">{{ $addon->description }}</textarea>
                    </div> 

                    <input type="hidden" name="addon_id" value="{{ $addon->id }}">
                    
                    <div class="action-btns text-right pt-4">
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