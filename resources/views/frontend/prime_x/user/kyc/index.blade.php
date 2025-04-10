@extends('frontend::user.setting.index')
@section('title')
    {{ __('KYC') }}
@endsection
@section('settings-content')
    <div class="card">
        <div class="card-body p-6">
            <div class="mb-10">
                <h4 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                    <iconify-icon icon="lucide:shield-check" class="text-primary text-2xl"></iconify-icon>
                    {{ __('KYC Verification Center') }}
                </h4>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ __('To maintain a secure and compliant trading environment, identity verification is required.') }} <br>
                    {{ __('Complete your KYC steps to unlock full access including deposits, trading, and withdrawals.') }}
                </p>
            </div>
            @if($totalActiveLevels > 1)

                <div class="max-w-5xl mx-auto mb-10">
                    <div class="relative w-full flex items-center justify-between">
                    @php
                        $completedStep = $user->kyc >= \App\Enums\KYCStatus::Level3->value ? 3 :
                                         ($user->kyc >= \App\Enums\KYCStatus::Level2->value ? 2 :
                                         ($user->kyc >= \App\Enums\KYCStatus::Level1->value ? 1 : 0));
                    @endphp

                    <!-- Progress Line -->
                        <div class="absolute top-1/2 left-0 right-0 h-1 z-0 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-300 ease-in-out rounded-full bg-primary"
                                 style="width: {{ $completedStep == 1 ? '50%' : ($completedStep == 2 ? '100%' : ($completedStep == 3 ? '100%' : '0%')) }}">
                            </div>
                        </div>

                        <!-- Step 1 -->
                        <div class="relative z-10 w-8 h-8 flex items-center justify-center bg-white dark:bg-dark border-2 rounded-full
                            @if($user->kyc >= \App\Enums\KYCStatus::Level1->value) border-primary text-primary @else border-slate-300 text-slate-400 @endif">
                            @if($user->kyc >= \App\Enums\KYCStatus::Level1->value)
                                <iconify-icon icon="lucide:check" class="text-lg"></iconify-icon>
                            @else
                                <span class="text-xs font-semibold">1</span>
                            @endif
                        </div>

                        <!-- Step 2 -->
                        <div class="relative z-10 w-8 h-8 flex items-center justify-center bg-white dark:bg-dark border-2 rounded-full
                            @if($user->kyc >= \App\Enums\KYCStatus::Level2->value) border-primary text-primary @else border-slate-300 text-slate-400 @endif">
                            @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                                <iconify-icon icon="lucide:check" class="text-lg"></iconify-icon>
                            @else
                                <span class="text-xs font-semibold">2</span>
                            @endif
                        </div>

                    @if($totalActiveLevels == 3)
                        <!-- Step 3 -->
                            <div class="relative z-10 w-8 h-8 flex items-center justify-center bg-white dark:bg-dark border-2 rounded-full
                                @if($user->kyc >= \App\Enums\KYCStatus::Level3->value) border-primary text-primary @else border-slate-300 text-slate-400 @endif">
                                @if($user->kyc >= \App\Enums\KYCStatus::Level3->value)
                                    <iconify-icon icon="lucide:check" class="text-lg"></iconify-icon>
                                @else
                                    <span class="text-xs font-semibold">3</span>
                                @endif
                            </div>
                        @endif
                    </div>
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
                                <p class="text-slate-900 dark:text-white mb-3 text-sm font-semibold uppercase tracking-wide">{{ __('Privileges of Account Verification') }}</p>
                                <ul class="space-y-2 mb-10">
                                    <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                        <iconify-icon class="text-primary mt-0.5" icon="lucide:user-check"></iconify-icon>
                                        <span>{{ __('Update your full profile securely.') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                        <iconify-icon class="text-primary mt-0.5" icon="lucide:wallet"></iconify-icon>
                                        <span>{{ __('Deposit funds without restrictions.') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                        <iconify-icon class="text-primary mt-0.5" icon="lucide:rocket"></iconify-icon>
                                        <span>{{ __('Open demo and real trading accounts.') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                        <iconify-icon class="text-primary mt-0.5" icon="lucide:refresh-cw"></iconify-icon>
                                        <span>{{ __('Transfer funds internally.') }}</span>
                                    </li>
                                    <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                        <iconify-icon class="text-primary mt-0.5" icon="lucide:headset"></iconify-icon>
                                        <span>{{ __('Create support ticket for assistance.') }}</span>
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

                                <div>
                                    <p class="text-slate-900 dark:text-white mb-2 text-sm font-semibold uppercase tracking-wide">
                                        {{ __('Privileges of Profile Verification') }}
                                    </p>
                                    <ul class="space-y-2 mb-10">
                                        <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                            <iconify-icon class="text-primary mt-0.5" icon="lucide:shield-check"></iconify-icon>
                                            <span>{{ __('Withdraw funds from verified accounts.') }}</span>
                                        </li>
                                        <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                            <iconify-icon class="text-primary mt-0.5" icon="lucide:repeat"></iconify-icon>
                                            <span>{{ __('Make external transfers securely.') }}</span>
                                        </li>
                                        <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                            <iconify-icon class="text-primary mt-0.5" icon="lucide:user-check"></iconify-icon>
                                            <span>{{ __('Get approved for higher trading limits.') }}</span>
                                        </li>
                                        <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                            <iconify-icon class="text-primary mt-0.5" icon="lucide:layout-dashboard"></iconify-icon>
                                            <span>{{ __('Unlock advanced account features.') }}</span>
                                        </li>
                                        <li class="text-sm text-slate-700 dark:text-slate-300 flex items-start space-x-2 rtl:space-x-reverse">
                                            <iconify-icon class="text-primary mt-0.5" icon="lucide:clock"></iconify-icon>
                                            <span>{{ __('Faster processing of requests and reviews.') }}</span>
                                        </li>
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


            <div class="pt-10 border-t border-slate-200 mt-14">
                <h4 class="text-xl font-semibold mb-6 text-slate-800 dark:text-white">{{ __('Why KYC Matters') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-slate-600 dark:text-slate-300">
                    <!-- Secure Account -->
                    <div class="flex items-start gap-4">
                        <div class="text-yellow-600 text-xl mt-1">
                            <iconify-icon icon="lucide:shield-check"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="font-semibold text-base text-slate-700 dark:text-white mb-1">Secure Your Account</h5>
                            <p class="leading-relaxed">
                                Your personal information and funds are safeguarded with top-level encryption and ID checks.
                            </p>
                        </div>
                    </div>

                    <!-- Full Access -->
                    <div class="flex items-start gap-4">
                        <div class="text-yellow-600 text-xl mt-1">
                            <iconify-icon icon="lucide:lock-keyhole"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="font-semibold text-base text-slate-700 dark:text-white mb-1">Full Platform Access</h5>
                            <p class="leading-relaxed">
                                Unlock deposits, withdrawals, real account trading, and financial transactions without limits.
                            </p>
                        </div>
                    </div>

                    <!-- Priority Support -->
                    <div class="flex items-start gap-4">
                        <div class="text-yellow-600 text-xl mt-1">
                            <iconify-icon icon="lucide:badge-check"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="font-semibold text-base text-slate-700 dark:text-white mb-1">Priority Support</h5>
                            <p class="leading-relaxed">
                                Get faster support, quicker approvals, and special attention from our client success team.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Verify Email -->
    @include('frontend::user.kyc.modal.__email_verify')

@endsection

