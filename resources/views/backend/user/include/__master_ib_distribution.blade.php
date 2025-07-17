<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="master-ib-modal" tabindex="-1" aria-labelledby="master-ib-modal-label" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <form id="master-ib-form" action="{{ route('admin.user.run-master-ib-distribution', $user->id) }}" method="POST">
                @csrf
                <div class="modal-header flex justify-between items-center p-4 border-b">
                    <h5 class="modal-title font-bold text-lg text-slate-800 dark:text-white" id="master-ib-modal-label">
                        {{ __('Master IB Network Distribution') }}
                    </h5>
                    <button type="button" class="btn-close text-2xl" data-bs-dismiss="modal" aria-label="{{ __('Close') }}">&times;</button>
                </div>
                <div class="modal-body p-6">
                    <div class="input-area">
                        <label for="master-ib-date" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the date to run the distribution for">
                                {{ __('Select Date') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="date" 
                                id="master-ib-date" 
                                class="form-control flatpickr-master-ib w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-slate-800 dark:text-white" 
                                placeholder="{{ __('YYYY-MM-DD') }}"
                                required
                            >
                        </div>
                    </div>
                    <div class="mt-4 p-4 rounded-md bg-yellow-50 border border-yellow-300 text-yellow-700 dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-200 text-sm">
                        <strong class="block mb-2">{{ __('Note:') }}</strong>
                        <ul class="list-disc list-inside space-y-1">
                            <li>{{ __('This distribution will be applied to the entire network of the master IB.') }}</li>
                            <li>{{ __('It will check for any missing distributions from the selected date up to now.') }}</li>
                            <li>{{ __('It will not distribute again for any deals that have already been processed.') }}</li>
                            <li>{{ __('If a user is missing from the network, the distribution will still go ahead using the current network structure.') }}</li>
                            <li>{{ __('Please make sure your IB network is set up correctly before continuing.') }}</li>
                        </ul>
                    </div>
                    <div class="flex justify-end items-center mt-10">
                        <button type="submit" class="btn btn-dark mr-2" id="run-master-ib-btn">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Run Distribution') }}
                            </span>
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
