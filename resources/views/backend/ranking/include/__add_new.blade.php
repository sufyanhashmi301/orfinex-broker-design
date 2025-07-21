<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="addNewRanking" tabindex="-1" aria-labelledby="addNewRanking" aria-hidden="true">
    <div class="modal-dialog relative max-w-3xl pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Add New Ranking') }}
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
                <div class="popup-body-text p-6 pt-0">
                    <form action="{{ route('admin.ranking.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="col-span-2">
                                <div class="input-area lg:w-1/2 max-w-full">
                                    <label class="form-label" for="">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Upload an icon to visually represent this user rank">
                                            {{ __('Ranking Icon') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="wrap-custom-file">
                                        <input type="file" name="icon" id="icon" accept=".gif, .jpg, .png"/>
                                        <label for="icon">
                                            <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                            <span>{{ __('Upload Icon') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the rank number (e.g., 1, 2, 3…)">
                                            {{ __('Ranking') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <input type="text" name="ranking" value="{{ old('ranking') }}" class="form-control mb-0"
                                        placeholder="Eg: 1, 2, 3..." required=""/>
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Provide a name for this rank (e.g., Silver, Gold, Platinum)">
                                            {{ __('Ranking Name') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <input type="text" name="ranking_name" value="{{ old('ranking_name') }}"
                                        class="form-control mb-0" placeholder="Ranking Name" required=""/>
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Required deposit amount to qualify for this rank">
                                            {{ __('Minimum Deposit') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="joint-input relative">
                                        <input type="text" class="form-control" name="minimum_deposit" oninput="this.value = validateDouble(this.value)">
                                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center px-1">
                                            {{ setting('site_currency','global') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Minimum investment required for the rank">
                                            {{ __('Minimum Invest') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="joint-input relative">
                                        <input type="text" class="form-control" name="minimum_invest" oninput="this.value = validateDouble(this.value)">
                                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center px-1">
                                            {{ setting('site_currency','global') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Required number of referred users">
                                            {{ __('Minimum Referral') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <input type="text" name="minimum_referral" value="{{ old('minimum_referral') }}" oninput="this.value = validateDouble(this.value)"
                                        class="form-control mb-0"  required=""/>
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Combined deposits made by referred users">
                                            {{ __('Minimum Referral Deposit') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="joint-input relative">
                                        <input type="text" class="form-control" name="minimum_referral_deposit" oninput="this.value = validateDouble(this.value)">
                                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center px-1">
                                            {{ setting('site_currency','global') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Total investment from referred users">
                                            {{ __('Minimum Referral Invest') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="joint-input relative">
                                        <input type="text" class="form-control" name="minimum_referral_invest" oninput="this.value = validateDouble(this.value)">
                                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center px-1">
                                            {{ setting('site_currency','global') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Minimum earnings needed for the rank">
                                            {{ __('Minimum Earning') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="joint-input relative">
                                        <input type="text" class="form-control" name="minimum_earnings" oninput="this.value = validateDouble(this.value)">
                                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center px-1">
                                            {{ setting('site_currency','global') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Any bonus value this rank grants">
                                            {{ __('Bonus') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="joint-input relative">
                                        <input type="text" class="form-control" name="bonus" oninput="this.value = validateDouble(this.value)">
                                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 dark:text-slate-300 flex items-center justify-center px-1">
                                            {{ setting('site_currency','global') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <div class="input-area">
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Add extra details or benefits about this rank">
                                            {{ __('Description') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <textarea class="form-control summernote" rows="5" placeholder="Description">
                                        {{ old('description') }}
                                    </textarea>
                                    <input type="hidden" name="description">
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <div class="input-area">
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <label class="form-label !w-auto pt-0">
                                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Toggle to enable or disable this ranking level">
                                                {{ __('Status') }}
                                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                            </span>
                                        </label>
                                        <div class="form-switch ps-0">
                                            <input type="hidden" value="0" name="status">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="status" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <div class="action-btns text-right">
                                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                        {{ __('Add Ranking') }}
                                    </button>
                                    <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                        {{ __('Close') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
