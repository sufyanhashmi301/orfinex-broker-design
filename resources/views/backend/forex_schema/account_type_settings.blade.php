@extends('backend.layouts.app')
@section('title')
    {{ __('Account Type Settings') }}
@endsection
@section('content')
    @include('backend.forex_schema.include.__menu')
    <?php
        $sections = [
            'account_type_settings',
        ];
    ?>
    <div class="space-y-5">
        @foreach($sections as $section)
            <?php $fields = config('setting.' . $section); ?>
            @if(!empty($fields))
                @include('backend.setting.site_setting.include.__global')
            @endif
        @endforeach
    </div>
    <div class="card mt-5">
        <div class="card-body p-6">
            <h4 class="font-medium text-base mb-3">{{ __('How these settings work') }}</h4>
            <ul class="list-disc ltr:ml-5 rtl:mr-5 space-y-1 text-sm text-slate-700 dark:text-slate-200">
                <li><span class="font-semibold">{{ __('Show Global Accounts with Country & Tags') }}</span>: {{ __('ON shows Global Accounts by user Country/Tags (and truly global with no Country/Tags). OFF hides them.') }}</li>
                <li><span class="font-semibold">{{ __('Show Global Accounts with IB Rebate Rules') }}</span>: {{ __('ON shows Global Accounts from IB Rebate Rules (IB users). OFF hides them.') }}</li>
                <li><span class="font-semibold">{{ __('Always visible') }}</span>: {{ __('Any schema with enabled global account category toggle in account types is shown regardless of the above toggles.') }}</li>
            </ul>
        </div>
    </div>
@endsection

