<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="editReferral"
    tabindex="-1"
    aria-labelledby="editReferral"
    aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Edit Level') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form action="{{ route('admin.referral.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" class="referral-id">
                    <input type="hidden" name="type" class="referral-type">

                    <div class="popup-body-text p-6 pt-0 space-y-5">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Choose One:') }}</label>
                            <select name="referral_target_id" class="form-control w-100 mb-0 target_id" required>
                                <option value="">--{{ __('Select One') }}--</option>
                                @foreach( $targets as $target)
                                    <option value="{{ $target->id }}">{{ $target->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Target Amount:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control !pr-12 target-amount" name="target_amount" oninput="this.value = validateDouble(this.value)">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 min-w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center px-1 text-sm">
                                    {{ setting('site_currency','global') }}
                                </span>
                            </div>
                        </div>

                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Bounty:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control !pr-12 bounty" name="bounty" oninput="this.value = validateDouble(this.value)">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 min-w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">%</span>
                            </div>
                        </div>


                        <div class="input-area mb-0">
                            <label for="" class="form-label">{{ __('Description:') }}</label>
                            <textarea name="description" class="form-control basicTinymce description" rows="6"
                                      placeholder="Description"></textarea>
                        </div>

                        <div class="action-btns text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                            <a
                                href="#"
                                class="btn btn-danger inline-flex items-center justify-center"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
