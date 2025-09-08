<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="deleteIbGroup"
     tabindex="-1"
     aria-labelledby="deleteIbGroup"
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
                        <strong class="name"></strong> {{ __('IB Group?') }}
                    </p>
                    <div id="attached-users" class="hidden">
                        <div class="text-left max-h-60 overflow-y-auto">
                            <h5 class="font-medium mb-2">{{ __('Attached Users') }}:</h5>
                            <ul class="users-list"></ul>
                        </div>
                        <div class="mt-4 text-red-500">
                            {{ __('Please remove these users first before deleting the group.') }}
                        </div>
                    </div>
                    <div id="no-users" class="hidden">
                        <p class="text-green-500">{{ __('No users are attached to this group.') }}</p>
                    </div>
                </div>
                <form method="POST" id="ibGroupDeleteForm">
                    @csrf
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