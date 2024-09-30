<div class="tab-pane space-y-5 fade" id="pills-performance" role="tabpanel" aria-labelledby="pills-performance-tab">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Trading Performance') }}</h4>
            <div class="max-w-xl w-full flex justify-between sm:space-x-4 space-x-2">
                <div class="flex-1 input-area relative">
                    <select class="form-control">
                        <option selected="">{{ __('Select Account') }}</option>
                        <option value="">...</option>
                    </select>
                </div>
                <div class="flex-1 input-area relative">
                    <select class="form-control">
                        <option selected="">{{ __('Select Period') }}</option>
                        <option value="1">{{ __('Today') }}</option>
                        <option value="7">{{ __('Weekly') }}</option>
                        <option value="30">{{ __('Monthly') }}</option>
                    </select>
                </div>
                <button class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                    {{ __('Fetch Positions') }}
                </button>
            </div>
        </div>
        <div class="card-body p-6 pt-3">
            <ul class="space-y-3">
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Total Trades</span>
                    <span class="text-slate-900 font-medium text-right">
                        0
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Profitable Trades</span>
                    <span class="text-slate-900 font-medium text-right">
                        0 USD
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Losing Trades</span>
                    <span class="text-slate-900 font-medium text-right">
                        0 USD
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Highest Profit on a Trade</span>
                    <span class="text-slate-900 font-medium text-right">
                        0.00 USD
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Largest Loss on a Trade</span>
                    <span class="text-slate-900 font-medium text-right">
                        0.00 USD
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Total Net Profit</span>
                    <span class="text-slate-900 font-medium text-right">
                        0.00 USD
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Win Rate</span>
                    <span class="text-slate-900 font-medium text-right">
                        0.00%
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Loss Rate</span>
                    <span class="text-slate-900 font-medium text-right">
                        0.00%
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Average Holding Time</span>
                    <span class="text-slate-900 font-medium text-right">
                        0.00 seconds
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Risk-Reward Ratio</span>
                    <span class="text-slate-900 font-medium text-right">
                        0.00
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                    <span>Capital Preservation Ratio</span>
                    <span class="text-slate-900 font-medium text-right">
                        0.00%
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
