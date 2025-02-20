<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="dealNote"
     tabindex="-1"
     aria-labelledby="dealNote"
     aria-hidden="true"
>
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="relative bg-white rounded-lg shadow dark:bg-dark">
                <div class="flex items-center justify-between rounded-t p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Change Deal Stage') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">{{ __('Close modal') }}</span>
                    </button>
                </div>
                <div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6 pt-0">
                    <form action="{{ route('admin.deal.note.store') }}" method="post">
                        @csrf
                        <input type="hidden" id="dealIdInput" name="deal_id" value="">
                        <input type="hidden" id="stageSlugInput" name="title" value="">
                        <div class="space-y-5">
                            <div class="py-3 px-4 font-normal text-sm rounded-md bg-warning-500 bg-opacity-[14%]  text-white">
                                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                    <iconify-icon class="text-lg flex-0 text-warning-500" icon="lucide:info"></iconify-icon>
                                    <p class="flex-1 text-warning-500 font-Inter">
                                        {{ __('A New note will be create when stage changes to win or lost.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Close Date') }}</label>
                                    <input type="text" class="form-control flatpickr flatpickr-input">
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Deal Stage') }}</label>
                                    <input type="text" id="dealStageInput" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Remark') }}</label>
                                <textarea name="details" rows="5" class="form-control block w-full bg-transparent dark:text-white resize-none"></textarea>
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Add Note') }}
                            </button>
                            <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
