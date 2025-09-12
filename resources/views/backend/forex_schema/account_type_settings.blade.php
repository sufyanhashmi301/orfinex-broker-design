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
            <h4 class="font-medium text-base mb-3">{{ __('Account Type Settings') }}</h4>
            <p class="text-sm text-slate-700 dark:text-slate-200 mb-3">
                {{ __('We have 3 account categories:') }}
                <span class="font-semibold">{{ __('Global') }}</span>,
                <span class="font-semibold">{{ __('Country & Tags') }}</span>,
                {{ __('and') }}
                <span class="font-semibold">{{ __('IB Rebate Rules') }}</span>.
            </p>

            <ol class="list-decimal ltr:ml-5 rtl:mr-5 space-y-3 text-sm text-slate-700 dark:text-slate-200">
                <li>
                    <span class="font-semibold">{{ __('Show Global Accounts with Country & Tags') }}</span>
                    <ul class="list-disc ltr:ml-5 rtl:mr-5 mt-1 space-y-1">
                        <li><span class="font-semibold">{{ __('Enabled') }}</span>: {{ __('Global accounts will also appear for users that match by country or tags.') }}</li>
                        <li><span class="font-semibold">{{ __('Disabled') }}</span>: {{ __('Global accounts will be hidden; only country/tag-specific accounts will show.') }}</li>
                    </ul>
                </li>
                <li>
                    <span class="font-semibold">{{ __('Show Global Accounts with IB Rebate Rules') }}</span>
                    <ul class="list-disc ltr:ml-5 rtl:mr-5 mt-1 space-y-1">
                        <li><span class="font-semibold">{{ __('Enabled') }}</span>: {{ __('Global accounts will also appear for users within the IB network.') }}</li>
                        <li><span class="font-semibold">{{ __('Disabled') }}</span>: {{ __('Global accounts will be hidden; users will only see accounts tied to their IB group.') }}</li>
                    </ul>
                </li>
            </ol>

            <hr class="my-4">
<br>
            <h4 class="font-medium text-base mb-3">{{ __('Exception') }}</h4>
            <p class="text-sm text-slate-700 dark:text-slate-200 mb-1">{!! __('When :strong, there is a toggle:', ['strong' => '<span class="font-semibold">'.__('adding/updating an account type').'</span>']) !!}</p>
            <p class="text-sm font-semibold mb-1">{{ __('“Set as Global Account”') }}</p>
            <ul class="list-disc ltr:ml-5 rtl:mr-5 space-y-1 text-sm text-slate-700 dark:text-slate-200">
                <li>{{ __('If this is enabled, it overrides country, tag, and IB restrictions.') }}</li>
                <li>{!! __('The account will be visible to :strong, regardless of the above settings.', ['strong' => '<span class="font-semibold">'.__('all users').'</span>']) !!}</li>
            </ul>
        </div>
    </div>
@endsection

