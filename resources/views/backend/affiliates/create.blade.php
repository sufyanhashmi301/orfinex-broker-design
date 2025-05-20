@extends('backend.layouts.app')
@section('title')
    {{ __('Affiliates Management') }}
@endsection
@section('content')
    <form action="{{ route('admin.affiliate-rules.store') }}" method="post" id="affiliate-rule-form" enctype="multipart/form-data">
        @csrf
        <div class="space-y-5">
            <h4
                class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Affiliate Rule') }}
            </h4>
            <div class="card">
                <div class="card-body p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Name') }}
                            </label>
                            <input type="text" name="name" value="{{ $affiliate_rule->name ?? '' }}" required class="form-control" placeholder="">
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                {{ __('Refer Count Mode') }}
                            </label>
                            <select name="count_mode" required class="select2 form-control w-full">
                                <option value="active_account"  {{ $affiliate_rule->count_mode == 'active_account' ? 'selected' : '' }}>By Active Accounts</option>
                                <option value="customer" {{ $affiliate_rule->count_mode == 'customer' ? 'selected' : '' }}>By Customers</option>
                            </select>
                        </div>

                        <div class="input-area relative">
                            <label class="form-label">
                                Balance Retention Period (days)
                            </label>
                            <input type="number" required value="{{ $affiliate_rule->balance_retention_period ?? '' }}" name="balance_retention_period" class="form-control"
                                placeholder="">
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                Has multiple levels
                            </label>
                            <select name="has_levels" required class="select2 form-control w-full has-multiple-levels">
                                <option value="1" {{ $affiliate_rule->has_levels == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $affiliate_rule->has_levels == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                Apply on Account Types
                            </label>
                            <select required name="for_account_type_ids[]" class="select2 form-control w-full" multiple>
                                <option value="all" {{ in_array('all', json_decode($affiliate_rule->for_account_type_ids, true)) ? 'selected' : '' }}>All</option>
                                @foreach ($account_types as $account_type)
                                    <option value="{{ $account_type->id }}"
                                        {{ in_array($account_type->id, json_decode($affiliate_rule->for_account_type_ids, true)) ? 'selected' : '' }}>
                                        {{ $account_type->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-area relative">
                            <label class="form-label">
                               Min. Payout Limit ({{ $currency }})
                            </label>
                            <input type="number" required value="{{ $affiliate_rule->min_payout_limit ?? 0 }}" name="min_payout_limit" class="form-control"
                                placeholder="">
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                Is Active
                            </label>
                            <select required name="is_active" class="select2 form-control w-full">
                                <option value="1" {{ $affiliate_rule->is_active == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $affiliate_rule->is_active == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                Description
                            </label>
                            <textarea name="description" rows="1" class="form-control">{{ $affiliate_rule->description }}</textarea>
                        </div>


                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex-wrap noborder">
                    <h4 class="card-title mb-1 sm:mb-0">
                        Affiliate Rule Configuration
                    </h4>
                    <button type="button" class="btn btn-primary add-config">Add Configuration</button>
                </div>
                <div class="card-body p-6 pt-0">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rulesTable" data-phase="1">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">Count Start</th>
                                            <th scope="col" class="table-th">Count End</th>
                                            <th scope="col" class="table-th">Commission %</th>
                                            <th scope="col" class="table-th">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 " id="affiliate-rule-config-container">

                                        @if (isset($affiliate_rule))
                                            @foreach ($affiliate_rule->affiliateRuleConfiguration()->orderBy('id', 'ASC')->get() as $index => $config)
                                                <tr class="affiliate-rule-config">
                                                    <td class="table-td"><input type="number" required value="{{ $config->count_start }}" {{ $loop->first ? 'readonly' : '' }} name="affiliate_configs[{{ $index }}][count_start]" data-index="{{ $index }}" class="form-control" ></td>
                                                    <td class="table-td"><input type="number" required value="{{ $config->count_end }}" {{ $loop->last ? 'readonly' : '' }} name="affiliate_configs[{{ $index }}][count_end]" data-index="{{ $index }}" class="form-control"></td>
                                                    <td class="table-td"><input type="number" required value="{{ $config->commission_percentage }}" name="affiliate_configs[{{ $index }}][commission_percentage]" data-index="{{ $index }}" class="form-control"></td>

                                                    @if ($loop->last && count($affiliate_rule->affiliateRuleConfiguration) != 1)
                                                        <td class="table-td delete-config delete-td"> <center><a href="javascript:void(0)" class="action-btn" ><iconify-icon icon="lucide:trash"></iconify-icon></a></center> </td>
                                                    @endif
                                                </tr>
                                            @endforeach

                                        @else
                                            <tr class="affiliate-rule-config">
                                                <td class="table-td"><input type="number" required value="1" readonly name="affiliate_configs[0][count_start]" data-index="0" class="form-control" ></td>
                                                <td class="table-td"><input type="number" required value="9999" readonly name="affiliate_configs[0][count_end]" data-index="0" class="form-control"></td>
                                                <td class="table-td"><input type="number" required name="affiliate_configs[0][commission_percentage]" data-index="0" class="form-control"></td>

                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="text-danger" id="config-error" style="color: #dc3545; font-size: 14px; padding-left: 15px; display: none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex-wrap noborder">
                    <h4 class="card-title mb-1 sm:mb-0">
                        Affiliate Rule Levels Management
                    </h4>
                    <button type="button" class="btn btn-primary add-level">Add Level</button>
                </div>
                <div class="card-body p-6 pt-0">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rulesTable" data-phase="1">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">Level</th>
                                            <th scope="col" class="table-th">Commission Percentage</th>
                                            <th scope="col" class="table-th">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 " id="levels-management-container">
                                        @if (isset($affiliate_rule))
                                            @foreach ($affiliate_rule->affiliateRuleLevel()->orderBy('id', 'ASC')->get() as $index => $level)
                                                <tr class="affiliate-level">
                                                    <td class="table-td"><input type="number" required value="{{ $level->level }}" readonly name="affiliate_levels[{{ $index }}][level]" data-index="{{ $index }}" class="form-control" ></td>
                                                    <td class="table-td"><input type="number" required value="{{ $level->commission_percentage }}" {{ $loop->first ? 'readonly' : '' }} name="affiliate_levels[{{ $index }}][commission_percentage]" data-index="{{ $index }}" class="form-control"></td>

                                                    @if ($loop->last && count($affiliate_rule->affiliateRuleLevel) != 1)
                                                        <td class="table-td delete-config delete-td"> <center><a href="javascript:void(0)" class="action-btn" ><iconify-icon icon="lucide:trash"></iconify-icon></a></center> </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="affiliate-level">
                                                <td class="table-td"><input type="number" required value="1" readonly name="affiliate_levels[0][level]" data-index="0" class="form-control" ></td>
                                                <td class="table-td"><input type="number" required value="100" readonly ="" name="affiliate_levels[0][commission_percentage]" data-index="0" class="form-control"></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-10">
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                {{ __('Update Affiliate Rule') }}
            </button>
        </div>
    </form>
@endsection

@section('script')

  <script>
    $('#affiliate-rule-form').on('submit', function(){

        let error_occured = false

        // config validation
        for(let i=0; i < $('.affiliate-rule-config').length; i++) {
            let config = $('.affiliate-rule-config').eq(i)
            let count_start = config.find('td').eq(0).find('input').val()
            let count_end = config.find('td').eq(1).find('input').val()
            let commission = config.find('td').eq(2).find('input').val()

            // exclude the first
            if(i != 0){

                let prev_config = $('.affiliate-rule-config').eq(i-1)
                let prev_count_start = prev_config.find('td').eq(0).find('input').val()
                let prev_count_end = prev_config.find('td').eq(1).find('input').val()
                let prev_commission = prev_config.find('td').eq(2).find('input').val()

                // if the count_start compared to prev_count_end is any number rather than +1 of prev_count_end, give error
                if(count_start != (+prev_count_end + 1)){
                    error_occured = true
                    $('#config-error').show()
                    $('#config-error').text('Invalid configuration: The "Count Start" value must be exactly +1 greater than the previous "Count End" value.')
                    break;
                }
            }

            // check if the count_end is less than or equal to count_start
            if(+count_end <= +count_start) {

                error_occured = true
                $('#config-error').show()
                $('#config-error').text('Invalid configuration: The "Count End" value must be greater than its respective "Count Start" value.')
                break;
            }

            // console.log(count_start, count_end, commission)
        }

        if(error_occured) {
            return false
        }
    })
  </script>

  <script>
    $('.add-config').on('click', function() {
      let new_row = $('.affiliate-rule-config:last').clone()

      // index
      let last_row_index = new_row.find('input').eq(0).attr('data-index')
      let next_index = parseInt(last_row_index, 10) + 1

      // last row modifications
      $('.affiliate-rule-config:last').find('.table-td input').eq(1).prop('readonly', false)
      $('.affiliate-rule-config:last').find('.table-td input').eq(1).val('')

      // new row modifications
      new_row.find('.table-td input').eq(0).val('')
      new_row.find('.table-td input').eq(2).val('')
      new_row.find('.table-td input').eq(0).prop('readonly', false)
      new_row.find('.delete-td').remove()
      new_row.append('<td class="table-td delete-config delete-td"> <center><a href="javascript:void(0)" class="action-btn" ><iconify-icon icon="lucide:trash"></iconify-icon></a></center> </td>')

      // changing name attrs of new row
      new_row.find('.table-td input').eq(0).attr('name', 'affiliate_configs[' + next_index + '][count_start]')
      new_row.find('.table-td input').eq(1).attr('name', 'affiliate_configs[' + next_index + '][count_end]')
      new_row.find('.table-td input').eq(2).attr('name', 'affiliate_configs[' + next_index + '][commission_percentage]')
      new_row.find('.table-td input').attr('data-index', next_index )

      // last row modificaitons
      $('.affiliate-rule-config:last').find('.delete-td').remove()

      // append
      $('#affiliate-rule-config-container').append(new_row)
    })

    $(document).on('click', '.delete-config', function(){
        $(this).parents('.affiliate-rule-config').remove()

        let last_row = $('#affiliate-rule-config-container .affiliate-rule-config:last')
        last_row.find('.table-td input').eq(1).val('9999')
        last_row.find('.table-td input').eq(1).prop('readonly', true)

        if($('.affiliate-rule-config').length > 1){
            last_row.append(
                '<td class="table-td delete-config delete-td"> <center><a href="javascript:void(0)" class="action-btn" ><iconify-icon icon="lucide:trash"></iconify-icon></a></center> </td>'
            )
        }
    })

    $('.add-level').on('click', function() {

        if($('.has-multiple-levels').val() != '1') {
            alert('Enable multiple levels option to add more levels')
            return false
        }

        let levels_count = $('.affiliate-level').length
        let new_row = $('.affiliate-level:last').clone()

        // index
        let last_row_index = new_row.find('input').eq(0).attr('data-index')
        let next_index = parseInt(last_row_index, 10) + 1

        // new row modifications
        new_row.find('.table-td input').eq(0).val(+(levels_count + 1))
        new_row.find('.table-td input').eq(1).attr('placeholder', 'Commission Percentage of Level ' + levels_count)
        new_row.find('.table-td input').eq(1).prop('readonly', false)
        new_row.find('.table-td input').eq(1).val('')
        new_row.find('.delete-td').remove()
        new_row.append('<td class="table-td delete-level delete-td"> <center><a href="javascript:void(0)" class="action-btn" ><iconify-icon icon="lucide:trash"></iconify-icon></a></center> </td>')

        // changing name attrs of new row
        new_row.find('.table-td input').eq(0).attr('name', 'affiliate_levels[' + next_index + '][level]')
        new_row.find('.table-td input').eq(1).attr('name', 'affiliate_levels[' + next_index + '][commission_percentage]')
        new_row.find('.table-td input').attr('data-index', next_index )

        // last row modificaitons
        $('.affiliate-level:last').find('.delete-td').remove()

        // append
        $('#levels-management-container').append(new_row)
    })

    $(document).on('click', '.delete-level', function(){
        $(this).parents('.affiliate-level').remove()

        let last_row = $('#levels-management-container .affiliate-level:last')

        if($('.affiliate-level').length > 1){
            last_row.append(
                '<td class="table-td delete-level delete-td"> <center><a href="javascript:void(0)" class="action-btn" ><iconify-icon icon="lucide:trash"></iconify-icon></a></center> </td>'
            )
        }
    })

    $('.has-multiple-levels').on('change', function(){
        if($(this).val() != '1') {
            $('#levels-management-container').html(
                `
                <tr class="affiliate-level">
                    <td class="table-td"><input type="text" value="1" readonly name="affiliate_levels[0][level]" data-index="0" class="form-control" ></td>
                    <td class="table-td"><input type="text" value="100" readonly ="" name="affiliate_levels[0][commission_percentage]" data-index="0" class="form-control"></td>

                </tr>
                `
            )
        }
    })
  </script>
@endsection
