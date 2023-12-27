<div class="col-span-12">
    <div class="card">
        <header class="card-header noborder">
            <h4 class="card-title">{{ __('My Live Accounts') }}</h4>
            <div>
                <a href="{{route('user.schema')}}" class="btn inline-flex justify-center btn-dark btn-sm">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="bi:plus"></iconify-icon>
                        <span>{{ __('Open New Account') }}</span>
                    </span>
                </a>
            </div>
        </header>
        <div class="card-body p-6 pt-0">
            <!-- BEGIN: Company Table -->
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
                                @foreach($realForexAccounts as $account)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{$account->login}}</strong>
                                    </td>
                                    <td class="table-td">
                                        {{$account->trading_platform}} {{ucfirst($account->account_type)}}
                                    </td>
                                    <td class="table-td">
                                        {{$account->equity}} $
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="{{ route('user.kyc') }}" class="toolTip onTop action-btn" data-tippy-content="Deposit" data-tippy-theme="dark">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
