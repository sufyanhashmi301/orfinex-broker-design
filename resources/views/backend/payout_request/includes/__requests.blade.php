@if( count($payout_requests) != 0 )
    <div class="card">
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Wallet Unique ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total Profit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Customer\'s Total Profit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Detail') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Request Timestamp') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>

                                </tr>
                            </thead>
                            <tbody> 

                                @foreach($payout_requests as $request)
                                    @php
                                      $total_profit = 0;
                                      for($i = 0; $i < count($request->detail); $i++){
                                        $total_profit += $request->detail[$i]['total_profit'];
                                      }
                                    @endphp
                                    <tr>
                                        <td class="table-td">{{ $request->wallet_unique_id }}</td>
                                        <td class="table-td">{{ $request->user->first_name . ' ' . $request->user->last_name }}</td>
                                        <td class="table-td">{{ number_format($total_profit, 2) . ' ' . $currency }}</td>
                                        <td class="table-td">{{ number_format($request->user_profit_share_amount, 2) . ' ' . $currency }}</td>
                                        <td class="table-td"><a href="javascript:void(0)" class="badge" style="color: #fff; background: #333">Payout Details</a></td>
                                        
                                        <td class="table-td"> <span class="badge" style="color: #fff; background: #333">{{ $request->status }}</span> </td>
                                        <td class="table-td">{{ $request->created_at }}</td>

        
                                        <td class="table-td" style="width: 300px">
                                            @if ($request->status == \App\Enums\PayoutRequestStatus::PENDING)
                                                <div class="btn-group">
                                                  <a href="{{ route('admin.payout_request.action', ["payout_request_id" => $request->id, "operation" => 'approve']) }}" class="btn btn-sm btn-success mr-1">Approve</a>
                                                  <a href="{{ route('admin.payout_request.action', ["payout_request_id" => $request->id, "operation" => 'decline']) }}" class="btn btn-sm btn-danger">Decline</a>
                                                </div>
                                            @else
                                                <a href="#" style="cursor: default" class="inline-flex justify-center">
                                                  <span class="flex items-center">
                                                      <span>{{ __('No Action Needed') }}</span>
                                                  </span>
                                              </a>
                                            @endif
                                            
                                        </td>

                                        <td class="table-td"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
