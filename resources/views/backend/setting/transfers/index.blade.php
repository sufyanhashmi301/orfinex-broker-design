@extends('backend.setting.payment.index')
@section('title')
    {{ __("Transfer's Settings") }}
@endsection
@section('payment-content')
    <?php

        $type = request()->query('type');

        $section = $type;
//        dd($section);
        $fields = config("setting.$section");
//        dd($fields);

    ?>

    @include('backend.setting.transfers.include.__inner_menu')

    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            <div class="grid grid-cols-12 gap-5">
                @if($section == 'transfer_internal')
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The minimum value a user can transfer internally">
                                    {{ __('Min Amount') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="internal_min_send" value="{{ oldSetting('internal_min_send','transfer_internal') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The maximum value allowed for a single internal transfer">
                                    {{ __('Max Amount') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="number" class="form-control" name="internal_max_send" value="{{ oldSetting('internal_max_send','transfer_internal') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Fee applied on each transfer (in % or fixed amount)">
                                    {{ __('Transfer Charge') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="relative">
                                <input type="number" class="form-control" value="{{ oldSetting('internal_send_charge','global') }}" name="internal_send_charge">
                                <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full py-0.5">
                                    <select name="internal_send_charge_type" class="w-full h-full outline-none" id="">
                                        @foreach(['fixed' => setting('currency_symbol','global') , 'percentage' => '%'] as $key => $value)
                                            <option @if( oldSetting('internal_send_charge_type','global') == $key) selected @endif value="{{ $key }}">
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Maximum number of internal transfers allowed per user per day">
                                    {{ __('Internal transactions daily limit') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="number" class="form-control" name="internal_send_daily_limit" value="{{ oldSetting('internal_send_daily_limit','transfer_internal') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="is_internal_transfer">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_internal_transfer" value="1" class="sr-only peer" @if(oldSetting('is_internal_transfer', 'transfer_internal')) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Toggle to activate or deactivate internal transfer functionality">
                                        {{ __('Enable Transfer') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                @elseif($section == 'transfer_external')

                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Minimum value allowed for an external transfer">
                                    {{ __('Min Amount') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="number" class="form-control" name="external_min_send" value="{{ oldSetting('external_min_send','transfer_external') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Maximum amount a user can send via external transfer">
                                    {{ __('Max Amount') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="number" class="form-control" name="external_max_send" value="{{ oldSetting('external_max_send','transfer_external') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Fee for each transfer (percentage or fixed)">
                                    {{ __('Transfer Charge') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="relative">
                                <input type="number" class="form-control" value="{{ oldSetting('external_send_charge','transfer_external') }}" name="external_send_charge">
                                <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full py-0.5">
                                    <select name="external_send_charge_type" class="w-full h-full outline-none" id="">
                                        @foreach(['fixed' => setting('currency_symbol','global') , 'percentage' => '%'] as $key => $value)
                                            <option @if( oldSetting('external_send_charge_type','global') == $key) selected @endif value="{{ $key }}">
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Max number of external transfers allowed per user daily">
                                    {{ __('External transactions daily limit') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="number" class="form-control" name="external_send_daily_limit" value="{{ oldSetting('external_send_daily_limit','transfer_external') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="is_external_transfer">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_external_transfer" value="1" class="sr-only peer" @if(oldSetting('is_external_transfer', 'transfer_external')) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Toggle to activate or deactivate the external transfer module">
                                        {{ __('Enable Transfer') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="is_external_transfer_auto_approve">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_external_transfer_auto_approve" value="1" class="sr-only peer" @if(oldSetting('is_external_transfer_auto_approve', 'transfer_external')) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="If enabled, transfer requests are approved automatically without manual review">
                                        {{ __('Automatic Approve') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="is_external_transfer_purpose">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_external_transfer_purpose" value="1" class="sr-only peer" @if(oldSetting('is_external_transfer_purpose', 'transfer_external')) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="When enabled, users must provide a reason for the transfer">
                                        {{ __('Transfer Purpose') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Wallet Exchange Charge') }}</label>
                            <div class="relative">
                                <input type="text" class="form-control" value="{{ oldSetting('wallet_exchange_charge','global') }}" name="wallet_exchange_charge">
                                <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full py-0.5">
                                    <select name="wallet_exchange_charge_type" class="w-full h-full outline-none" id="">
                                        @foreach(['fixed' => setting('currency_symbol','global') , 'percentage' => '%'] as $key => $value)
                                            <option @selected( oldSetting('wallet_exchange_charge_type','global') == $key) value="{{ $key }}">
                                            {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Wallet Exchange Daily Limit:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="wallet_exchange_day_limit" value="{{ oldSetting('wallet_exchange_day_limit','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ __('Times') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Send Money Daily Limit:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="send_money_day_limit" value="{{ oldSetting('send_money_day_limit','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ __('Times') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Withdraw Daily Limit:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="withdraw_day_limit" value="{{ oldSetting('withdraw_day_limit','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ __('Times') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Investment Cancellation Daily Limit:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="investment_cancellation_daily_limit" value="{{ oldSetting('investment_cancellation_daily_limit','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ __('Times') }}</span>
                            </div>
                        </div>
                    </div>

                @endif
            </div>
            @can('transfers-action')
                @include('backend.setting.site_setting.include.form.__close_action')
            @endcan

        </div>
    </div>
@endsection


