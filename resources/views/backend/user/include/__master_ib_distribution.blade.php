<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="master-ib-modal" tabindex="-1" aria-labelledby="master-ib-modal-label" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <form id="master-ib-form" action="{{ route('admin.user.run-master-ib-distribution', $user->id) }}" method="POST">
                @csrf
                <div class="modal-header flex justify-between items-center p-4 border-b">
                    <h5 class="modal-title font-bold text-lg text-slate-800 dark:text-white" id="master-ib-modal-label">
                        Master IB Network Distribution
                    </h5>
                    <button type="button" class="btn-close text-2xl" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body p-6">
                    <div class="flex flex-col space-y-2">
                        <label for="master-ib-date" class="text-sm font-medium text-slate-600 dark:text-slate-300">
                            Select Date
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="date" 
                                id="master-ib-date" 
                                class="form-control flatpickr-master-ib w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-slate-800 dark:text-white" 
                                placeholder="YYYY-MM-DD"
                                required
                            >
                           
                        </div>
                    </div>
                    <div class="mt-4 p-3 rounded-md bg-yellow-50 border border-yellow-300 text-yellow-700 dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-200 text-sm">
                        <strong>Note:</strong> If any user is missing in the network, the distribution will still proceed based on the current structure. Please ensure your IB network is correctly set up before continuing.
                    </div>
                </div>
                
                <div class="modal-footer flex justify-end p-4 border-t">
                    <button type="submit" class="btn btn-dark" id="run-master-ib-btn">
                        Run Distribution
                    </button>
                    <button type="button" class="btn btn-outline-dark ml-2" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>