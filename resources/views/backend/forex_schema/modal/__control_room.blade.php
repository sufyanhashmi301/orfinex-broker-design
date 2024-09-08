<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="controlRoomModal" tabindex="-1" aria-labelledby="controlRoomModal" aria-hidden="true">
    <div class="modal-dialog modal-xl top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between gap-3 p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                    {{ __('Control Room') }}
                </h3>
                <button type="button" class="btn btn-primary inline-flex items-center justify-center" id="newRule">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:plus"></iconify-icon>
                    {{ __(' New Rule') }}
                </button>
            </div>
            <div class="modal-body px-6 pb-6">
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="rulesTable">
                                <thead>
                                <tr>
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
                                @if(old('rules'))
                                    @foreach(old('rules') as $index => $rule)
                                        <tr>
                                            <td class="table-td"><input type="text" name="rules[{{ $index }}][allotted_funds]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="{{ $rule['allotted_funds'] }}" /></td>
                                            <td class="table-td"><input type="text" name="rules[{{ $index }}][daily_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="{{ $rule['daily_drawdown_limit'] }}" /></td>
                                            <td class="table-td"><input type="text" name="rules[{{ $index }}][max_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="{{ $rule['max_drawdown_limit'] }}" /></td>
                                            <td class="table-td"><input type="text" name="rules[{{ $index }}][profit_target]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="{{ $rule['profit_target'] }}" /></td>
                                            <td class="table-td"><input type="text" name="rules[{{ $index }}][fee]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="{{ $rule['fee'] }}" /></td>
                                            <td class="table-td"><input type="text" name="rules[{{ $index }}][discount]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="{{ $rule['discount'] }}" /></td>
                                            <td class="table-td">
                                                <input type="checkbox" name="rules[{{ $index }}][is_new_order]" class="form-check-input" value="1" {{ isset($rule['is_new_order']) ? 'checked' : '' }} />
                                            </td>
                                            <td class="table-td">
                                                <a href="#" class="action-btn deleteRule">
                                                    <iconify-icon icon="lucide:trash"></iconify-icon>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <!-- Add a default row if no rules are present -->
                                @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="action-btns mt-10">
                    <button type="button" class="btn btn-dark inline-flex items-center justify-center mr-2 update-rules">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Update') }}
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


