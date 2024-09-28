<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editDiscountModal" tabindex="-1" aria-labelledby="editDiscountModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
        rounded-md outline-none text-current">
            <div class="flex items-start justify-between gap-3 p-5">
                <div>
                    <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                        {{ __('Create New Discount Code') }}
                    </h3>

                </div>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="" method="post">
                    <div class="space-y-5">
                        @csrf
                        <div class="input-area !mt-0">
                            <label for="" class="form-label">{{ __('Code Name:') }}</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control mb-0"
                                placeholder="Code Name"
                                required
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Type:') }}</label>
                            <select id="discount_type" class="form-control w-100">
                                <option selected>{{ __('Select Type') }}</option>
                                <option value="fixed_amount_1">{{ __('Fixed') }}</option>
                                <option value="percentage_1">{{ __('Percentage') }}</option>
                            </select>
                        </div>
                        <div class="input-area">
                            <div class="discount-type hidden" data-div="fixed_amount_1">
                                <label for="" class="form-label">{{ __('Value:') }}</label>
                                <input
                                    type="text"
                                    name="fixed_amount"
                                    class="form-control mb-0"
                                    placeholder="Fixed Amount"
                                />
                            </div>
                            <div class="discount-type hidden" data-div="percentage_1">
                                <label for="" class="form-label">{{ __('Value:') }}</label>
                                <input
                                    type="text"
                                    name="percentage"
                                    class="form-control mb-0"
                                    placeholder="Percentage"
                                />
                            </div>
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Applies To:') }}</label>
                            <select name="applies_to" class="form-control w-100">
                                <option value="all account type">{{ __('All Account Type') }}</option>
                                <option value="challenge accounts only">{{ __('Challenge Accounts Only') }}</option>
                                <option value="direct funded accounts only">{{ __('Direct Funded Accounts Only') }}</option>
                            </select>
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Usage Limit:') }}</label>
                            <select name="usage_limit" class="form-control w-100">
                                <option value="unlimited">{{ __('Unlimited') }}</option>
                                <option value="100">{{ __('100 Uses') }}</option>
                                <option value="500">{{ __('500 Uses') }}</option>
                            </select>
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Expires On:') }}</label>
                            <input
                                type="text"
                                name="applies_to"
                                class="form-control flatpickr flatpickr-input mb-0"
                                placeholder="09/14/2024"
                                required
                            />
                        </div>

                        <div class="flex items-center space-x-7 flex-wrap">
                            <label class="form-label !w-auto mb-0">
                                {{ __('Status:') }}
                            </label>
                            <div class="form-switch ps-0" style="line-height: 0">
                                <input type="hidden" value="0" name="status">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="status" value="1" class="sr-only peer">
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="action-btns text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Update') }}
                        </button>
                        <a
                            href="#"
                            class="btn btn-outline-dark inline-flex items-center justify-center"
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
