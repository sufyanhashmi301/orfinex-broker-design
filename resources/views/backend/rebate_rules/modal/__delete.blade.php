<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="deleteRebateRule"
     tabindex="-1"
     aria-labelledby="deleteRebateRule"
     aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-10 text-center">
                <div class="space-y-3">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger-500 text-danger-500 bg-opacity-30">
                        <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                    </div>
                    <div class="title">
                        <h4 class="text-2xl font-medium dark:text-white capitalize">
                            {{ __('Are you sure?') }}
                        </h4>
                    </div>
                    <p>
                        {{ __('You want to delete') }}
                        <strong class="name"></strong> {{ __('Rebate Rule?') }}
                    </p>
                    <div id="attached-groups" class="hidden">
                        <div class="text-left max-h-60 overflow-y-auto">
                            <h5 class="font-medium mb-2">{{ __('Attached IB Groups') }}:</h5>
                            <ul class="groups-list"></ul>
                        </div>
                        <div class="mt-4 text-red-500">
                            {{ __('Please detach these groups first before deleting the rule.') }}
                        </div>
                    </div>
                    <div id="no-groups" class="hidden">
                        <p class="text-green-500">{{ __('No IB Groups are attached to this rule.') }}</p>
                    </div>
                </div>
                <form method="POST" id="rebateRuleDeleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="action-btns mt-10">
                        <button type="submit" id="confirm-delete-btn" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Confirm') }}
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>