<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="activityDetailsModal"
    tabindex="-1"
    aria-labelledby="activityDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="activityDetailsModalLabel">
                    {{ __('Activity Details') }}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-5 pt-0 space-y-4">
                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                    <h3 class="text-base font-medium text-slate-900 dark:text-white mb-4">{{ __('Activity Information') }}</h3>
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Activity') }}:</span>
                            <span class="text-slate-600 dark:text-slate-200 text-xs font-normal" id="act_action"></span>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Description') }}:</span>
                            <span class="text-slate-600 dark:text-slate-200 text-xs font-normal" id="act_description"></span>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Time') }}:</span>
                            <span class="text-slate-600 dark:text-slate-200 text-xs font-normal" id="act_datetime"></span>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-medium text-slate-900 dark:text-white">{{ __('IP') }}:</span>
                            <span class="text-slate-600 dark:text-slate-200 text-xs font-normal" id="act_ip"></span>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Location') }}:</span>
                            <span class="text-slate-600 dark:text-slate-200 text-xs font-normal" id="act_location"></span>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Agent') }}:</span>
                            <span class="text-slate-600 dark:text-slate-200 text-xs font-normal" id="act_agent"></span>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                    <h3 class="text-base font-medium text-slate-900 dark:text-white mb-4">{{ __('Meta Information') }}</h3>
                    <div id="act_meta" class="space-y-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>