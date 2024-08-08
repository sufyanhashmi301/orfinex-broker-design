@extends('backend.setting.index')
@section('title')
    {{ __('Currency Settings') }}
@endsection
@section('setting-content')
    <?php

        $type = request()->query('type');
        $section = $type;

        $fields = config("setting.$section");

    ?>

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __($fields['title']) }}
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers', ['type' => 'internal']) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.transfers', ['type' => 'internal']) }}">
                    {{ __('Internal Transfers') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers', ['type' => 'external']) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.transfers', ['type' => 'external']) }}">
                    {{ __('External Transfers') }}
                </a>
            </li>
        </ul>
    </div>

    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')

            <div class="input-area grid grid-cols-12 gap-5 mb-5">
                <div class="lg:col-span-4 col-span-12 form-label">
                    {{ __('External Transfer Limit') }}
                </div>
                <div class="lg:col-span-8 col-span-12">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                        <div class="col-xl-6 col-sm-12 col-12">
                            <div class="site-input-groups">
                                <label for="" class="form-label">{{ __('Min Amount:') }}</label>
                                <div class="joint-input relative">
                                    <input type="text" class="form-control" name="min_send" value="{{ oldSetting('min_send','fee') }}">
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ setting('site_currency','global') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-12 col-12">
                            <div class="site-input-groups">
                                <label for="" class="form-label">{{ __('Max Amount:') }}</label>
                                <div class="joint-input relative">
                                    <input type="text" class="form-control" name="max_send" value="{{ oldSetting('max_send','fee') }}">
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ setting('site_currency','global') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="input-area grid grid-cols-12 gap-5 mb-5">
                <label for="" class="lg:col-span-4 col-span-12 form-label">{{ __('External Transfer Charge') }}</label>
                <div class="lg:col-span-8 col-span-12">
                    <div class="site-input-groups relative">
                        <div class="relative">
                            <input type="text" class="form-control" value="{{ oldSetting('send_charge','global') }}" name="send_charge">
                            <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                <select name="send_charge_type" class="w-full h-full outline-none" id="">
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
            </div>

            <div class="input-area grid grid-cols-12 gap-5 mb-5">
                <div class="lg:col-span-4 col-span-12 form-label">{{ __('Internal Transfer Limit') }}</div>
                <div class="lg:col-span-8 col-span-12">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                        <div class="site-input-groups">
                            <label for="" class="form-label">{{ __('Min Amount:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="internal_min_send" value="{{ oldSetting('internal_min_send','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ setting('site_currency','global') }}</span>
                            </div>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="form-label">{{ __('Max Amount:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="internal_max_send" value="{{ oldSetting('internal_max_send','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ setting('site_currency','global') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="input-area grid grid-cols-12 gap-5 mb-5">
                <label for="" class="lg:col-span-4 col-span-12 form-label">{{ __('Internal Transfer Charge') }}</label>
                <div class="lg:col-span-8 col-span-12">
                    <div class="site-input-groups relative">
                        <div class="relative">
                            <input type="text" class="form-control" value="{{ oldSetting('internal_send_charge','global') }}" name="internal_send_charge">
                            <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
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
            </div>

            <div class="input-area grid grid-cols-12 gap-5 mb-5">
                <label for="" class="lg:col-span-4 col-span-12 form-label">{{ __('Wallet Exchange Charge') }}</label>
                <div class="lg:col-span-8 col-span-12">
                    <div class="site-input-groups relative">
                        <div class="relative">
                            <input type="text" class="form-control" value="{{ oldSetting('wallet_exchange_charge','global') }}" name="wallet_exchange_charge">
                            <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
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
            </div>

            <div class="input-area grid grid-cols-12 gap-5 mb-5">
                <div class="lg:col-span-4 col-span-12 form-label">{{ __('User Bonus') }}</div>
                <div class="lg:col-span-8 col-span-12">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                        <div class="site-input-groups">
                            <label for="" class="form-label">{{ __('Referral Bonus:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="referral_bonus" value="{{ oldSetting('referral_bonus','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ setting('site_currency','global') }}</span>
                            </div>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="form-label">{{ __('Signup Bonus:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="signup_bonus" value="{{ oldSetting('signup_bonus','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ setting('site_currency','global') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="input-area grid grid-cols-12 gap-5 mb-5">
                <div class="lg:col-span-4 col-span-12 form-label">{{ __('Daily Limits') }}</div>
                <div class="lg:col-span-8 col-span-12">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                        <div class="site-input-groups">
                            <label for="" class="form-label">{{ __('Wallet Exchange Daily Limit:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="wallet_exchange_day_limit" value="{{ oldSetting('wallet_exchange_day_limit','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ __('Times') }}</span>
                            </div>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="form-label">{{ __('Send Money Daily Limit:') }}</label>
                            <div class="joint-input relative">
                                <input type="text" class="form-control" name="send_money_day_limit" value="{{ oldSetting('send_money_day_limit','fee') }}">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ __('Times') }}</span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="site-input-groups">
                                <label for="" class="form-label">{{ __('Withdraw Daily Limit:') }}</label>
                                <div class="joint-input relative">
                                    <input type="text" class="form-control" name="withdraw_day_limit" value="{{ oldSetting('withdraw_day_limit','fee') }}">
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ __('Times') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="site-input-groups">
                                <label for="" class="form-label">{{ __('Investment Cancellation Daily Limit:') }}</label>
                                <div class="joint-input relative">
                                    <input type="text" class="form-control" name="investment_cancellation_daily_limit" value="{{ oldSetting('investment_cancellation_daily_limit','fee') }}">
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">{{ __('Times') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection

