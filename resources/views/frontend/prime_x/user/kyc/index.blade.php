@extends('frontend::user.setting.index')
@section('title')
    {{ __('KYC') }}
@endsection
@section('settings-content')
    <div class="card">
        <div class="card-body p-6">
            <div class="mb-10">
                <h4 class="card-title mb-2">{{ __('Verification Center') }}</h4>
                <p class="block font-normal text-sm text-slate-500">
                    {{ __('Ensure Trust and Security: Complete your KYC to access all features.') }}
                </p>
            </div>
            @if($totalActiveLevels > 1)
            <div class="max-w-5xl mx-auto mb-6">
                <ul class="relative w-full m-0 flex list-none justify-between overflow-hidden p-0 transition-[height] duration-200 ease-in-out">
                    <!--First item-->
                    <li class="w-[4.5rem] flex-auto">
                        <div class="flex items-center pl-2 leading-[1.3rem] no-underline after:ml-2 after:h-3px after:w-full after:flex-1 @if($user->email_verified_at != null) after:bg-primary @else after:bg-[#e0e0e0] @endif after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">
                            <div>
                                @if($user->kyc >= \App\Enums\KYCStatus::Level1->value)
                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="9.5" cy="9.5" r="9.5" fill="{{ setting('primary_color', 'global') }}"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z" fill="white"/>
                                    </svg>
                                @else
                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="14" cy="13.5" r="9" stroke="{{ setting('primary_color', 'global') }}"/>
                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="{{ setting('primary_color', 'global') }}" stroke-width="4"/>
                                        <circle cx="14" cy="13.5" r="3.5" fill="{{ setting('primary_color', 'global') }}"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!--Second item-->
                    <li class="w-[4.5rem] flex-auto">
                        <div class="flex items-center leading-[1.3rem] no-underline before:mr-2 before:h-3px before:w-full before:flex-1 @if($user->kyc > \App\Enums\KYCStatus::Level1->value) before:bg-primary @else before:bg-[#e0e0e0] @endif before:content-[''] @if($totalActiveLevels > 2)after:ml-2 after:h-3px after:w-full after:flex-1 @if($user->kyc > \App\Enums\KYCStatus::Level2->value) after:bg-primary @else after:bg-[#e0e0e0] @endif after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:before:bg-neutral-600 dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b] @endif">
                            <div>
                                @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="9.5" cy="9.5" r="9.5" fill="{{ setting('primary_color', 'global') }}"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z" fill="white"/>
                                    </svg>
                                @else
                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="14" cy="13.5" r="9" stroke="{{ setting('primary_color', 'global') }}"/>
                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="{{ setting('primary_color', 'global') }}" stroke-width="4"/>
                                        <circle cx="14" cy="13.5" r="3.5" fill="{{ setting('primary_color', 'global') }}"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </li>

                    @if($totalActiveLevels == 3)
                        <!--Third item-->
                        <li class="w-[4.5rem] flex-auto">
                            <div class="flex items-center pr-2 leading-[1.3rem] no-underline before:mr-2 before:h-3px before:w-full before:flex-1 before:bg-[#e0e0e0] before:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:before:bg-neutral-600 @if($user->kyc > \App\Enums\KYCStatus::Level2->value) before:bg-primary @else before:bg-[#e0e0e0] @endif dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b']">
                                <div>
                                    @if($user->kyc == \App\Enums\KYCStatus::Level3->value)
                                        <svg width="28" height="27" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="9.5" cy="9.5" r="9.5" fill="{{ setting('primary_color', 'global') }}"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z" fill="white"/>
                                        </svg>
                                    @else
                                        <svg width="28" height="27" viewBox="0 0 28 27" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="14" cy="13.5" r="9" stroke="{{ setting('primary_color', 'global') }}"/>
                                            <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="{{ setting('primary_color', 'global') }}" stroke-width="4"/>
                                            <circle cx="14" cy="13.5" r="3.5" fill="{{ setting('primary_color', 'global') }}"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-3  gap-5">
                @foreach($kycLevels as $kycLevel)
                    @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL1)
                        <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                            <span class="badge badge-primary capitalize">{{ __('Automated') }}</span>
                            <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                                {{ __('Verify your details please') }}
                            </p>
                            @php
                                $phoneSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::PHONE)->first();
                                $emailSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::EMAIL)->first();
                            @endphp
                            <h4 class="text-2xl text-slate-900 dark:text-white">{{ __('1 - Confirm ') }} @if($emailSubLevel && $emailSubLevel->status) {{__('Email')}}  @endif @if($kycLevel->kyc_sub_levels()->where('status', true)->count()>1) {{__(' and ')}} @endif @if($phoneSubLevel && $phoneSubLevel->status) {{__('Phone')}}  @endif</h4>
                                @if($phoneSubLevel && $phoneSubLevel->status)
                                <div class="input-area w-full">
                                    <div class="relative">
                                        <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->phone }}" disabled>
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            <a href="javascript:void(0);" class="py-1 px-2 bg-slate-200 text-sm rounded inline-flex items-center">
                                                {{ __('Verify Now') }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @if($emailSubLevel && $emailSubLevel->status)
                                <div class="input-area w-full">
                                    <div class="relative">
                                        <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->email }}" disabled>
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            @if($user->email_verified_at != null)
                                                <span class="flex items-center text-sm">
                                                    {{ __('Verified') }}
                                                    <iconify-icon class="text-primary ml-1" icon="bxs:badge-check" style="color: {{ setting('primary_color', 'global') }};"></iconify-icon>
                                                </span>
                                            @else
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#emailVerifyModal" class="py-1 px-2 bg-slate-200 text-sm rounded inline-flex items-center">
                                                    {{ __('Verify Now') }}
                                                </button>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <p class="text-slate-900 dark:text-white mb-2">{{ __('Privileges and Benefit') }}</p>
                                <ul class="space-y-2 mb-10">
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __("Access to client's area.") }}</span>
                                    </li>
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __('Open demo accounts.') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __('Trade on demo accounts.') }}</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="javascript:void(0);"
                               class="btn btn-primary @if($user->kyc == \App\Enums\KYCStatus::Level1->value) cursor-not-allowed @endif block-btn mt-auto">
                                @if($user->kyc >= \App\Enums\KYCStatus::Level1->value)
                                    {{ __('Completed') }}
                                @else
                                    {{ __('Verify Your Level 1') }}
                                @endif
                            </a>
                        </div>
                    @endif
                    @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL2)
                        @php
                            $manualSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::MANUAL)->where('status', true)->first();
                            $automaticSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::AUTOMATIC)->where('status', true)->first();
                        @endphp
                        @if($automaticSubLevel && $automaticSubLevel->status)
                            <div
                                class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                                <span class="badge badge-primary capitalize">{{ __('Automated') }}</span>
                                <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                                    {{ __('Provide a document confirming your name') }}
                                </p>
                                <h4 class="text-2xl text-slate-900 dark:text-white">
                                    {{ __('2 - Verify your identity using Sumsub') }}
                                </h4>
                                {{--                    <div class="input-area w-full">--}}
                                {{--                        <div class="relative">--}}
                                {{--                            <input type="text" class="form-control form-control-lg !pr-9" placeholder="+971509760755">--}}
                                {{--                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">--}}
                                {{--                                <iconify-icon icon="lucide:folder-open"></iconify-icon>--}}
                                {{--                            </button>--}}
                                {{--                        </div>--}}
                                {{--                    </div>--}}
                                <div>
                                    <p class="text-slate-900 dark:text-white mb-2">{{ __('Privileges and Benefit') }}</p>
                                    <ul class="space-y-2 mb-10">
                                        @include('frontend.prime_x.user.kyc.include.__level_2_benefits')
                                    </ul>
                                </div>
                                @if($user->kyc>=\App\Enums\KYCStatus::Level2->value)
                                    <a href="javascript:void(0);" class="btn btn-dark btn-primary block-btn mt-auto">{{ __('Completed') }}</a>
                                @elseif(!isset($user->kyc) || $user->kyc < \App\Enums\KYCStatus::Level1->value)
                                    <a href="javascript:void(0);" class="btn btn-light block-btn mt-auto">{{ __('Complete step 1 to continue') }}</a>
                                @else
                                    <a href="{{route('user.kyc.automatic')}}" class="btn btn-primary loaderBtn block-btn mt-auto">
                                        {{ __('Go to Sumsub') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                        @if($manualSubLevel && $manualSubLevel->status)
                            <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                                <span class="badge badge-primary capitalize">{{ __('Manual') }}</span>
                                <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                                    {{ __('Provide a document confirming your name') }}
                                </p>
                                <h4 class="text-2xl text-slate-900 dark:text-white">
                                    {{ __('2 - Verify your identity manually') }}
                                </h4>
                                <div>
                                    <p class="text-slate-900 dark:text-white mb-2">{{ __('Privileges and Benefit') }}</p>
                                    <ul class="space-y-2 mb-10">
                                        @include('frontend.prime_x.user.kyc.include.__level_2_benefits')
                                    </ul>
                                </div>
                                @if($user->kyc >=\App\Enums\KYCStatus::Level2->value)
                                    <a href="javascript:void(0);" class="btn btn-dark btn-primary block-btn mt-auto">{{ __('Completed') }}</a>
                                @elseif($user->kyc == \App\Enums\KYCStatus::Pending->value)
                                    <a href="javascript:void(0);" class="btn btn-light block-btn mt-auto">{{ __('Pending') }}</a>

                                @elseif(!isset($user->kyc) || $user->kyc < \App\Enums\KYCStatus::Level1->value)
                                    <a href="javascript:void(0);" class="btn btn-light block-btn mt-auto">{{ __('Complete step 1 to continue') }}</a>
                                @else
                                    <a href="{{ route('user.kyc.basic') }}" class="btn btn-primary loaderBtn block-btn mt-auto">
                                        {{ __('Go to Manual Submission') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    @endif
                    @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL3)

                        <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                            <span class="badge badge-primary capitalize">{{ __('Semi-Automated') }}</span>
                            <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                                {{ __('You will need to provide proof of your place of residence') }}
                            </p>
                            <h4 class="text-2xl text-slate-500 dark:text-white">
                                {{ __('3 - Verify residential address') }}
                            </h4>
{{--                            <div class="input-area w-full">--}}
{{--                                <div class="relative">--}}
{{--                                    <input type="text" class="form-control form-control-lg !pr-9"--}}
{{--                                           placeholder="Add profile information">--}}
{{--                                    <span--}}
{{--                                        class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">--}}
{{--                                        <iconify-icon icon="lucide:folder-open"></iconify-icon>--}}
{{--                                    </span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div>
                                <p class="text-slate-900 dark:text-white mb-2">{{ __('Privileges and Benefit') }}</p>
                                <ul class="space-y-2 mb-10">
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __("Access to client's area.") }}</span>
                                    </li>
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __('Open demo & real accounts.') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __('Trade on demo accounts') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __('Trade on Real Accounts.') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __('Deposit money in real accounts') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __('Withdrawal money from real accounts') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                        <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                        <span class="text-slate-500">{{ __('Priority Customer Support') }}</span>
                                    </li>
                                </ul>
                            </div>
                            @if($user->kyc==\App\Enums\KYCStatus::Level3->value)
                                <a href="javascript:void(0);" class="btn btn-dark btn-primary block-btn mt-auto">{{ __('Completed') }}</a>
                            @elseif($user->kyc == \App\Enums\KYCStatus::PendingLevel3->value)
                                <a href="javascript:void(0);" class="btn btn-light block-btn mt-auto">{{ __('Pending') }}</a>

                            @elseif($user->kyc < \App\Enums\KYCStatus::Level2->value)
                                <a href="javascript:void(0);" class="btn btn-light block-btn mt-auto">{{ __('Complete step 2 to continue') }}</a>
                            @else
                                <a href="{{ route('user.kyc.level3') }}" class="btn btn-primary loaderBtn block-btn mt-auto">
                                    {{ __('Go to Manual Submission') }}
                                </a>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal for Verify Email -->
    @include('frontend::user.kyc.modal.__email_verify')

@endsection

