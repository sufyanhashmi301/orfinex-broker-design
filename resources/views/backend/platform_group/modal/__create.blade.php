<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="assignRiskBookModal" tabindex="-1" aria-labelledby="assignRiskBookModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                            {{ __('Assign Risk Book') }}
                        </h3>
                        <p class="text-slate-600 dark:text-slate-200">
                            {{ __('Enter your information to assign risk book') }}
                        </p>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-3">
                    <form action="{{ route('admin.groups.assignRiskBook') }}" method="post">
                        @csrf
                        <div class="space-y-5">
                            <div class="input-area relative">
                                <label for="" class="form-label">
                                    {{ __('Risk Book') }}
                                </label>
                                <select name="risk_book_id" class="select2 form-control w-full">
                                    @foreach($riskBooks as $riskBook)
                                        <option  value="{{ $riskBook->id }}">
                                            {{ $riskBook->name  }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area relative">
                                <label for="" class="form-label">
                                    {{ __('Platform Group') }}
                                </label>
                                <select name="group_ids[]" class="select2 form-control w-full" multiple>
                                    @foreach($groups as $group)
                                        <option  value="{{ $group->id }}">
                                            {{ $group->group  }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Assign Risk Book') }}
                            </button>
                            <a href="#"
                                class="btn btn-danger inline-flex items-center justify-center"
                                data-bs-dismiss="modal"
                                aria-label="Close">
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
