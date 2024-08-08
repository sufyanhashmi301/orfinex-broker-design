<div class="overflow-x-auto -mx-6">
    <div class="inline-block min-w-full align-middle">
        <div class="overflow-hidden ">
            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                <thead class="bg-slate-200 dark:bg-slate-700">
                    <tr>
                        <th scope="col" class="table-th">{{ __('Account Number') }}</th>
                        <th scope="col" class="table-th">{{ __('Type') }}</th>
                        <th scope="col" class="table-th">{{ __('Equity') }}</th>
                        <th scope="col" class="table-th"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                    @foreach($demoForexAccounts as $account)
                    <tr>
                        <td class="table-td">
                            <strong>{{$account->login}}</strong>
                        </td>
                        <td class="table-td">
                            {{$account->trading_platform}} {{ucfirst($account->account_type)}}
                        </td>
                        <td class="table-td">
                            {{get_mt5_account_equity($account->login)}} $
                        </td>
                        <td class="table-td">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <a href="{{route('user.deposit.amount')}}" class="toolTip onTop action-btn dropdown-deposit-demo-account"
                                   data-tippy-content="Deposit"
                                   data-tippy-theme="dark"
                                   data-bs-toggle="modal"
                                   data-bs-target="#depositDemo" data-login="{{$account->login}}">
                                    <iconify-icon icon="octicon:download-16"></iconify-icon>
                                </a>
                                <a href="javascript:;" class="toolTip onTop action-btn" data-tippy-content="Trade" data-tippy-theme="dark">
                                    <iconify-icon icon="tabler:chart-candle"></iconify-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{  $demoForexAccounts->links() }}

        </div>
    </div>
</div>
