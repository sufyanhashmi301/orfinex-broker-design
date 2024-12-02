<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="ibGroupModal" tabindex="-1" aria-labelledby="ibGroupModal" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
        rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Add New IB Group') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-0">
                    <form action="{{ route('admin.ib-group.store') }}" method="post" class="space-y-4">
                        @csrf
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Name:') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="IB Group Name" required/>
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Details (Optional)') }}</label>
                            <textarea name="desc" class="form-control basicTinymce mb-0" rows="6" placeholder="Details about the IB group"></textarea>
                        </div>
                        <div class="input-area">
                            <label for="rebate_rule_id" class="form-label">{{ __('Attach Rebate Rule(s) (Optional)') }}</label>
                            <select name="rebate_rule_id[]" id="rebate_rule_id" class="select2 form-control w-full h-9" multiple>
                                <option value="">{{ __('Select Rebate Rule') }}</option>
                                @foreach($rebateRules as $rule)
                                    <option value="{{ $rule->id }}">{{ $rule->title }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto pt-0 !mb-0">
                                    {{ __('Status:') }}
                                </label>
                                <div class="form-switch ps-0" style="line-height: 0;">
                                    <input class="form-check-input" type="hidden" value="0" name="status">
                                    <label class="deposit-status relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                        <input type="checkbox" name="status" value="1" class="sr-only peer">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="input-area text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
