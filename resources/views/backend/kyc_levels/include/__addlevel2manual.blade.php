<!-- Modal Structure -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="addKycLevel2Formmodal" tabindex="-1" aria-labelledby="editLevel2" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <h5 class="modal-title" id="editLevel2Label">{{ __('Add KYC Form') }}</h5>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-3 edit-plugin-section">
                    <form action="{{ route('admin.kyc.storeLevel2') }}" method="post">
                        <input type="hidden" name="kyc_sub_level_id" value="{{the_hash(3)}}">
                        @csrf
                        <div class="space-y-5">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter a name for this KYC form (e.g., Personal Info, Identity Check)">
                                        {{ __('Name') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                       placeholder="KYC Type Name" required/>
                            </div>
                            <div>
                                <a href="javascript:void(0)"  class="generateCreate btn btn-outline-dark btn-sm inline-flex items-center justify-center mb-3">
                                    {{ __('Add Field option') }}
                                </a>
                            </div>
                            <div class="addOptions">
                            </div>
                            <div class="input-area">
                                <div class="input-area md:col-span-2">
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <div class="form-label !w-auto pt-0">
                                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable or disable this form for user access">
                                                {{ __('Status') }}
                                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                            </span>
                                        </div>
                                        <div class="form-switch ps-0">
                                            <input class="form-check-input" type="hidden" value="0" name="status"/>
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="status" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-area text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                            <a href="" class="btn btn-danger inline-flex items-center justify-center" type="button"
                               class="btn-close"
                               data-bs-dismiss="modal"
                               aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
