<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="addRebateRuleModal" tabindex="-1" aria-labelledby="addRebateRuleModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                            {{ __('Add Rebate Rule') }}
                        </h3>

                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
                    <div class="p-6 pt-3">
                        <form action="" method="post" id="modalForm">
                            @csrf
                            <div class="space-y-5">
                                <div class="input-area relative">
                                    <label for="" class="form-label">{{ __('Title:') }}</label>
                                    <input
                                        type="text"
                                        name="title"
                                        class="form-control mb-0"
                                        placeholder="New York"

                                    />
                                    <div class="invalid-feedback" id="title-error" style="display: none;"></div>
                                </div>

                                <div class="input-area relative">
                                    <label for="" class="form-label">
                                        {{ __('Select Symbol Groups') }}
                                    </label>
                                    <select name="symbol_groups[]" class="select2 form-control w-full" multiple="multiple">

                                    </select>
                                    <div class="invalid-feedback" id="symbol-groups-error" style="display: none;"></div>
                                </div>
                                <div class="input-area relative">
                                    <label for="forex_schemas" class="form-label">{{ __('Select Account Types') }}</label>
                                    <select name="forex_schemas[]" class="select2 form-control w-full" multiple="multiple">
                                        @foreach($forexSchemas as $id => $title)
                                            <option value="{{ $id }}">{{ $title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="forex-schemas-error" style="display: none;"></div>
                                </div>

                                {{--                            <div class="input-area relative">--}}
    {{--                                <label for="ib_groups" class="form-label">{{ __('Select IB Groups') }}</label>--}}
    {{--                                <select name="ib_groups[]" class="select2 form-control w-full" multiple="multiple">--}}
    {{--                                    @foreach($ibGroups as $id => $name)--}}
    {{--                                        <option value="{{ $id }}">{{ $name }}</option>--}}
    {{--                                    @endforeach--}}
    {{--                                </select>--}}
    {{--                                <div class="invalid-feedback" id="ib-groups-error" style="display: none;"></div>--}}
    {{--                            </div>--}}

                                <div class="input-area relative">
                                    <label for="" class="form-label">{{ __('Rule Type:') }}</label>
                                    <select name="rule_type_id" class="form-control">
                                        <option value="">----</option>
                                        <option value="1">Per Lot</option>
                                    </select>
                                    <div class="invalid-feedback" id="rule-type-id-error" style="display: none;"></div>
                                </div>
                                <div class="input-area relative">
                                    <label for="" class="form-label">{{ __('Rebate Amount:') }}</label>
                                    <input
                                        type="text"
                                        name="rebate_amount"
                                        class="form-control mb-0"
                                        placeholder="$55.00"

                                    />
                                    <div class="invalid-feedback" id="rebate-amount-error" style="display: none;"></div>
                                </div>
                                <div class="input-area relative">
                                    <label for="" class="form-label">{{ __('Per Lot:') }}</label>
                                    <input
                                        type="text"
                                        name="per_lot"
                                        class="form-control mb-0"
                                        placeholder="1"

                                    />
                                    <div class="invalid-feedback" id="per-lot-error" style="display: none;"></div>
                                </div>
                                <div class="input-area relative">
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <label class="form-label !w-auto pt-0">
                                            {{ __('Status:') }}
                                        </label>
                                        <div class="form-switch ps-0">
                                            <input type="hidden" value="0" name="status">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="status" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                        <div class="invalid-feedback" id="status-error" style="display: none;"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="action-btns text-right mt-10">
                                <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                    {{ __('Add Rebate Rule') }}
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
</div>
