@if( count($investments) != 0 )
    <div class="card">
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
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
                                @foreach($investments as $investment)
                                    @php
                                        $accountTypeData = $investment->getAccountTypeSnapshotData();
                                        $phaseData = $investment->getPhaseSnapshotData();
                                        $ruleData = $investment->getRuleSnapshotData();

                                        $stats = $investment->accountTypeInvestmentStat;
                                        $hourly_stats = $investment->accountTypeInvestmentHourlyStatsRecord;
                                    @endphp

                                    {{-- Contracts Function --}}
                                    @php
                                        // Phase started
                                        if($phaseData['type'] == \App\Enums\AccountTypePhase::FUNDED && isset($investment->contract['status']) && $investment->contract['status'] != \App\Enums\ContractStatusEnums::SIGNED) {
                                            $phase_started_at = 'N/A';
                                        } else {
                                            $phase_started_at = $investment->phase_started_at ?? 'N/A';
                                        }

                                        $contract_expired = false;
                                        $contract_pending = false;
                                        if(isset($investment->contract['status'])) {
                                            if($investment->contract['status'] == \App\Enums\ContractStatusEnums::PENDING) {
                                                $contract_pending = true;
                                            } elseif ($investment->contract['status'] == \App\Enums\ContractStatusEnums::EXPIRED) {
                                                $contract_expired = true;
                                            }
                                        }
                                        
                                    @endphp
                                    <tr>
                                        <td class="table-td">{{ $accountTypeData['title'] ?? '' }} 
                                            @if ($investment->is_trial == 1 && $investment->status == \App\Enums\InvestmentStatus::EXPIRED)
                                                <span class="badge badge-danger ml-2">Trial Expired</span>
                                            @elseif ($investment->is_trial == 1)
                                                <span class="badge badge-warning ml-2">Trial Account 
                                                    @if ($investment->status == \App\Enums\InvestmentStatus::ACTIVE)
                                                        - {{ \Carbon\Carbon::parse($investment->accountTrial->trial_expiry_at)->diffInDays(\Carbon\Carbon::now()) }} days left
                                                    @endif
                                                    
                                                </span>
                                            @endif

                                            <span><b> | 
                                                @if ($investment->getPhaseSnapshotData()['phase_step'] == 1 && $transactions->where('target_id', $investment->id)->count() == 0 && $investment->is_trial == 0)
                                                    No Txn ({{ $investment->id }})
                                                @endif
                                            </b></span>

                                        </td>
                                        <td class="table-td">{{ $investment->login ?? 'N/A'}}</td>
                                        <td class="table-td">{{ number_format($ruleData['allotted_funds'] ?? 0.00, 0) }} {{ $currency }}</td>
                                        <td class="table-td"><span class="badge bg-primary" style="color: #fff">{{ $investment->is_trial == 1 ? 'Trial Phase' : str_replace('_', ' ', $phaseData['type']) }}</span></td>
                                        <td class="table-td"><span class="badge bg-primary" style="color: #fff">Phase {{ $phaseData['phase_step'] }}</span></td>
                                        <td class="table-td">{{ $phase_started_at }}</td>
                                        <td class="table-td">
                                            <span class="badge bg-primary" style="color: #fff">
                                                
                                                {{ $investment->is_trial == 1 ? 'Trial ' : '' }} {{ $contract_pending == true ? 'Pending' : $investment->status  }}
                                                
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            @if (
                                                ($investment->status == \App\Enums\InvestmentStatus::ACTIVE || 
                                                 $investment->status == \App\Enums\InvestmentStatus::PASSED ||
                                                 $investment->status == \App\Enums\InvestmentStatus::VIOLATED) &&
                                                 $contract_expired == false
                                                )
                                                {{-- check if the stats exists --}}
                                                @if($contract_pending == true)
                                                    <a href="{{ route('user.contract.show', ['id' => $investment->contract['id']]) }}" class="inline-flex justify-center">
                                                        <span class="flex items-center">
                                                            <span>{{ __('Submit Contract')}}</span>
                                                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                                                        </span>
                                                    </a>
                                                @elseif (isset($stats) && count($hourly_stats) != 0 )
                                                    <a href="{{ route('user.investment.trading-stats', ['account_id' => $investment->id ]) }}" class="inline-flex justify-center">
                                                        <span class="flex items-center">
                                                            <span>{{ __('Trading Stats')}}</span>
                                                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                                                        </span>
                                                    </a>
                                                @else
                                                    <span class="flex items-center">
                                                        <span>{{ __('Account Stats Loading') }}</span>
                                                    </span>
                                                @endif
                                               
                                            @else

                                                @if ($investment->status == \App\Enums\InvestmentStatus::PENDING && $investment->is_trial == 1)
                                                    Available Soon
                                                @else
                                                    <span class="flex items-center">
                                                        <span>{{ __('-') }}</span>
                                                    </span>
                                                @endif
                                                
                                            @endif
                                            
                                        </td>
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
                {{ __("You don't have any Active account.") }}
            </p>
        </div>
    </div>
@endif
