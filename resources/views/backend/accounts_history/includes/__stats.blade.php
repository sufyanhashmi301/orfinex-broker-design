@if( count($accounts_stats) != 0 )
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                  <th scope="col" class="table-th">{{ __('Account Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    
                                    <th scope="col" class="table-th">{{ __('Login') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Current Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Prev. Day Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Prev. Day Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total PnL') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trading Days') }}</th>
                                    <th scope="col" class="table-th">{{ __('Stats Recorded At') }}</th>
                                    
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($accounts_stats as $record)
                                    @php
                                      
                                      $user_exists = true;
                                      if(!isset($record->accountTypeInvestment->user)) {
                                        $user_exists = false;
                                      }
                                    @endphp
                                    <tr>
                                      <td class="table-td">{{ $record->account_name }}</td>  
                                      <td class="table-td">
                                        <div>
                                            <span class="text-sm text-slate-900 dark:text-white block capitalize">
                                                {{ $user_exists == true ? $record->accountTypeInvestment->user->first_name . ' ' . $record->accountTypeInvestment->user->last_name : 'N/A' }}
                                            </span>
                                            <span class="text-xs text-slate-500 dark:text-slate-300">
                                                {{ $user_exists == true ?  $record->accountTypeInvestment->user->email : 'N/A' }}
                                            </span>
                                        </div>
                                      </td>  
                                      <td class="table-td" style="text-decoration: underline;">
                                        <a href="{{ route('admin.account.trading_stats.history', ['search' => $record->accountTypeInvestment->login ]) }}">
                                          <b>{{ $record->accountTypeInvestment->login }}</b>
                                        </a>  
                                      </td>  
                                      <td class="table-td">{{ number_format($record->balance, 2) }}</td>  
                                      <td class="table-td">{{ number_format($record->current_equity, 2) }}</td>  
                                      <td class="table-td">{{ number_format($record->prev_day_balance, 2) }}</td>  
                                      <td class="table-td">{{ number_format($record->prev_day_equity, 2) }}</td>  
                                      <td class="table-td">
                                        <span class="badge {{ $record->total_pnl < 0 ? 'badge-danger' : 'badge-success' }}">{{ number_format($record->total_pnl, 2) }}</span>
                                      </td>  
                                      <td class="table-td">{{ $record->trading_days }}</td>  
                                      <td class="table-td">{{ $record->created_at }}</td>  
                                      
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $accounts_stats->firstItem();
                                    $to = $accounts_stats->lastItem();
                                    $total = $accounts_stats->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $accounts_stats->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card basicTable_wrapper items-center justify-center py-10 px-10">
        <div class="flex items-center justify-center flex-col gap-3">
            <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                {{ __("Nothing to see here.") }}
            </p>
        </div>
    </div>
@endif
