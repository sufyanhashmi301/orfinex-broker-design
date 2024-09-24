<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="addNewReferral"
    tabindex="-1"
    aria-labelledby="addNewReferral"
    aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Add New') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.referral.level.store') }}">
                    @csrf
                    <div class="popup-body-text p-6 pt-0 space-y-5">
                        <input type="hidden" name="type" class="referral-type">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Choose Type:') }}</label>
                            <select name="level_type" class="form-control w-100 mb-0" id="" required>
                                <option value="">--{{ __('Select One') }}--</option>
                                @foreach( $referralType as $key => $type)
                                    <option value="{{ $type->value }}">{{ ucwords($type->value) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Bounty:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control !pr-12" name="bounty" oninput="this.value = validateDouble(this.value)">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                                    %
                                </span>
                            </div>
                        </div>

                        <div class="action-btns text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Add New') }}
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
