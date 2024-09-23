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

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers', ['type' => 'internal']) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->routeIs('admin.settings.transfers') && request()->query('type') === 'internal' ? 'active' : '' }}">
                    {{ __('Internal Transfers') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers', ['type' => 'external']) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->routeIs('admin.settings.transfers') && request()->query('type') === 'external' ? 'active' : '' }}">
                    {{ __('External Transfers') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers', ['type' => 'transfer_misc']) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->routeIs('admin.settings.transfers') && request()->query('type') === 'transfer_misc' ? 'active' : '' }}">
                    {{ __('Misc') }}
                </a>
            </li>
        </ul>
    </div>

    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            <div class="grid grid-cols-12 gap-5">
                @if($section == 'internal')
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Min Amount:') }}</label>
                            <input type="text" class="form-control" name="internal_min_send" value="{{ oldSetting('internal_min_send','fee') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Max Amount:') }}</label>
                            <input type="text" class="form-control" name="internal_max_send" value="{{ oldSetting('internal_max_send','fee') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Transfer Charge') }}</label>
                            <div class="relative">
                                <input type="text" class="form-control" value="{{ oldSetting('internal_send_charge','global') }}" name="internal_send_charge">
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
                            <label for="" class="form-label">{{ __('Transfers per Day') }}</label>
                            <input type="text" class="form-control" name="internal_send_daily_limit" value="{{ oldSetting('internal_send_daily_limit','fee') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="is_internal_transfer">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_internal_transfer" value="1" class="sr-only peer" @if(oldSetting('is_internal_transfer', 'fee')) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0">
                                    {{ __('Enable Transfer') }}
                                </label>
                            </div>
                        </div>
                    </div>

                @elseif($section == 'external')

                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Min Amount:') }}</label>
                            <input type="text" class="form-control" name="external_min_send" value="{{ oldSetting('external_min_send','fee') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Max Amount:') }}</label>
                            <input type="text" class="form-control" name="external_max_send" value="{{ oldSetting('external_max_send','fee') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Transfer Charge') }}</label>
                            <div class="relative">
                                <input type="text" class="form-control" value="{{ oldSetting('external_send_charge','global') }}" name="external_send_charge">
                                <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full py-0.5">
                                    <select name="external_send_charge_type" class="w-full h-full outline-none" id="">
                                        @foreach(['fixed' => setting('currency_symbol','global') , 'percentage' => '%'] as $key => $value)
                                            <option @if( oldSetting('send_charge_type','global') == $key) selected @endif value="{{ $key }}">
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
                            <label for="" class="form-label">{{ __('Transfers per Day') }}</label>
                            <input type="text" class="form-control" name="external_send_daily_limit" value="{{ oldSetting('external_send_daily_limit','fee') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="is_external_transfer">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_external_transfer" value="1" class="sr-only peer" @if(oldSetting('is_external_transfer', 'fee')) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0">
                                    {{ __('Enable Transfer') }}
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
                                        <input type="checkbox" name="is_external_transfer_auto_approve" value="1" class="sr-only peer" @if(oldSetting('is_external_transfer_auto_approve', 'fee')) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0">
                                    {{ __('Automatic Approve') }}
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
                                        <input type="checkbox" name="is_external_transfer_purpose" value="1" class="sr-only peer" @if(oldSetting('is_external_transfer_purpose', 'fee')) checked @endif>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0">
                                    {{ __('Transfer Purpose') }}
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
                            <label for="" class="form-label">{{ __('User Referral Bonus:') }}</label>
                            <input type="text" class="form-control" name="referral_bonus" value="{{ oldSetting('referral_bonus','fee') }}">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('User Signup Bonus:') }}</label>
                            <input type="text" class="form-control" name="signup_bonus" value="{{ oldSetting('signup_bonus','fee') }}">
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

            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection

