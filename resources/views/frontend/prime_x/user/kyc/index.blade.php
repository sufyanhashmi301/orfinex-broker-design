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

            {{-- Progress Bar --}}
            <div class="max-w-5xl mx-auto mb-6">
                <ul class="relative w-full m-0 flex list-none justify-between overflow-hidden p-0 transition-[height] duration-200 ease-in-out">

                    <!--First item-->
                    <li class="w-[4.5rem] flex-auto">
                        <div class="flex items-center pl-2 leading-[1.3rem] no-underline after:ml-2 after:h-3px after:w-full after:flex-1  after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b] {{ $email_verified ? 'after:bg-primary' : ' after:bg-[#e0e0e0]' }}">
    
                            <div>
                                @if ($email_verified)
                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="9.5" cy="9.5" r="9.5"
                                            fill="{{ setting('primary_color', 'global') }}" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z"
                                            fill="white" />
                                    </svg>
                                @else
                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="14" cy="13.5" r="9"
                                            stroke="{{ setting('primary_color', 'global') }}" />
                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5"
                                            stroke="{{ setting('primary_color', 'global') }}" stroke-width="4" />
                                        <circle cx="14" cy="13.5" r="3.5"
                                            fill="{{ setting('primary_color', 'global') }}" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!--Second item-->
                    <li class="w-[4.5rem] flex-auto">
                        <div class="flex items-center leading-[1.3rem] no-underline before:mr-2 before:h-3px before:w-full before:flex-1 after:bg-primary before:content-[''] {{ $kyc_verified ? 'before:bg-primary' : 'before:bg-[#e0e0e0]' }}">
                            <div>
                                @if ($kyc_verified)
                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="9.5" cy="9.5" r="9.5"
                                            fill="{{ setting('primary_color', 'global') }}" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z"
                                            fill="white" />
                                    </svg>
                                @else
                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="14" cy="13.5" r="9"
                                            stroke="{{ setting('primary_color', 'global') }}" />
                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5"
                                            stroke="{{ setting('primary_color', 'global') }}" stroke-width="4" />
                                        <circle cx="14" cy="13.5" r="3.5"
                                            fill="{{ setting('primary_color', 'global') }}" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2  gap-5">
                
                {{-- Email --}}
                <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                    <span class="badge badge-primary capitalize">{{ __('Automated') }}</span>
                    <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                        {{ __('Verify your details please') }}
                    </p>
                    
                    <h4 class="text-2xl text-slate-900 dark:text-white">{{ __('1 - Confirm Email') }}</h4>
                 
                    <div class="input-area w-full">
                        <div class="relative">
                            <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->email }}" disabled>
                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                @if ($email_verified)
                                    <span class="flex items-center text-sm">
                                        {{ __('Verified') }}
                                        <iconify-icon class="text-primary ml-1" icon="bxs:badge-check" style="color: {{ setting('primary_color', 'global') }};"></iconify-icon>
                                    </span>
                                @else
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#emailVerifyModal"
                                        class="py-1 px-2 bg-slate-200 text-sm rounded inline-flex items-center">
                                        {{ __('Verify Now') }}
                                    </button>
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div>
                        <p class="text-slate-900 dark:text-white mb-2">{{ __('Privileges and Benefit') }}</p>
                        <ul class="space-y-2 mb-10">
                            <li
                                class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]"
                                    icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">{{ __("Access to client's area.") }}</span>
                            </li>
                            <li
                                class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]"
                                    icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">{{ __('Buy Accounts.') }}</span>
                            </li>
                            <li
                                class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]"
                                    icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">{{ __('Trade on accounts.') }}</span>
                            </li>
                        </ul>
                    </div>

                    <a href="javascript:void(0);" {!! !$email_verified ? 'data-bs-toggle="modal" data-bs-target="#emailVerifyModal"' : '' !!} class="btn btn-primary {{ $email_verified ? 'cursor-not-allowed' : '' }} block-btn mt-auto">
                        @if ($email_verified)
                            {{ __('Verified') }}
                        @else
                            {{ __('Verify Now') }}
                        @endif
                    </a>
                </div>

                {{-- Sumsub or Manual --}}
                <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                    <span class="badge badge-primary capitalize">{{ $kyc_method->slug == 'manual' ? 'Manual' : 'Automated' }}</span>
                    <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                        {{ __('Provide a document confirming your name') }}
                    </p>
                    <h4 class="text-2xl text-slate-900 dark:text-white">
                        {{ __('2 - Verify your Identity') }}
                    </h4>
                    <div>
                        <p class="text-slate-900 dark:text-white mb-2">{{ __('Privileges and Benefit') }}</p>
                        <ul class="space-y-2 mb-10">
                            @include('frontend.prime_x.user.kyc.include.__level_2_benefits')
                        </ul>
                    </div>
                    
                    @if(!$email_verified)
                        <a href="javascript:void(0);" class="btn btn-light block-btn cursor-not-allowed mt-auto">{{ __('Verify Email') }}</a>
                    @elseif ($user->kyc->status == \App\Enums\KycStatusEnums::VERIFIED)
                        <a href="javascript:void(0);" class="btn btn-dark btn-primary block-btn cursor-not-allowed mt-auto">{{ __('Verified') }}</a>
                    @elseif ($user->kyc->status == \App\Enums\KycStatusEnums::PENDING)
                        <a href="javascript:void(0);" class="btn btn-dark btn-primary block-btn cursor-not-allowed mt-auto">{{ __('Pending') }}</a>
                    @elseif ($user->kyc->status == \App\Enums\KycStatusEnums::UNVERIFIED)
                        <a href="{{ $kyc_method->slug == 'manual' ? route('user.verification.manual_kyc') : route('user.verification.automatic_kyc')  }}" class="btn btn-primary loaderBtn block-btn mt-auto">
                            {{ __('Verify Now') }}
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Modal for Verify Email -->
    @include('frontend::user.kyc.modal.__email_verify')

@endsection
