<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 all-phases">

  {{-- On Creation --}}
  @if ( !isset( $account_type ) )
    {{-- Phase 1 --}}
    <div class="card account-type-phases latest-phase" >
      <div class="card-header noborder">
        <h4 class="card-title">{{ __('Phase 1') }}</h4>
        <input type="hidden" name="phases[0][phase_step]" class="phase-step" value="1">
      </div>
      
      <div class="card-body p-6 pt-0 space-y-5">

        {{-- Phases Type Options --}}
        
        <div class="input-area !mb-7">
          <div class="flex items-center space-x-7 flex-wrap phase-options">
            
            <div class="primary-radio evaluation-phase">
              <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden phase-type" name="phases[0][type]" value="{{ \App\Enums\AccountTypePhase::EVALUATION }}"
                  {{ old('phases.0.type') == \App\Enums\AccountTypePhase::EVALUATION || is_null(old('phases.0.type')) ? 'checked' : '' }}>
                <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-primary text-sm leading-6 capitalize">
                  {{ __('Evaluation Phase') }}
                </span>
              </label>
            </div>

            <div class="primary-radio verification-phase" style="display: none">
              <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden phase-type" name="phases[0][type]" value="{{ \App\Enums\AccountTypePhase::VERIFICATION }}" {{ old('phases.0.type') == \App\Enums\AccountTypePhase::VERIFICATION ? 'checked' : '' }}>
                <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary text-sm leading-6 capitalize">
                  {{ __('Verification Phase') }}
                </span>
              </label>
            </div>

            <div class="primary-radio funded-phase" style="display: none">
              <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden phase-type" name="phases[0][type]" value="{{ \App\Enums\AccountTypePhase::FUNDED }}" {{ old('phases.0.type') == \App\Enums\AccountTypePhase::FUNDED ? 'checked' : '' }}>
                <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-info text-sm leading-6 capitalize">
                  {{ __('Funded Phase') }}
                </span>
              </label>
            </div>

          </div>
        </div>

        <div class="input-area">
          <label class="form-label" for="">{{ __('Phase Approval') }}</label>
          <select readonly style="pointer-events: none" name="phases[0][phase_approval_method]" class="cursor-pointer phase-approval-method form-control w-full">
            <option value="{{ \App\Enums\PhaseApproval::PAYMENT }}" class="on_payment_approval" {{ old('phases.0.phase_approval_method', 'payment') == 'payment' ? 'selected' : '' }}>On Payment Approval</option>
            <option value="{{ \App\Enums\PhaseApproval::AUTO }}" {{ old('phases.0.phase_approval_method') == 'auto' ? 'selected' : '' }}>Auto Approval</option>
            <option value="{{ \App\Enums\PhaseApproval::ADMIN }}" {{ old('phases.0.phase_approval_method') == 'admin' ? 'selected' : '' }}>Admin Approval</option>
          </select>
        </div>

        {{-- Temporarily Removed --}}
        <div class="input-area" style="display: none">
          <label class="form-label" for="">{{ __('Validity Period') }}</label>
          <select name="phases[0][validity_period]" class="cursor-pointer validity-period form-control w-full">
            @for ($i = 1; $i <= 12; $i++)
              <option value="{{ $i }}" {{ old('phases.0.validity_period') == $i ? 'selected' : '' }}>
              {{ $i }} {{ __('Month') }}
              </option>
              @endfor
          </select>
        </div>

        {{-- Server Info --}}
        <div class="input-area">
          <label class="form-label" for="">{{ __('Server') }}</label>
          <select name="phases[0][server]" class="cursor-pointer phase-server form-control w-full">
            @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
              <option value="{{ setting('live_server', 'platform_api') }}" {{ old('phases.0.server') == setting('live_server', 'platform_api') ? 'selected' : '' }}>
                {{ setting('live_server', 'platform_api') }}
              </option>
            @elseif (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT)
              <option value="{{ setting('mt_live_server_real', 'match_trader_platform_api') }}" {{ old('phases.0.server') == setting('mt_live_server_real', 'match_trader_platform_api') ? 'selected' : '' }}>
                {{ setting('mt_live_server_real', 'match_trader_platform_api') }}
              </option>
            @endif
          </select>
        </div>
        

        <div class="flex items-center gap-3">
          <button type="button" class="btn btn-outline-dark control-room-btn inline-flex items-center justify-center w-full"
            data-bs-toggle="modal" data-bs-target="#controlRoomModal" data-phase="1" style="border-width: 2px">
            {{ __('Edit Trading Objectives') }}
          </button>
          
          <button type="button" class="btn btn-outline-danger inline-flex items-center justify-center w-full delete-phase hidden" style="border-width: 2px">Delete</button>

        </div>

      </div>
    </div>

    

  @elseif (isset( $account_type ))

    {{-- On Update --}}
    @foreach($account_type->accountTypePhases as $phase)
      {{-- All phases  --}}

      <div class="card account-type-phases latest-phase">
        <div class="card-header noborder">
          <h4 class="card-title">{{ __('Phase ' . $phase->phase_step) }}</h4>
          <input type="hidden" name="phases[{{ $phase->phase_step - 1 }}][phase_step]" class="phase-step" value="{{ $phase->phase_step }}">
          <input type="hidden" name="phases[{{ $phase->phase_step - 1 }}][id]" class="phase-step" value="{{ $phase->id }}">
        </div>
        
        <div class="card-body p-6 pt-0 space-y-5">
  
          {{-- Phases Type Options --}}
          
          <div class="input-area !mb-7">
            <div class="flex items-center space-x-7 flex-wrap phase-options">
              
              @if ($phase->type == \App\Enums\AccountTypePhase::EVALUATION)
                <div class="primary-radio evaluation-phase">
                  <label class="flex items-center cursor-pointer">
                    <input type="radio" class="hidden phase-type" name="phases[{{ $phase->phase_step - 1 }}][type]" value="{{ \App\Enums\AccountTypePhase::EVALUATION }}" {{ $phase->type == \App\Enums\AccountTypePhase::EVALUATION ? 'checked' : '' }}>
                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                    <span class="text-primary text-sm leading-6 capitalize">
                      {{ __('Evaluation Phase') }}
                    </span>
                  </label>
                </div>
              @endif
  
              @if ($phase->type == \App\Enums\AccountTypePhase::VERIFICATION)
                <div class="primary-radio verification-phase" style="display: none">
                  <label class="flex items-center cursor-pointer">
                    <input type="radio" class="hidden phase-type" name="phases[{{ $phase->phase_step - 1 }}][type]" value="{{ \App\Enums\AccountTypePhase::VERIFICATION }}" {{ $phase->type == \App\Enums\AccountTypePhase::VERIFICATION ? 'checked' : '' }}>
                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                    <span class="text-secondary text-sm leading-6 capitalize">
                      {{ __('Verification Phase') }}
                    </span>
                  </label>
                </div>
              @endif
  
              @if ($phase->type == \App\Enums\AccountTypePhase::FUNDED)
                <div class="primary-radio funded-phase" style="display: none">
                  <label class="flex items-center cursor-pointer">
                    <input type="radio" class="hidden phase-type" name="phases[{{ $phase->phase_step - 1 }}][type]" value="{{ \App\Enums\AccountTypePhase::FUNDED }}" {{ $phase->type == \App\Enums\AccountTypePhase::FUNDED ? 'checked' : '' }}>
                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                    <span class="text-info text-sm leading-6 capitalize">
                      {{ __('Funded Phase') }}
                    </span>
                  </label>
                </div>
              @endif
  
            </div>
          </div>

          <div class="input-area">
            <label class="form-label" for="">{{ __('Phase Approval') }}</label>
            <select {!! $phase->phase_step == 1 ? 'readonly style="pointer-events: none"' : '' !!} name="phases[{{ $phase->phase_step - 1 }}][phase_approval_method]" class="cursor-pointer phase-approval-method form-control w-full">
              @if ($phase->phase_step == 1)
                <option value="{{ \App\Enums\PhaseApproval::PAYMENT }}" class="on_payment_approval" {{ $phase->phase_approval_method == \App\Enums\PhaseApproval::PAYMENT ? 'selected' : '' }} >On Payment Approval</option>                
              @endif
              <option value="{{ \App\Enums\PhaseApproval::AUTO }}" {{ $phase->phase_approval_method == \App\Enums\PhaseApproval::AUTO ? 'selected' : '' }}>Auto Approval</option>
              <option value="{{ \App\Enums\PhaseApproval::ADMIN }}" {{ $phase->phase_approval_method == \App\Enums\PhaseApproval::ADMIN ? 'selected' : '' }}>Admin Approval</option>
            </select>
          </div>

          <div class="input-area" style="display: none">
            <label class="form-label" for="">{{ __('Validity Period') }}</label>
            <select name="phases[{{ $phase->phase_step - 1 }}][validity_period]" class="cursor-pointer validity-period form-control w-full">
              @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ $phase->validity_period == $i ? 'selected' : '' }}>
                  {{ $i }} {{ __('Month') }}
                </option>
                @endfor
            </select>
          </div>

          <div class="input-area">
            <label class="form-label" for="">{{ __('Server') }}</label>
            <select name="phases[{{ $phase->phase_step - 1 }}][server]" class="cursor-pointer phase-server form-control w-full">
              @if ($account_type->trader_type == \App\Enums\TraderType::MT5)
                <option value="{{ setting('live_server', 'platform_api') }}" selected>
                  {{ setting('live_server', 'platform_api') }}
                </option>
              @endif
              @if ($account_type->trader_type == \App\Enums\TraderType::MT)
                <option value="{{ setting('mt_live_server_real', 'match_trader_platform_api') }}" selected>
                  {{ setting('mt_live_server_real', 'match_trader_platform_api') }}
                </option>
              @endif
              
            </select>
          </div>
  
          <div class="flex items-center gap-3">
            <button type="button" class="btn btn-outline-dark control-room-btn  inline-flex items-center justify-center w-full"
              data-bs-toggle="modal" data-bs-target="#controlRoomModal" data-phase="{{ $phase->phase_step }}" style="border-width: 2px">
              {{ __('Control Room') }}
            </button>
            
     
            {{-- <button type="button" class="btn btn-outline-danger inline-flex items-center justify-center w-full delete-phase {{ $phase->phase_step == 1 ? 'hidden' : '' }}" style="border-width: 2px">Delete</button> --}}
           

          </div>
        </div>
      </div>

    @endforeach

  @endif

  {{-- Add Another Phase --}}
  <div class="card add-phase-container border border-gray-300 cursor-pointer" style="display: none">
    <div class="card-body h-full p-6">
      <div class="flex w-full h-full divide-x divide-gray-300">
  
        <div class="flex-1 add-phase flex items-center justify-center p-4 mr-5" style="border: 2px solid #eee; border-radius: 4px" data-type="verification">
          <span class="flex flex-col items-center justify-center">
            <iconify-icon class="text-3xl font-light mb-1" icon="tabler:layout-grid-add"></iconify-icon>
            {{ __('Add Verification Phase') }}
          </span>
        </div>
  
        <div class="flex-1 add-phase flex items-center justify-center p-4" style="border: 2px solid #eee; border-radius: 4px" data-type="funded">
          <span class="flex flex-col items-center justify-center">
            <iconify-icon class="text-3xl font-light mb-1" icon="tabler:layout-grid-add"></iconify-icon>
            {{ __('Add Funded Phase') }}
          </span>
        </div>
  
      </div>
    </div>
  </div>
  

  

