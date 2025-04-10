<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="controlRoomModal" tabindex="-1" aria-labelledby="controlRoomModal1" aria-hidden="true">
    <div class="modal-dialog modal-xl top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between gap-3 p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                    {{ __('Control Room') }}
                </h3>

                <div>
                    <button type="button" class="btn btn-primary inline-flex items-center" id="newRule">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:plus"></iconify-icon>
                        {{ __(' New Rule') }}
                    </button>
    
                    @if (!isset( $account_type ))
                        <button type="button" class="btn btn-primary inline-flex items-center sample-data">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light " icon="lucide:database"></iconify-icon>
                            {{ __(' Sample Data') }}
                        </button>
                    @endif
                </div>
                
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
                <th scope="col" class="table-th pt-funded-th">{{ __('Profit Target') }}</th>
                <th scope="col" class="table-th">{{ __('Min. Trading Days') }}</th>
                <th scope="col" class="table-th multiple-phase-hidden">{{ __('Price') }}</th>
                <th scope="col" class="table-th multiple-phase-hidden">{{ __('Discount') }}</th>
                {{-- <th scope="col" class="table-th">{{ __('New Orders') }}</th> --}}
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
                    @if ($phase->type == \App\Enums\AccountTypePhase::FUNDED)
                        <th scope="col" class="table-th">{{ __('Minimum Profit') }}</th>
                    @else
                        <th scope="col" class="table-th">{{ __('Profit Target') }}</th>
                    @endif
                    <th scope="col" class="table-th ">{{ __('Min. Trading Days') }}</th>
                    <th scope="col" class="table-th" style="display: {{ (($phase->phase_step - 1) != 0 ? 'none' : '') }}">{{ __('Price') }}</th>
                    <th scope="col" class="table-th" style="display: {{ (($phase->phase_step - 1) != 0 ? 'none' : '') }}">{{ __('Discount') }}</th>

                    
                    {{-- <th scope="col" class="table-th">{{ __('New Orders') }}</th> --}}
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
                                <input {{ $phase->phase_step == 1 ? '' : 'readonly'  }} type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][allotted_funds]" class="form-control allotted-funds-field validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->allotted_funds }}" />
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
                            <td class="table-td">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][trading_days]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->trading_days }}" />
                            </td>
                            <td class="table-td" style="display: {{ (($phase->phase_step - 1) != 0 ? 'none' : '') }}">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][price]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->amount }}" />
                            </td>
                            <td class="table-td" style="display: {{ (($phase->phase_step - 1) != 0 ? 'none' : '') }}">
                                <input type="text" name="phases[{{ $phase->phase_step - 1 }}][rules][{{ $index }}][discount]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" data-value="{{ $rule->discount }}" />
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
        let newPhaseRuleData = (phase_index, isFunded) => {
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
                    'allotted_funds': $('#phases-data').find('.allotted-funds-field[name="phases[0][rules][' + i + '][allotted_funds]"]').attr('data-value'),
                    'rule_index': i,
                    'phase_data_element': new_rules_table
                }
                new_rule_row(new_rule_data, true)

            }

            $('#phases-data').append(new_rules_table)

            // 
            if(isFunded) {
                new_rules_table.find('.pt-funded-th').text('Minimum Profit')
            }

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

            $('#controlRoomModal').find('h3').text('Control Room for ' + $(this).parents('.account-type-phases').find('.card-title').text());

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

        // Fill sample data
        $('.sample-data').on('click', function() {
            let sampleData = [ 
                {
                    'alloted_funds': 10000,
                    'daily_dd': 500,
                    'max_dd': 1000,
                    'profit_target': 1000,
                    'trading_days': 4,
                    'price': 129,
                    'discount': 10,
                },
                {
                    'alloted_funds': 25000,
                    'daily_dd': 1250,
                    'max_dd': 2500,
                    'profit_target': 2500,
                    'trading_days': 4,
                    'price': 219,
                    'discount': 20,
                },
                {
                    'alloted_funds': 50000,
                    'daily_dd': 2500,
                    'max_dd': 5000,
                    'profit_target': 5000,
                    'trading_days': 4,
                    'price': 449,
                    'discount': 50,
                },
                {
                    'alloted_funds': 100000,
                    'daily_dd': 5000,
                    'max_dd': 10000,
                    'profit_target': 10000,
                    'trading_days': 4,
                    'price': 649,
                    'discount': 100,
                },
                {
                    'alloted_funds': 200000,
                    'daily_dd': 10000,
                    'max_dd': 20000,
                    'profit_target': 20000,
                    'trading_days': 4,
                    'price': 899,
                    'discount': 100,
                },
            ]

            let phase_step = $(this).parents('#controlRoomModal').find('.rulesTable').attr('data-phase')
            
            if(phase_step == 1) {
                $('.rulesTable').find('tbody tr').remove()
                    sampleData.forEach(package => {                
                    new_rule_row(package, false, true)
                });
            } else {

                let rules_rows = $(this).parents('#controlRoomModal').find('.rulesTable').find('tbody tr');

                for(let i=0; i < rules_rows.length; i++) {
                    let rule_row = $(this).parents('#controlRoomModal').find('.rulesTable').find('tbody tr').eq(i)

                    if(i < sampleData.length) {
                        rule_row.find('.daily_dd input').val(sampleData[i]['daily_dd'])
                        rule_row.find('.max_dd input').val(sampleData[i]['max_dd'])
                        rule_row.find('.profit_target input').val(sampleData[i]['profit_target'])
                        rule_row.find('.trading_days input').val(sampleData[i]['trading_days'])
                    }
                }
               
            }

            
        })
    </script>
@endpush


