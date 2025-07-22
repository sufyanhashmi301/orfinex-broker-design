<!-- Include Bootstrap modal markup at the end of the file -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="deleteConfirmationModall"
    tabindex="-1"
    aria-labelledby="deleteConfirmationModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-10 text-center">
                <div class="space-y-3">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                        <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                    </div>
                    <div class="title">
                        <h4 class="text-2xl font-medium dark:text-white capitalize">
                            {{ __('Are you sure?') }}
                        </h4>
                    </div>
                    <p class="dark:text-slate-300">
                        {{ __('Are you sure you want to delete this user?') }}
                    </p>
                </div>
               
                <div class="action-btns text-center mt-3">
                    <form id="deleteForm" action="{{ route('admin.user.destroy', $id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="input-area">
                            <input
                                type="text"
                                id="admin_key"
                                name="admin_key"
                                class="form-control"
                                placeholder="{{ __('Enter Super-Admin Key') }}"
                                required
                            />
                        </div>
                        <div class="action-btns text-center mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Delete') }}
                            </button>
                            <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
