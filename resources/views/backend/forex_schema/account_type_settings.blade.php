@extends('backend.layouts.app')
@section('title')
    {{ __('Account Type Settings') }}
@endsection
@section('content')
    @include('backend.forex_schema.include.__menu')
    <?php
    $sections = ['account_type_settings'];
    ?>
    <div class="space-y-5">
        @foreach ($sections as $section)
            <?php $fields = config('setting.' . $section); ?>
            @if (!empty($fields))
                @include('backend.setting.site_setting.include.__global')
            @endif
        @endforeach
        <div class="card">
            <div class="card-body p-6 space-y-4">
                <h4 class="card-title">{{ __('Account Type Settings') }}</h4>

                {{-- Branch System Information --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <h5 class="text-lg font-bold text-blue-900 mb-2">{{ __('Branch System Overview') }}</h5>
                    <p class="text-blue-800 mb-3">
                        {{ __('If an account type is assigned to a branch, it will be visible only to users assigned to that branch. The following rules apply:') }}
                    </p>
                </div>

                {{-- Branch Visibility Rules --}}
                <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                    <h5 class="text-base font-bold mb-3">{{ __('Branch System Visibility Rules') }}</h5>

                    <div class="space-y-4">
                        {{-- Rule 1: Universal Global --}}
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <h6 class="font-semibold text-green-900">1. {{ __('Universal Global') }}</h6>
                            <p class="text-sm text-gray-700">
                                {{ __('Visible to all users, whether they are assigned to a branch or not.') }}</p>
                        </div>

                        {{-- Rule 2: Branch + Global --}}
                        <div class="border-l-4 border-purple-500 pl-4 py-2">
                            <h6 class="font-semibold text-purple-900">2.
                                {{ __('Branch + Global (with IB Rebate Rules Settings)') }}</h6>
                            <p class="text-sm text-gray-700">
                                {{ __('Shows only to branch-assigned users with global settings defined with IB Rebate Rules settings.') }}
                            </p>
                        </div>

                        {{-- Rule 3: No Branch + Global --}}
                        <div class="border-l-4 border-indigo-500 pl-4 py-2">
                            <h6 class="font-semibold text-indigo-900">3.
                                {{ __('No Branch + Global (with IB Rebate Rules Settings)') }}</h6>
                            <p class="text-sm text-gray-700">
                                {{ __('Shows only to non-branch users with global settings defined with IB Rebate Rules settings.') }}
                            </p>
                        </div>

                        {{-- Rule 4: Branch + Country & Tag --}}
                        <div class="border-l-4 border-yellow-500 pl-4 py-2">
                            <h6 class="font-semibold text-yellow-900">4. {{ __('Branch + Country & Tag') }}</h6>
                            <p class="text-sm text-gray-700">
                                {{ __('Shows only to branch-assigned users who meet the same country and tag criteria.') }}
                            </p>
                        </div>

                        {{-- Rule 5: No Branch + Country & Tag --}}
                        <div class="border-l-4 border-orange-500 pl-4 py-2">
                            <h6 class="font-semibold text-orange-900">5. {{ __('No Branch + Country & Tag') }}</h6>
                            <p class="text-sm text-gray-700">
                                {{ __('Shows only to non-branch users who meet the same country and tag criteria.') }}</p>
                        </div>

                        {{-- Rule 6: Branch + IB Rebate Rules --}}
                        <div class="border-l-4 border-red-500 pl-4 py-2">
                            <h6 class="font-semibold text-red-900">6. {{ __('Branch + IB Rebate Rules') }}</h6>
                            <p class="text-sm text-gray-700">
                                {{ __('Shows only to branch-assigned users. This depends on IB rebate rules attached to account types. It will show all IB group network as per assigned account types with IB rebate rules.') }}
                            </p>
                        </div>

                        {{-- Rule 7: No Branch + IB Rebate Rules --}}
                        <div class="border-l-4 border-pink-500 pl-4 py-2">
                            <h6 class="font-semibold text-pink-900">7. {{ __('No Branch + IB Rebate Rules') }}</h6>
                            <p class="text-sm text-gray-700">
                                {{ __('Shows only to non-branch users. This depends on IB rebate rules attached to account types. It will show all IB group network as per assigned account types with IB rebate rules.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <p class="card-text">{{ __('We have 3 account categories:') }} <span
                        class="font-semibold">{{ __('Global, Country & Tags, and IB Rebate Rules.') }}</span></p>

                <ol class="list-decimal ml-4 pl-5 space-y-3">
                    <li>
                        <h5 class="text-sm font-semibold">{{ __('Show Global Accounts with Country & Tags') }}</h5>
                        <ul class="list-disc ml-4 pl-5 space-y-1">
                            <li><span class="font-semibold">{{ __('Enabled:') }}</span>
                                {{ __('Global accounts will also appear for users that match by country or tags.') }}</li>
                            <li><span class="font-semibold">{{ __('Disabled:') }}</span>
                                {{ __('Global accounts will be hidden; only country/tag-specific accounts will show.') }}
                            </li>
                        </ul>
                    </li>
                    <li>
                        <h5 class="text-sm font-semibold">{{ __('Show Global Accounts with IB Rebate Rules') }}</h5>
                        <ul class="list-disc ml-4 pl-5 space-y-1">
                            <li><span class="font-semibold">{{ __('Enabled:') }}</span>
                                {{ __('Global accounts will also appear for users within the IB network as per ib group settings.') }}
                            </li>
                            <li><span class="font-semibold">{{ __('Disabled:') }}</span>
                                {{ __('Global accounts will be hidden; users will only see accounts tied to their IB group.') }}
                            </li>
                        </ul>
                    </li>
                </ol>

                <div class="border-t pt-3 mt-2 space-y-2">
                    <h4 class="card-title">{{ __('Exception') }}</h4>
                    <p>{{ __('When adding/updating an account type, there is a toggle under global account type:') }}</p>
                    <p class="italic font-semibold">{{ __('“Set as Universal Global Account”') }}</p>
                    <ul class="list-disc ml-4 pl-5 space-y-1">
                        <li>{{ __('If this is enabled, it overrides branchcountry, tag, and IB restrictions.') }}</li>
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
                    <p>{{ __('For new users who are not yet associated with any Branch, IB group, rebate rule, country, or tag, all Global and Universal Global account types are visible. Once they become associated with an Branch, IB group, rebate rule, country, or tag, the above visibility rules and settings will apply.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
