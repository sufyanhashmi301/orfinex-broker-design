<!-- Modal for Control Room (Edit) -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="controlRoomModal" tabindex="-1" aria-labelledby="controlRoomModal" aria-hidden="true">
    <div class="modal-dialog modal-xl top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between gap-3 p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                    {{ __('Control Room') }}
                </h3>
                <button type="button" class="btn btn-primary inline-flex items-center justify-center addRuleBtn" id="newRule">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:plus"></iconify-icon>
                    {{ __(' New Rule') }}
                </button>
            </div>
            <div class="modal-body px-6 pb-6">
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            @foreach($phases as $phase)
                                <h4 class="text-lg font-semibold mb-4 px-6">{{ __('Phase') }} {{ $loop->iteration }}</h4>
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rulesTable" id="rulesTable_{{ $loop->index }}">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('ID') }}</th> <!-- New Unique ID Column -->
                                        <th scope="col" class="table-th">{{ __('Funds') }}</th>
                                        <th scope="col" class="table-th">{{ __('Daily DD') }}</th>
                                        <th scope="col" class="table-th">{{ __('Max DD') }}</th>
                                        <th scope="col" class="table-th">{{ __('Profit Target') }}</th>
                                        <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                        <th scope="col" class="table-th">{{ __('Discount') }}</th>
                                        <th scope="col" class="table-th">{{ __('New Orders') }}</th>
                                        <th scope="col" class="table-th">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    <!-- Rules Table -->
                                    @foreach($phase->rules as $rule)
                                        <tr>
                                            <!-- Hidden field for rule ID -->
                                            <input type="hidden" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][id]" value="{{ $rule->id }}">
                                            <!-- Unique ID field - readonly -->
                                            <td class="table-td">
                                                <input type="text" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][unique_id]" value="{{ $rule->unique_id }}" class="form-control" readonly />
                                            </td>
                                            <!-- Other fields for the rule -->
                                            <td class="table-td">
                                                <input type="text" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][allotted_funds]" value="{{ $rule->allotted_funds }}" class="form-control validate-number" />
                                            </td>
                                            <td class="table-td">
                                                <input type="text" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][daily_drawdown_limit]" value="{{ $rule->daily_drawdown_limit }}" class="form-control validate-number" />
                                            </td>
                                            <td class="table-td">
                                                <input type="text" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][max_drawdown_limit]" value="{{ $rule->max_drawdown_limit }}" class="form-control validate-number" />
                                            </td>
                                            <td class="table-td">
                                                <input type="text" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][profit_target]" value="{{ $rule->profit_target }}" class="form-control validate-number" />
                                            </td>
                                            <td class="table-td">
                                                <input type="text" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][fee]" value="{{ $rule->amount }}" class="form-control validate-number" />
                                            </td>
                                            <td class="table-td">
                                                <input type="text" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][discount]" value="{{ $rule->discount }}" class="form-control validate-number" />
                                            </td>
                                            <td class="table-td">
                                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                    <input type="checkbox" name="rules[{{ $loop->parent->index }}][{{ $loop->index }}][is_new_order]" value="1" class="sr-only peer" {{ $rule->is_new_order ? 'checked' : '' }}>
                                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                </label>
                                            </td>
                                            <td class="table-td">
                                                <a href="#" class="action-btn deleteRule">
                                                    <iconify-icon icon="lucide:trash"></iconify-icon>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="action-btns mt-10">
                    <button type="button" class="btn btn-dark inline-flex items-center justify-center update-rules">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Update Rules') }}
                    </button>
                    <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


