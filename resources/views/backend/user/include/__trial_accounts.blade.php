
<div class="tab-pane fade" id="trial-accounts" role="tabpanel">
  <div class="card">
      <div class="card-body p-6 pt-0">
          <div class="overflow-x-auto -mx-6">
              <div class="inline-block min-w-full align-middle">
                  <div style="padding-top: 5px" class="overflow-hidden basicTable_wrapper" style="height: 723px; overflow: auto">
                      <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                          <thead>
                              <tr>
                                  <th scope="col" class="table-th">{{ __('Title') }}</th>
                                  <th scope="col" class="table-th">{{ __('Login') }}</th>
                                  <th scope="col" class="table-th">{{ __('Allotted Funds') }}</th>
                                  <th scope="col" class="table-th">{{ __('Phase Type') }}</th>
                                  <th scope="col" class="table-th">{{ __('Phase Step') }}</th>
                                  <th scope="col" class="table-th">{{ __('Phase Started At') }}</th>
                                  <th scope="col" class="table-th">{{ __('Status') }}</th>
                                  <th scope="col" class="table-th">{{ __('Detail') }}</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse ($trial_accounts as $investment)
                                  @php
                                      $accountTypeData = $investment->getAccountTypeSnapshotData();
                                      $phaseData = $investment->getPhaseSnapshotData();
                                      $ruleData = $investment->getRuleSnapshotData();

                                      $stats = $investment->accountTypeInvestmentStat;
                                      $hourly_stats = $investment->accountTypeInvestmentHourlyStatsRecord;

                                  @endphp
                                  <tr>
                                      <td class="table-td">{{ $accountTypeData['title'] ?? '' }}</td>
                                      <td class="table-td">{{ $investment->login ?? 'N/A' }}</td>
                                      <td class="table-td">{{ $ruleData['allotted_funds'] ?? '' }}</td>
                                      <td class="table-td"><span class="badge bg-primary"
                                              style="color: #fff">{{ str_replace('_', ' ', $phaseData['type']) }}</span>
                                      </td>
                                      <td class="table-td"><span class="badge bg-primary" style="color: #fff">Phase
                                              {{ $phaseData['phase_step'] }}</span></td>
                                      <td class="table-td">{{ $investment->phase_started_at ?? 'N/A' }}</td>
                                      <td class="table-td"><span class="badge bg-primary"
                                              style="color: #fff">{{ $investment->status }}</span></td>
                                      <td class="table-td">
                                          @if (
                                              $investment->status == \App\Enums\InvestmentStatus::ACTIVE ||
                                                  $investment->status == \App\Enums\InvestmentStatus::PASSED ||
                                                  $investment->status == \App\Enums\InvestmentStatus::VIOLATED)
                                              {{-- check if the stats exists --}}
                                              @if (isset($stats) && count($hourly_stats) != 0)
                                                  <a href="{{ route('user.investment.trading-stats', ['account_id' => $investment->id]) }}"
                                                      class="inline-flex justify-center">
                                                      <span class="flex items-center">
                                                          <span>{{ __('Trading Stats') }}</span>
                                                          <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                              icon="lucide:chevron-right"></iconify-icon>
                                                      </span>
                                                  </a>
                                              @else
                                                  <span class="flex items-center">
                                                      <span>{{ __('Account Stats Loading') }}</span>
                                                  </span>
                                              @endif
                                          @else
                                              <span class="flex items-center">
                                                  <span>{{ __('-') }}</span>
                                              </span>
                                          @endif

                                      </td>
                                  </tr>
                              @empty
                                  <tr>
                                      <td colspan="8"> <center><small>No Trial account!</small></center> </td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


