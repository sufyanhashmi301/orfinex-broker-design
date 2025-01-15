<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="expireContractModal" tabindex="-1" aria-labelledby="expireContractModal" aria-hidden="true">
  <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
      <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
          <div class="p-6 space-y-4">
            <form action="{{ route('admin.contract.mark_as') }}" method="POST">
                @csrf
                <div class="text-center">
                    <iconify-icon icon="heroicons:question-mark-circle-16-solid" class="display-1 mb-3"></iconify-icon>
                    <h3 class="mb-0">{{ __('Are you sure?') }}</h3>
                    <p class="lead my-3">  
                      You are marking the contract for account <b>(#<span class="account-login"></span>)</b> as expired, however, the expiry date of this contract is in <b><span class="rem-days"></span> days</b>. 
                    </p>

                    <input type="hidden" name="contract_id"  class="contract_id" value="">
                    <input type="hidden" name="action" value="expire">
                    
                    <div class="action-btns mt-5">
                        <button type="submit" id="confirmDeleteButton" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Mark as Expired') }}
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
                </form>
          </div>
      </div>
  </div>
</div>
