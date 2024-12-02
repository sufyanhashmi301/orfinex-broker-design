@extends('backend.layouts.app')
@section('title')
    {{ __('Add Affiliate Rule') }}
@endsection
@section('content')
    <form action="{{ route('admin.affiliate-rules.store') }}" method="post" enctype="multipart/form-data">
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
                            <input type="text" name="name" class="form-control" placeholder="">
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                {{ __('Refer Count Mode') }}
                            </label>
                            <select name="count_mode" class="select2 form-control w-full">
                                <option value="active_account" selected>By Active Accounts</option>
                                <option value="customer">By Customers</option>
                            </select>
                        </div>

                        <div class="input-area relative">
                            <label class="form-label">
                                Balance Retention Period (days)
                            </label>
                            <input type="number" value="0" name="balance_retention_period" class="form-control"
                                placeholder="">
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                Has multiple levels
                            </label>
                            <select name="has_levels" class="select2 form-control w-full has-multiple-levels">
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                Apply on Account Types
                            </label>
                            <select name="for_account_type_ids[]" class="select2 form-control w-full" multiple>
                                <option value="all" selected>All</option>
                                @foreach ($account_types as $account_type)
                                    <option value="{{ $account_type->id }}">{{ $account_type->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                Is Active
                            </label>
                            <select name="is_active" class="select2 form-control w-full">
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                Description
                            </label>
                            <textarea name="description" rows="1" class="form-control"></textarea>
                        </div>


                    </div>
                </div>
            </div>

            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                Affiliate Rule Configuration
            </h4>
            <div class="card">
                <div class="card-body p-6">
                    <button type="button" class="btn btn-primary add-config" style="float: right">Add Configuration</button>
                    <br style="clear: both">
                    <div class="grid mt-4 gap-5 items-center">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rulesTable"
                            data-phase="1">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th" style="text-align: center"><b>Count Start</b></th>
                                    <th scope="col" class="table-th" style="text-align: center"><b>Count End</b></th>
                                    <th scope="col" class="table-th" style="text-align: center"><b>Commission %</b></th>
                                    <th scope="col" class="table-th" style="text-align: center"><b>Delete</b></th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 " id="affiliate-rule-config-container">

                                <tr class="affiliate-rule-config">
                                    <td class="table-td"><input type="text" value="1" readonly name="affiliate_configs[0][count_start]" data-index="0" class="form-control" ></td>
                                    <td class="table-td"><input type="text" value="9999" readonly name="affiliate_configs[0][count_end]" data-index="0" class="form-control"></td>
                                    <td class="table-td"><input type="text" name="affiliate_configs[0][commission_percentage]" data-index="0" class="form-control"></td>
                                    
                                </tr>
                                
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                Affiliate Rule Levels Management
            </h4>
            <div class="card">
                <div class="card-body p-6">
                    <button type="button" class="btn btn-primary add-level " style="float: right">Add Level</button>
                    <br style="clear: both">
                    <div class="grid mt-4 gap-5 items-center">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rulesTable"
                            data-phase="1">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th" style="text-align: center"><b>Level</b></th>
                                    <th scope="col" class="table-th" style="text-align: center"><b>Commission Percentage</b></th>
                                    <th scope="col" class="table-th" style="text-align: center"><b>Delete</b></th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 " id="levels-management-container">

                                <tr class="affiliate-level">
                                    <td class="table-td"><input type="text" value="1" readonly name="affiliate_levels[0][level]" data-index="0" class="form-control" ></td>
                                    <td class="table-td"><input type="text" value="100" readonly ="" name="affiliate_levels[0][commission_percentage]" data-index="0" class="form-control"></td>

                                </tr>
                                
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            
        </div>
        <div class="mt-10">
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                {{ __('Add Affiliate Rule') }}
            </button>
        </div>
    </form>
@endsection

@section('script')
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
      new_row.find('.table-td input').eq(0).prop('readonly', false)
      new_row.find('.delete-td').remove()
      new_row.append('<td class="table-td delete-td"> <center><a href="javascript:void(0)" class="action-btn" ><iconify-icon icon="lucide:trash"></iconify-icon></a></center> </td>')

      // changing name attrs of new row
      new_row.find('.table-td input').eq(0).attr('name', 'affiliate_configs[' + next_index + '][count_start]')
      new_row.find('.table-td input').eq(1).attr('name', 'affiliate_configs[' + next_index + '][count_end]')
      new_row.find('.table-td input').eq(2).attr('name', 'affiliate_configs[' + next_index + '][commission_percentage]')
      new_row.find('.table-td input').attr('data-index', next_index )

      // append
      $('#affiliate-rule-config-container').append(new_row)
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
        new_row.append('<td class="table-td delete-td"> <center><a href="javascript:void(0)" class="action-btn" ><iconify-icon icon="lucide:trash"></iconify-icon></a></center> </td>')

        // changing name attrs of new row
        new_row.find('.table-td input').eq(0).attr('name', 'affiliate_levels[' + next_index + '][level]')
        new_row.find('.table-td input').eq(1).attr('name', 'affiliate_levels[' + next_index + '][commission_percentage]')
        new_row.find('.table-td input').attr('data-index', next_index )

        // last row modificaitons
        $('.affiliate-level:last').find('.delete-td').remove()

        // append
        $('#levels-management-container').append(new_row)
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