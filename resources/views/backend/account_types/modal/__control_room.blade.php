<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="controlRoomModal" tabindex="-1" aria-labelledby="controlRoomModal1" aria-hidden="true">
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
                        <div class="overflow-hidden rules-table-container">
                            
                        </div>
                    </div>
                </div>
                <div class="action-btns mt-10">
                    <button type="button" class="btn btn-dark inline-flex items-center justify-center mr-2 update-rules">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Update') }}
                    </button>
                    <a href="#" class="btn btn-danger inline-flex items-center close-modal justify-center" data-bs-dismiss="modal" aria-label="Close">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Phases Data --}}
<div style="display: none" id="phases-data">
    @if ( !isset( $account_type ) )
        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rulesTable" data-phase="1">
            <thead>
            <tr>
                <th scope="col" class="table-th">{{ __('Funds') }}</th>
                <th scope="col" class="table-th">{{ __('Daily DD') }}</th>
                <th scope="col" class="table-th">{{ __('Max DD') }}</th>
                <th scope="col" class="table-th">{{ __('Profit Target') }}</th>
                <th scope="col" class="table-th multiple-phase-hidden">{{ __('Price') }}</th>
                <th scope="col" class="table-th multiple-phase-hidden">{{ __('Discount') }}</th>
                <th scope="col" class="table-th">{{ __('New Orders') }}</th>
                <th scope="col" class="table-th multiple-phase-hidden">{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                
            </tbody>

        </table>

    @elseif (isset( $account_type ))
        @foreach ($account_type->accountTypePhases as $phase)
            
            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rulesTable" data-phase="{{ $phase->phase_step }}">
                <thead>
                <tr>
                    <th scope="col" class="table-th">{{ __('Unique ID') }}</th>
                    <th scope="col" class="table-th">{{ __('Funds') }}</th>
                    <th scope="col" class="table-th">{{ __('Daily DD') }}</th>
                    <th scope="col" class="table-th">{{ __('Max DD') }}</th>
                    <th scope="col" class="table-th">{{ __('Profit Target') }}</th>
                    <th scope="col" class="table-th" style="display: {{ (($phase->phase_step - 1) != 0 ? 'none' : '') }}">{{ __('Price') }}</th>
                    <th scope="col" class="table-th" style="display: {{ (($phase->phase_step - 1) != 0 ? 'none' : '') }}">{{ __('Discount') }}</th>
                    <th scope="col" class="table-th">{{ __('New Orders') }}</th>
                    @if ($phase->phase_step - 1 == 0)
                        <th scope="col" class="table-th">{{ __('Action') }}</th>
                    @endif
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                    @foreach($phase->accountTypePhaseRules as $index => $rule)
                        <tr>
                            <input type="hidden" class="hidden-rule-fields" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][id]" data-value="{{ $rule->id }}">
                            
                            <td class="table-td">
                                <input type="text" readonly class="form-control unique_id" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][unique_id]" data-value="{{ $rule->unique_id }}">
                            </td>
                            <td class="table-td">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][allotted_funds]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->allotted_funds }}" />
                            </td>
                            <td class="table-td">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][daily_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->daily_drawdown_limit }}" />
                            </td>
                            <td class="table-td">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][max_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->max_drawdown_limit }}" />
                            </td>
                            <td class="table-td">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][profit_target]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->profit_target }}" />
                            </td>
                            <td class="table-td" style="display: {{ (($phase->phase_step - 1) != 0 ? 'none' : '') }}">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][price]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->amount }}" />
                            </td>
                            <td class="table-td" style="display: {{ (($phase->phase_step - 1) != 0 ? 'none' : '') }}">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][discount]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->discount }}" />
                            </td>
                            <td class="table-td">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][is_new_order]" data-value="{{ $rule->is_new_order }}" class="sr-only peer">
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </td>
                            @if ($phase->phase_step - 1 == 0)
                                <td class="table-td">
                                    <a href="#" class="action-btn deleteRule disabled" style="opacity: 0.5; cursor: not-allowed">
                                        <iconify-icon icon="lucide:trash"></iconify-icon>
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>

            </table>
        @endforeach
        
    @endif
</div>

@push('single-script')
    <script>
        let newPhaseRuleData = (phase_index) => {
            let latest_rules_table = $('.rulesTable:last')
            let new_rules_table = latest_rules_table.clone()

            // rulesTable Phase
            new_rules_table.attr('data-phase', no_of_phases)

            // clear the html of rules
            let latest_phase_rules_count = latest_rules_table.find('tbody tr').length
            new_rules_table.find('tbody').html('')

            // add new rules similar to the latest one
            for(let i=0; i < latest_phase_rules_count; i++){
                let new_rule_data = {
                    'phase_index': phase_index,
                    'rule_index': i,
                    'phase_data_element': new_rules_table
                  }
                  new_rule_row(new_rule_data, true)
            }


            // empty the rows
            // for(let i=0; i < new_rules_table.find('input').length; i++){
            //     let rule_input = new_rules_table.find('input').eq(i)
            //     if(rule_input.attr('type') == 'checkbox'){
            //         rule_input.prop('checked', false)
            //         rule_input.attr('data-value', 0)
            //     }else{
            //         rule_input.val('')
            //         rule_input.attr('data-value', '')
            //     }
            // }
            

            $('#phases-data').append(new_rules_table)

            // Remove the delete column
            if(account_type_type != 'funded') {
                new_rules_table.find('thead').find('.multiple-phase-hidden').remove()
            }
        }

        // Control room button On click
        $(document).on('click', '.control-room-btn', function() {

            let phase_data = $('#phases-data').find('.rulesTable[data-phase="' + $(this).attr('data-phase') + '"]').clone();
            $('.rules-table-container').html(phase_data);

            if(phase_data.attr('data-phase') != '1') {
                // verification phase and funded phase
                if( phase_data.find('tbody tr').length == 0 ){
                    $('.rules-table-container').find('tbody').html(`<td colspan="7" class="text-sm pt-3" style="text-align: center; color: #999">No data here. Please add rules in Phase 1 (Evaluation Phase)</td>`)
                }

                $('#newRule').hide()
            }else{
                // evaluation phase
                $('#newRule').show()
            }

            $('#controlRoomModal').find('h3').text('Control Room for Phase ' + phase_data.attr('data-phase'));

            $('.rules-table-container').find('.rulesTable').find('input').each(function() {
                let dataValue = $(this).attr('data-value');

                if ($(this).attr('type') === 'checkbox') {
                    // If it's a checkbox and data-value is "1", check it; otherwise, uncheck
                    $(this).prop('checked', dataValue == "1");

                } else {
                    // For other input types, just set the value
                    $(this).val(dataValue);
                }
            });
        });
    </script>
@endpush


