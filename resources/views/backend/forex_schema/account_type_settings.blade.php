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
        <div class="card">
            <div class="card-body p-6 space-y-4">
                <h4 class="card-title">{{ __('Account Type Settings') }}</h4>
                <p class="card-text">{{ __('We have 3 account categories:') }} <span class="font-semibold">{{ __('Global, Country & Tags, and IB Rebate Rules.') }}</span></p>

                <ol class="list-decimal ml-4 pl-5 space-y-3">
                    <li>
                        <h5 class="text-sm font-semibold">{{ __('Show Global Accounts with Country & Tags') }}</h5>
                        <ul class="list-disc ml-4 pl-5 space-y-1">
                            <li><span class="font-semibold">{{ __('Enabled:') }}</span> {{ __('Global accounts will also appear for users that match by country or tags.') }}</li>
                            <li><span class="font-semibold">{{ __('Disabled:') }}</span> {{ __('Global accounts will be hidden; only country/tag-specific accounts will show.') }}</li>
                        </ul>
                    </li>
                    <li>
                        <h5 class="text-sm font-semibold">{{ __('Show Global Accounts with IB Rebate Rules') }}</h5>
                        <ul class="list-disc ml-4 pl-5 space-y-1">
                            <li><span class="font-semibold">{{ __('Enabled:') }}</span> {{ __('Global accounts will also appear for users within the IB network as per ib group settings.') }}</li>
                            <li><span class="font-semibold">{{ __('Disabled:') }}</span> {{ __('Global accounts will be hidden; users will only see accounts tied to their IB group.') }}</li>
                        </ul>
                    </li>
                </ol>

                <div class="border-t pt-3 mt-2 space-y-2">
                    <h4 class="card-title">{{ __('Exception') }}</h4>
                    <p>{{ __('When adding/updating an account type, there is a toggle under global account type:') }}</p>
                    <p class="italic font-semibold">{{ __('“Set as Universal Global Account”') }}</p>
                    <ul class="list-disc ml-4 pl-5 space-y-1">
                        <li>{{ __('If this is enabled, it overrides country, tag, and IB restrictions.') }}</li>
                        <li>{{ __('The account will be visible to all users, regardless of the above settings.') }}</li>
                    </ul>
                </div>

                <div class="border-t pt-3 mt-2 space-y-2">
                    <h4 class="card-title">{{ __('Global vs Universal Global') }}</h4>
                    <ul class="list-disc ml-4 pl-5 space-y-1">
                        <li>
                            <span class="font-semibold">{{ __('Universal Global (enabled):') }}</span>
                            {{ __('Always visible to all users. If you enable universal global it overrides country, tag, and IB rebate rules. Shown as “Universal Global” in listings.') }}
                        </li>
                        <li>
                            <span class="font-semibold">{{ __('Global:') }}</span>
                            {{ __('Visibility follows settings and matching above rules. Additionally, if a user does not match any country, tag, or IB rule, these Global accounts are still shown as a fallback. Shown as “Global” in listings.') }}
                        </li>
                    </ul>
                </div>
                <div class="border-t pt-3 mt-2 space-y-2">
                <h4 class="card-title">{{ __('New Users') }}</h4>
                    <p>{{ __('For new users who are not yet associated with any IB group, rebate rule, country, or tag, all Global and Universal Global account types are visible. Once they become associated with an IB group, rebate rule, country, or tag, the above visibility rules and settings will apply.') }}</p>
                </div>
            </div>
        </div>
    </div>
    
@endsection

