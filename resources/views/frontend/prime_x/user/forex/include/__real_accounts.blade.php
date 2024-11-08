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
                                    <th scope="col" class="table-th">{{ __('Fund') }}</th>
                                    <th scope="col" class="table-th">{{ __('Activation Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('Daily Drawdown') }}</th>
                                    <th scope="col" class="table-th">{{ __('Max Drawdown') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit Target') }}</th>
                                    <th scope="col" class="table-th">{{ __('Detail') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($investments as $investment)
                                @php
                                    $investment_snapshot = $investment->accountTypeInvestmentSnapshot;
                                @endphp
                                <tr>
                                    <td class="table-td">{{ $investment_snapshot->account_types_data['title'] }} </td>
                                    <td class="table-td">{{ $investment->login }}</td>
                                    <td class="table-td">{{ $investment_snapshot->account_types_phases_rules_data['allotted_funds'] }}</td>
                                    <td class="table-td">{{ $investment->phase_started_at }}</td>
                                    <td class="table-td">{{ $investment_snapshot->account_types_phases_rules_data['daily_drawdown_limit'] }}</td>
                                    <td class="table-td">{{ $investment_snapshot->account_types_phases_rules_data['max_drawdown_limit'] }}</td>
                                    <td class="table-td">{{ $investment_snapshot->account_types_phases_rules_data['profit_target'] }}</td>
                                    <td class="table-td">
                                        <a href="{{route('user.investment.trading-stats', ['investment_id' => $investment->id ])}}" class="inline-flex justify-center">
                                        <span class="flex items-center">
                                            <span>{{ __('Trading Stats') }}</span>
                                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                                        </span>
                                        </a>
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
