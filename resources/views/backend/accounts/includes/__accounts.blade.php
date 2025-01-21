@if (count($accounts) != 0)
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                            id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">Title</th>
                                    <th scope="col" class="table-th">User</th>
                                    <th scope="col" class="table-th">Login</th>
                                    <th scope="col" class="table-th">Allotted Funds</th>
                                    <th scope="col" class="table-th">Phase Type</th>
                                    <th scope="col" class="table-th">Phase Step</th>
                                    <th scope="col" class="table-th">Phase Started At</th>
                                    <th scope="col" class="table-th">Phase Ended At</th>
                                    <th scope="col" class="table-th">Status</th>
                                    <th scope="col" class="table-th">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($accounts as $account)
                                    @php
                                        $accountTypeData = $account->getAccountTypeSnapshotData();
                                        $phaseData = $account->getPhaseSnapshotData();
                                        $ruleData = $account->getRuleSnapshotData();

                                        $stats = $account->accountTypeInvestmentStat;
                                        $hourly_stats = $account->accountTypeInvestmentHourlyStatsRecord;
                                    @endphp

                                    @php
                                        $user_exists = true;
                                        if (!isset($account->user->first_name)) {
                                            $user_exists = false;
                                        }
                                    @endphp

                                    {{-- Contracts Function --}}
                                    @php
                                        // Phase started
                                        if (
                                            $phaseData['type'] == \App\Enums\AccountTypePhase::FUNDED &&
                                            isset($account->contract['status']) &&
                                            $account->contract['status'] != \App\Enums\ContractStatusEnums::SIGNED
                                        ) {
                                            $phase_started_at = 'N/A';
                                        } else {
                                            $phase_started_at = $account->phase_started_at ?? 'N/A';
                                        }

                                        $contract_expired = false;
                                        $contract_pending = false;
                                        if (isset($account->contract['status'])) {
                                            if (
                                                $account->contract['status'] == \App\Enums\ContractStatusEnums::PENDING
                                            ) {
                                                $contract_pending = true;
                                            } elseif (
                                                $account->contract['status'] == \App\Enums\ContractStatusEnums::EXPIRED
                                            ) {
                                                $contract_expired = true;
                                            }
                                        }

                                    @endphp
                                    <tr>
                                        <td class="table-td">{{ $accountTypeData['title'] ?? '' }}</td>
                                        <td class="table-td">
                                            {{ $user_exists == true ? $account->user->first_name . ' ' . $account->user->last_name . ' (' . $account->user->email . ')' : 'N/A' }}
                                        </td>
                                        <td class="table-td">{{ $account->login ?? 'N/A' }}</td>
                                        <td class="table-td">{{ $ruleData['allotted_funds'] ?? '' }}</td>
                                        <td class="table-td"><span class="badge bg-primary"
                                                style="color: #fff">{{ str_replace('_', ' ', $phaseData['type']) }}</span>
                                        </td>
                                        <td class="table-td"><span class="badge bg-primary" style="color: #fff">Phase
                                                {{ $phaseData['phase_step'] }}</span></td>
                                        <td class="table-td">{{ $phase_started_at }}</td>
                                        <td class="table-td">{{ $account->phase_ended_at ?? 'N/A' }}</td>
                                        <td class="table-td"><span class="badge bg-primary"
                                                style="color: #fff">{{ $contract_pending == true ? 'Pending' : $account->status }}</span>
                                        </td>
                                        <td class="table-td">
                                            @if (
                                                ($account->status == \App\Enums\InvestmentStatus::ACTIVE ||
                                                    $account->status == \App\Enums\InvestmentStatus::PASSED ||
                                                    $account->status == \App\Enums\InvestmentStatus::VIOLATED) &&
                                                    $contract_expired == false)
                                                {{-- check if the stats exists --}}
                                                @if ($contract_pending == true)
                                                    <a href="{{ route('admin.contracts.index') }}"
                                                        class="inline-flex justify-center">
                                                        <span class="flex items-center">
                                                            <span>{{ __('Pending Contracts') }}</span>
                                                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                                icon="lucide:chevron-right"></iconify-icon>
                                                        </span>
                                                    </a>
                                                @elseif (isset($stats) && count($hourly_stats) != 0)
                                                    <a href="{{ route('admin.account.trading_stats', ['account_id' => $account->id]) }}"
                                                        target="__blank" class="inline-flex justify-center">
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
                                                @if ($account->status == \App\Enums\InvestmentStatus::PENDING)
                                                    <a href="{{ route('admin.deposit.manual.pending') }}"
                                                        class="inline-flex justify-center">
                                                        <span class="flex items-center">
                                                            <span>{{ __('Payment Pending') }}</span>
                                                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                                icon="lucide:chevron-right"></iconify-icon>
                                                        </span>
                                                    </a>
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
                        <div
                            class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $accounts->firstItem();
                                    $to = $accounts->lastItem();
                                    $total = $accounts->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span
                                        class="font-medium">{{ $to }}</span> of <span
                                        class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $accounts->appends(request()->except('page'))->links() }}
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
                {{ __('Nothing to see here.') }}
            </p>
        </div>
    </div>
@endif
@push('single-script')
    <script></script>
@endpush
