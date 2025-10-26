<!-- Grace Period Status Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="gracePeriodModal" tabindex="-1" aria-labelledby="gracePeriodModalLabel" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <form method="POST" action="{{ route('admin.user.updateGracePeriod') }}" id="gracePeriodForm">
                @csrf
                <input type="hidden" name="user_id" id="formUserId">
                <div class="modal-header flex justify-between items-center p-4 border-b">
                    <h5 class="modal-title font-bold text-lg text-slate-800 dark:text-white" id="gracePeriodModalLabel">
                        {{ __('Grace Period Status') }}
                    </h5>
                    <button type="button" class="btn-close text-2xl" data-bs-dismiss="modal" aria-label="{{ __('Close') }}">&times;</button>
                </div>
                <div class="modal-body p-6">
                    <div class="flex flex-col space-y-2">
                        <label for="graceStatus" class="text-sm font-medium text-slate-600 dark:text-slate-300">
                            {{ __('Select Grace Period Status') }}
                        </label>
                        <div class="relative">
                            <select name="status" id="graceStatus" class="form-control w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-slate-800 dark:text-white">
                                <option value="1">Active Grace Period</option>
                                <option value="0">Remove Grace Period</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-area text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center space-x-2" id="save-grace-status-btn" data-loading-text="Processing...">
                            <iconify-icon class="text-xl" icon="lucide:check"></iconify-icon>
                            <span>{{ __('Save Changes') }}</span>
                        </button>
                        <button type="button" class="btn inline-flex justify-center btn-danger ml-2" data-bs-dismiss="modal">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            <span>{{ __('Cancel') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
