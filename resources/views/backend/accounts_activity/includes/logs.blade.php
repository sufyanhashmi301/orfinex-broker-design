@if( count($account_activities) != 0 )
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                  <th scope="col" class="table-th">{{ __('Account Unique ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('Login') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Allotted Funds') }}</th> --}}
                                    <th scope="col" class="table-th">{{ __('Phase Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Phase Step') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Phase Started At') }}</th> --}}
                                    <th scope="col" class="table-th">{{ __('Phase Action') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Investment Status') }}</th> --}}

                                    <th scope="col" class="table-th">{{ __('Detail') }}</th>
                                    <th scope="col" class="table-th">{{ __('Activity Timestamp') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($account_activities as $activity)
                                    @php

                                        $accountTypeData = $activity->accountTypeInvestment->getAccountTypeSnapshotData();
                                        $phaseData = $activity->accountTypeInvestment->getPhaseSnapshotData();

                                        $ruleData = $activity->accountTypeInvestment->getRuleSnapshotData();


                                    @endphp
                                    <tr>
                                        <td class="table-td"><b> <a style="text-decoration: underline" href="?unique_id={{$activity->accountTypeInvestment->unique_id}}">{{ $activity->accountTypeInvestment->unique_id }}</a> </b></td>
                                        <td class="table-td"> {{ $activity->accountTypeInvestment->user->first_name . ' ' . $activity->accountTypeInvestment->user->last_name  }} <span style="text-transform: lowercase">({{ $activity->accountTypeInvestment->user->email }})</span></td>
                                        <td class="table-td">{{ $accountTypeData['title'] ?? '' }}</td>
                                        <td class="table-td">{{ $activity->accountTypeInvestment->login ?? 'N/A'}}</td>
                                        {{-- <td class="table-td">{{ $ruleData['allotted_funds'] ?? '' }}</td> --}}
                                        <td class="table-td"><span class="badge" style="color: #fff; background: #333">{{ str_replace('_', ' ', $phaseData['type']) }}</span></td>
                                        <td class="table-td"><span class="badge" style="color: #fff; background: #333">Phase {{ $phaseData['phase_step'] }}</span></td>
                                        {{-- <td class="table-td">{{ $activity->accountTypeInvestment->phase_started_at ?? 'N/A'}}</td> --}}

                                        @php
                                          $phase_approval = $activity->accountTypeInvestment->accountActivities->where('account_type_phase_id', $phaseData['id'])->first();
                                          $phase_action = str_replace('_', ' ', $activity->status);
                                        @endphp

                                        <td class="table-td"><span class="badge" style="color: #fff; background: #333">{{ $phase_action }}</span></td>
                                        {{-- <td class="table-td"><span class="badge" style="color: #fff; background: #333">{{ $activity->accountTypeInvestment->status }}</span></td> --}}

                                        <td class="table-td" style="width: 300px">
                                            @if ($activity->status == \App\Enums\InvestmentPhaseApproval::ADMIN_APPROVE && $activity->action == 0)
                                                <div class="btn-group">
                                                  <a href="{{ route('admin.account-phase.approval-request', ['operation' => 'approve', 'investment_id' => $activity->accountTypeInvestment->id]) }}" class="btn btn-sm btn-success mr-1">Approve</a>
                                                  <a href="{{ route('admin.account-phase.approval-request', ['operation' => 'reject', 'investment_id' => $activity->accountTypeInvestment->id]) }}" class="btn btn-sm btn-danger">Reject</a>
                                                </div>

                                            @else
                                                {{-- {{ route('user.investment.trading-stats', ['investment_id' => $activity->accountTypeInvestment->id ]) }} --}}
                                                <a href="#" class="inline-flex justify-center">
                                                  <span class="flex items-center">
                                                      <span>{{ __('No Action Needed') }}</span>
                                                      {{-- <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon> --}}
                                                  </span>
                                              </a>
                                            @endif

                                        </td>

                                        <td class="table-td">{{ $activity->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $account_activities->firstItem();
                                    $to = $account_activities->lastItem();
                                    $total = $account_activities->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $account_activities->links() }}
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