</div>

@push('single-script')
  <script>

    let account_type_type = $('#account-type-type').val()
    
    let verification_phase_option_html
    let funded_phase_option_html

    let no_of_phases = $('.account-type-phases').length;

    let updating = false
    // check if we are updating the account type or creating
    @if (isset( $account_type ))
      updating = true
    @endif

    // Adding Phases Dynamically
    $('.add-phase').on('click', function(){
      if( account_type_type == 'funded') {
        return false
      }

      // cannot create another phase if funded has been created
      if($('input.phase-type[value="Funded Phase"]').length > 0){
        alert('Cannot create another phase if funded has been created.')
        return false
      }

      let latest_phase = $('.account-type-phases:last');
      let new_phase = latest_phase.clone()
      no_of_phases++

      // --- Newly created Phase Modifications ---
      

      // change phase step
      new_phase.find('.phase-step').val(no_of_phases)
      new_phase.find('.phase-step').attr('name', `phases[${no_of_phases - 1}][phase_step]`)
      

      // type
      new_phase.find('.phase-options').html('')
      if( no_of_phases > 1 ) {
        if( $(this).attr('data-type') == 'verification' ) {
          new_phase.find('.phase-options').append( verification_phase_option_html )
        }
        if( $(this).attr('data-type') == 'funded' ) {
          new_phase.find('.phase-options').append( funded_phase_option_html )
          $('.add-phase-container').hide()
        }
      }
      new_phase.find('.phase-type').attr('name', `phases[${no_of_phases - 1}][type]`)
      new_phase.find('.phase-type').removeAttr('checked')
      new_phase.find('.phase-type').first().prop('checked', true)

      // Phase approval method
      new_phase.find('.phase-approval-method').attr('name', `phases[${no_of_phases - 1}][phase_approval_method]`)
      new_phase.find('.phase-approval-method').removeAttr('style')
      new_phase.find('.phase-approval-method').removeAttr('readonly')
      new_phase.find('.phase-approval-method .on_payment_approval').eq(0).remove()
      new_phase.find('.phase-approval-method option[value="auto_approval"]').prop('selected', true)

      // Validity Period
      new_phase.find('.validity-period').attr('name', `phases[${no_of_phases - 1}][validity_period]`)

      // Phase Server
      new_phase.find('.phase-server').attr('name', `phases[${no_of_phases - 1}][server]`)
      
      // Control Room Button 
      new_phase.find('.control-room-btn').attr('data-phase', no_of_phases)

      // Show delete option in the next phase
      new_phase.find('.delete-phase').removeClass('hidden')

      // Add the phase after the latest created phase
      $(new_phase).insertAfter('.account-type-phases:last')

      // title
      // new_phase.find('.card-title').text('Phase ' + no_of_phases)
      for(let i=0; i < $('.account-type-phases').length; i++) {
        $('.account-type-phases').eq(i).find('.card-title').text('Phase ' + (i + 1))
      }



      // Add new Rules Table
      newPhaseRuleData((no_of_phases - 1))
    })

    // delete phase
    $(document).on('click', '.delete-phase', function() {
      let phase = $(this).parents('.account-type-phases')
      if(phase.index() == 0) {
        alert('Not allowed to remove Phase 1')
      } else {
        // delete the phase and its rules
        let deleted_phase_step = phase.find('.phase-step').val()
        $('#phases-data').find('.rulesTable[data-phase="' + deleted_phase_step + '"]').remove()
        
        // Show the controls to add more phases if the funded phase is  removed
        if(phase.find('.funded-phase').length > 0){
          $('.add-phase-container').css('display', 'block')
        } 

        // then delete the phase
        phase.remove()

        // rearrange the title of phases
        for(let i=0; i < $('.account-type-phases').length; i++) {
          $('.account-type-phases').eq(i).find('.card-title').text('Phase ' + (i + 1))
        }

      }
    })

    verification_phase_option_html = $('.account-type-phases').find('.verification-phase').removeAttr('style').prop('outerHTML')
    funded_phase_option_html = $('.account-type-phases').find('.funded-phase').removeAttr('style').prop('outerHTML')

    // if there is only one phase then remove the funded option

    let phaseOneSettings = () => {
      if( $('.account-type-phases').length == 1 && account_type_type == 'challenge') {
        $('.account-type-phases').find('.verification-phase').remove()
        $('.account-type-phases').find('.funded-phase').remove()
        $('.add-phase-container').css('display', 'block')
      }

      if( $('.account-type-phases').length == 1 && account_type_type == 'funded') {
        $('.account-type-phases').find('.verification-phase').remove()
        $('.account-type-phases').find('.evaluation-phase').remove()
        $('.account-type-phases').find('.funded-phase .phase-type').prop('checked', true)
        $('.add-phase-container').remove()
      }

    }
    phaseOneSettings()
  
    // new row
    let new_rule_row = (data = {}, isData = false, sampleData= false) => {
      let phase_index
      let rule_index
      if(!isData){
        phase_index = $('.rules-table-container').find('.rulesTable').attr('data-phase') - 1
        rule_index = $('.rules-table-container').find('.rulesTable tbody tr').length;
      }

      // if the data to add row in other phases is provided, and that is not currently opened in modal
      if(isData){
        phase_index = data['phase_index']
        rule_index = data['rule_index']
      }

      let delete_rule_html = `<td class="table-td" >
                <a href="#" class="action-btn deleteRule">
                    <iconify-icon icon="lucide:trash"></iconify-icon>
                </a>
            </td>`
      let updating_phases_html = `<td class="table-td">
                                <input type="text" readonly class="form-control unique_id" name="phases[${phase_index}][rules][${rule_index}][unique_id]" data-value="">
                            </td>`

      const newRow = `<tr>
          ${ updating ? updating_phases_html : '' }
          <td class="table-td"><input type="text" ${ phase_index != 0 ? 'readonly' : '' } name="phases[${phase_index}][rules][${rule_index}][allotted_funds]" class="form-control validate-number allotted-funds-field" data-value="${ phase_index == 0 ? '' : data['allotted_funds'] }"  oninput="this.value = validateDouble(this.value)" value="${sampleData ? data.alloted_funds : ''}" /></td>

          <td class="table-td daily_dd"><input type="text" name="phases[${phase_index}][rules][${rule_index}][daily_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="${sampleData ? data.daily_dd : ''}" /></td>
          <td class="table-td max_dd"><input type="text" name="phases[${phase_index}][rules][${rule_index}][max_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="${sampleData ? data.max_dd : ''}" /></td>
          <td class="table-td profit_target"><input type="text" name="phases[${phase_index}][rules][${rule_index}][profit_target]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="${sampleData ? data.profit_target : ''}" /></td>
          <td class="table-td trading_days"><input type="text" name="phases[${phase_index}][rules][${rule_index}][trading_days]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="${sampleData ? data.trading_days : ''}" /></td>

          <td class="table-td" style="display: ${ phase_index == 0 ? '' : 'none' }" ><input type="${ phase_index == 0 ? 'text' : 'hidden' }" ${ phase_index == 0 ? '' : 'data-value="0"' } name="phases[${phase_index}][rules][${rule_index}][price]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="${sampleData ? data.price : ''}" /></td>
          <td class="table-td" style="display: ${ phase_index == 0 ? '' : 'none' }" ><input type="${ phase_index == 0 ? 'text' : 'hidden' }" ${ phase_index == 0 ? '' : 'data-value="0"' } name="phases[${phase_index}][rules][${rule_index}][discount]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" value="${sampleData ? data.discount : ''}" /></td>
          
          ${ phase_index == 0 ? delete_rule_html : '' }
      </tr>`;

      // append the rows
      if(!isData){
        $('.rules-table-container').find('.rulesTable tbody').append(newRow);
      }else{
        data['phase_data_element'].find('tbody').append(newRow)
      }

    }
    $('#newRule').on('click', function(){
      new_rule_row()
    })

    // Delete row logic
    $(document).on('click', '.rulesTable .deleteRule', function (e) {
      e.preventDefault();

      if($(this).hasClass('disabled')){
        return false;
      }

      let tr = $(this).closest('tr')
      tr.remove();
    });

    // Initialize first rule row when modal opens
    $('#controlRoomModal').on('shown.bs.modal', function () {
        // if the phase one control room has been opened, and it has no rows then add a row automatically.
        if ($('.rules-table-container').find('.rulesTable tbody tr').length == 0 && $('.rules-table-container').find('.rulesTable').attr('data-phase') == '1') {
          
          new_rule_row()
        }
    });

    // Validate that all fields are filled and accept only numbers
    function validateInputs() {
        let isValid = true;
        $('.rules-table-container').find('.validate-number').each(function () {
            const value = $(this).val();
            if (!value || isNaN(value)) {
                $(this).addClass('border-red-500');
                if ($(this).next('.error-message').length === 0) {
                    $(this).after('<span class="error-message text-red-500">Please enter a valid number.</span>');
                }
                isValid = false;
            } else {
                $(this).removeClass('border-red-500');
                $(this).next('.error-message').remove();
            }
        });
        return isValid;
    }

    

    // Hide modal and prepare rules for submission if validation passes
    $('.update-rules').click(function (e) {
        e.preventDefault();

        if (validateInputs()) {

          let updated_rules_phase_index = $('.rules-table-container').find('.rulesTable').attr('data-phase') 
          let updated_rules_data = $('.rules-table-container').find('.rulesTable').clone();

          // add the values to phases-data
          updated_rules_data.find('input').each(function() {
            if ($(this).attr('type') === 'checkbox'){
                if($(this).is(':checked')){
                  $(this).attr('data-value', '1'); 
                } else {
                  $(this).attr('data-value', '0');  
                }
              }else{

                $(this).attr('data-value', $(this).val());
              }

          });

          $('#phases-data').find('.rulesTable[data-phase="' + updated_rules_phase_index + '"]').html(updated_rules_data.html())

          // if the phase 1 is being updated
          if(updated_rules_phase_index == 1){
            // get the number of rules of phase 1
            let phase_1_rules_count = updated_rules_data.find('tbody tr').length

            // check the rule count of next phases and if it is less than append the new rules, i = 1, as skipping the phase 1 table
            for(let i=1; i < $('#phases-data').find('.rulesTable').length; i++){
              let next_phase = $('#phases-data').find('.rulesTable').eq(i)
              let next_phase_rows_count = next_phase.find('tbody tr').length

              // add new rules, if phase 1 has more rules than the next ones
              if(next_phase_rows_count < phase_1_rules_count ){

                for(let j=(next_phase_rows_count); j < (phase_1_rules_count ); j++){
                  let new_rule_data = {
                    'phase_index': next_phase.attr('data-phase') - 1,
                    'rule_index': j,
                    'phase_data_element': next_phase
                  }
                  new_rule_row(new_rule_data, true)
                }
                
              }

              // if other phases have more rules than phase 1
              if(next_phase_rows_count > phase_1_rules_count ){
                let delete_next_phase_rows_count = next_phase_rows_count - phase_1_rules_count

                for(let j=0; j < delete_next_phase_rows_count; j++){
                  next_phase.find('tbody tr:last').remove()
                }

              }

            }

          }

          // update all the allotted_fund fields in the next phases (if any)
          let phase_1_data = $('#phases-data').find('table').eq(0)
          for(let i=1; i < no_of_phases; i++ ) {
            let phase = $('#phases-data').find('table').eq(i)
            
            for(let j=0; j < phase.find('tr').length; j++){
              let allotted_funds_field = phase.find('tbody tr').eq(j).find('.allotted-funds-field')
              let data_value = phase_1_data.find('.allotted-funds-field[name="phases[0][rules][' + j + '][allotted_funds]"]').attr('data-value')
              
              allotted_funds_field.attr('data-value', data_value)
            }

          }

          $('#controlRoomModal').modal('hide');
        }
    });

    // Validation for phases/rules
    function checkForAtLeastOneRule() {
        return $('#phases-data').find('.rulesTable').eq(0).find('tbody tr').length > 0;
    }
    function checkIfAllFieldsAreFilled() {
      for(let i=0; i < $('#phases-data').find('input').length; i++){
        let input = $('#phases-data').find('input').eq(i)

        if(input.attr('data-value') == undefined || input.attr('data-value') == ''){
          if(input.hasClass('unique_id')){
            continue
          }
          return false
          break;
        }

      }
      return true
    }
    function checkIfFundedPhaseExists() {
      let funded_phase_exists = false

      for(let i=0; i < $('.phase-type').length; i++){
        let type = $('.phase-type').eq(i)

        if(type.val() == 'funded_phase'){
          funded_phase_exists = true
          break
        }
      }

      return funded_phase_exists

    }

    //Validate main form on submission
    $('#accountTypeForm').on('submit', function (e) {
        // e.preventDefault();
        // --- Validations ---
        // there should be atleast one rule

        if (!checkForAtLeastOneRule()) {
            showNotification('At least one rule must be set in Control Room.', 'error');

            e.preventDefault();
            return false
        }

        // every field of every rule in every phase must be filled
        if (!checkIfAllFieldsAreFilled()) {
            
            showNotification('All fields in the rules section are required!', 'error');

            e.preventDefault();
            return false
        } 

        // funded phase is required
        if(!checkIfFundedPhaseExists()){
          showNotification('Funded Phase is required!', 'error');

            e.preventDefault();
            return false
        }

        
        // prepare the phases data
        $('#phases-data input').each(function() {
            // Check if the input has a data-value attribute
            if ($(this).attr('data-value') !== undefined) {

              if ($(this).attr('type') === 'checkbox'){
                if($(this).attr('data-value') == 1){
                  $(this).prop('checked', true);  
                } else {
                  $(this).prop('checked', false);  
                }
              }else{
                $(this).val($(this).attr('data-value'));
              }
            }

        });

        // Rearrange phase steps
        for(let i=0; i < $('.account-type-phases').length; i++) {
          let phase = $('.account-type-phases').eq(i)
          phase.find('.phase-step').val(i+1)
        }
      
    });


    
  </script>
@endpush