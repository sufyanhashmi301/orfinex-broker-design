<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="newDiscountModal" tabindex="-1" aria-labelledby="newDiscountModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
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
            <div class="modal-body">
                <div class="p-6 pt-0 max-h-[calc(100vh-200px)] overflow-y-auto">
                    <form action="{{ route('admin.discounts.store') }}" method="POST">
                        <div class="space-y-5">
                        @csrf
                        <!-- Code Name -->
                            <div class="input-area !mt-0">
                                <label for="code_name" class="form-label">{{ __('Code Name') }}</label>
                                <input
                                    type="text"
                                    name="code_name"
                                    class="form-control mb-0"
                                    placeholder="Code Name"
                                    required
                                />
                            </div>

                            <!-- Discount Code -->
                            <div class="input-area">
                                <label for="code" class="form-label">{{ __('Code') }}</label>
                                <input
                                    type="text"
                                    name="code"
                                    class="form-control mb-0"
                                    placeholder="Discount Code"
                                    required
                                />
                            </div>

                            <!-- Type -->
                            <div class="input-area">
                                <label for="type" class="form-label">{{ __('Type') }}</label>
                                <select id="discounttype" name="type" class="form-control w-100" required>
                                    <option value="" disabled selected>{{ __('Select Type') }}</option>
                                    <option value="fixed">{{ __('Fixed') }}</option>
                                    <option value="percentage">{{ __('Percentage') }}</option>
                                </select>
                            </div>

                            <!-- Value based on type (Fixed or Percentage) -->
                            <div class="input-area">
                                <div class="discount-type hidden" data-div="fixed">
                                    <label for="fixed_amount" class="form-label">{{ __('Fixed Amount') }}</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        name="fixed_amount"
                                        class="form-control mb-0"
                                        placeholder="Fixed Amount"
                                    />
                                </div>
                                <div class="discount-type hidden" data-div="percentage">
                                    <label for="percentage" class="form-label">{{ __('Percentage') }}</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        name="percentage"
                                        class="form-control mb-0"
                                        placeholder="Percentage"
                                    />
                                </div>
                            </div>

                            <!-- Applied To -->
    {{--                        <div class="input-area">--}}
    {{--                            <label for="applied_to" class="form-label">{{ __('Applies To') }}</label>--}}
    {{--                            <input--}}
    {{--                                type="text"--}}
    {{--                                name="applied_to"--}}
    {{--                                class="form-control mb-0"--}}
    {{--                                placeholder="Specific product or category (optional)"--}}
    {{--                            />--}}
    {{--                        </div>--}}

                            <!-- Usage Limit -->
                            <div class="input-area">
                                <label for="usage_limit" class="form-label">{{ __('Usage Limit') }}</label>
                                <input
                                    type="number"
                                    name="usage_limit"
                                    class="form-control mb-0"
                                    placeholder="Usage Limit"
                                    value="1"
                                    required
                                />
                            </div>

                            <!-- Expiry Date -->
                            <div class="input-area">
                                <label for="expire_at" class="form-label">{{ __('Expires On') }}</label>
                                <input
                                    type="text"
                                    name="expire_at"
                                    class="form-control flatpickr flatpickr-input mb-0"
                                    placeholder="Select Expiry Date"
                                    required
                                />
                            </div>

                            <!-- Status -->
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto mb-0">
                                    {{ __('Status') }}
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
                                {{ __('Add Discount Code') }}
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
</div>
