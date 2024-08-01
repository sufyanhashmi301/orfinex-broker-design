@extends('frontend::user.setting.index')
@section('title')
    {{ __('KYC') }}
@endsection
@section('settings-content')


{{--    <div class="card">--}}
{{--        <div class="card-body p-6">--}}
{{--            <div class="mb-10">--}}
{{--                <h4 class="card-title mb-2">{{ __('Verification Center') }}</h4>--}}
{{--                <p class="block font-normal text-sm text-slate-500">--}}
{{--                    {{ __('Ensure Trust and Security: Complete your KYC to access all features.') }}--}}
{{--                </p>--}}
{{--            </div>--}}
{{--            <div class="max-w-5xl mx-auto mb-6">--}}
{{--                <ul class="relative w-full m-0 flex list-none justify-between overflow-hidden p-0 transition-[height] duration-200 ease-in-out">--}}
{{--                    <!--First item-->--}}
{{--                    <li class="w-[4.5rem] flex-auto">--}}
{{--                        <div class="flex items-center pl-2 leading-[1.3rem] no-underline after:ml-2 after:h-3px after:w-full after:flex-1 @if($user->email_verified_at != null) after:bg-primary @else after:bg-[#e0e0e0] @endif after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">--}}
{{--                            <div>--}}
{{--                                @if($user->email_verified_at != null)--}}
{{--                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <circle cx="9.5" cy="9.5" r="9.5" fill="#FED000"/>--}}
{{--                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z" fill="white"/>--}}
{{--                                    </svg>--}}
{{--                                @else--}}
{{--                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <circle cx="14" cy="13.5" r="9" stroke="#FED000"/>--}}
{{--                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="#FED000" stroke-width="4"/>--}}
{{--                                        <circle cx="14" cy="13.5" r="3.5" fill="#FED000"/>--}}
{{--                                    </svg>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </li>--}}

{{--                    <!--Second item-->--}}
{{--                    <li class="w-[4.5rem] flex-auto">--}}
{{--                        <div class="flex items-center leading-[1.3rem] no-underline before:mr-2 before:h-3px before:w-full before:flex-1 @if($user->kyc == 1) before:bg-primary @else before:bg-[#e0e0e0] @endif before:content-[''] after:ml-2 after:h-3px after:w-full after:flex-1 after:bg-[#e0e0e0] after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:before:bg-neutral-600 dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">--}}
{{--                            <div>--}}
{{--                                @if($user->kyc == 1)--}}
{{--                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <circle cx="9.5" cy="9.5" r="9.5" fill="#FED000"/>--}}
{{--                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z" fill="white"/>--}}
{{--                                    </svg>--}}
{{--                                @else--}}
{{--                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <circle cx="14" cy="13.5" r="9" stroke="#FED000"/>--}}
{{--                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="#FED000" stroke-width="4"/>--}}
{{--                                        <circle cx="14" cy="13.5" r="3.5" fill="#FED000"/>--}}
{{--                                    </svg>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </li>--}}

{{--                    <!--Third item-->--}}
{{--                    <li class="w-[4.5rem] flex-auto">--}}
{{--                        <div class="flex items-center pr-2 leading-[1.3rem] no-underline before:mr-2 before:h-3px before:w-full before:flex-1 before:bg-[#e0e0e0] before:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:before:bg-neutral-600 dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">--}}
{{--                            <div>--}}
{{--                                <svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                    <circle cx="14" cy="13.5" r="9" stroke="#FED000"/>--}}
{{--                                    <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="#FED000" stroke-width="4"/>--}}
{{--                                    <circle cx="14" cy="13.5" r="3.5" fill="#FED000"/>--}}
{{--                                </svg>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-3  gap-5">--}}
{{--                <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">--}}
{{--                    <span class="badge bg-primary text-slate-900 capitalize">Automated</span>--}}
{{--                    <p class="text-base font-normal text-slate-500 dark:text-slate-300">Enter your details please</p>--}}
{{--                    <h4 class="text-2xl text-slate-900 dark:text-white">1 - Confirm email and phone</h4>--}}
{{--                    <div class="input-area w-full">--}}
{{--                        <div class="relative">--}}
{{--                            <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->phone }}" disabled>--}}
{{--                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">--}}
{{--                                <a href="javascript:;" class="py-1 px-2 bg-slate-200 text-sm rounded inline-flex items-center">--}}
{{--                                    Verify Now--}}
{{--                                </a>--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="input-area w-full">--}}
{{--                        <div class="relative">--}}
{{--                            <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->email }}" disabled>--}}
{{--                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">--}}
{{--                                @if($user->email_verified_at != null)--}}
{{--                                    <span class="flex items-center text-sm">--}}
{{--                                        Verified--}}
{{--                                        <iconify-icon class="text-base ml-1" icon="bxs:badge-check" style="color: #FED000;"></iconify-icon>--}}
{{--                                    </span>--}}
{{--                                @else--}}
{{--                                    <button type="button"--}}
{{--                                            data-bs-toggle="modal"--}}
{{--                                            data-bs-target="#emailVerifyModal" class="py-1 px-2 bg-slate-200 text-sm rounded inline-flex items-center">--}}
{{--                                        Verify Now--}}
{{--                                    </button>--}}
{{--                                @endif--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <p class="text-slate-900 dark:text-white mb-2">Privileges and Benefit</p>--}}
{{--                        <ul class="space-y-2 mb-10">--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Access to client's area.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Open demo accounts.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Trade on demo accounts.</span>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                    <a href="javascript:;" class="btn @if($user->email_verified_at != null) btn-primary @else btn-dark @endif block-btn mt-auto">--}}
{{--                        @if($user->email_verified_at != null)--}}
{{--                            {{ __('Completed') }}--}}
{{--                        @else--}}
{{--                            {{ __('Submit') }}--}}
{{--                        @endif--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">--}}
{{--                    <span class="badge bg-primary text-slate-900 capitalize">Automated</span>--}}
{{--                    <p class="text-base font-normal text-slate-500 dark:text-slate-300">--}}
{{--                        Provide a document confirming your name--}}
{{--                    </p>--}}
{{--                    <h4 class="text-2xl text-slate-900 dark:text-white">2 - Verify your identity using sumsub</h4>--}}
{{--                    --}}{{--                    <div class="input-area w-full">--}}
{{--                    --}}{{--                        <div class="relative">--}}
{{--                    --}}{{--                            <input type="text" class="form-control form-control-lg !pr-9" placeholder="+971509760755">--}}
{{--                    --}}{{--                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">--}}
{{--                    --}}{{--                                <iconify-icon icon="lucide:folder-open"></iconify-icon>--}}
{{--                    --}}{{--                            </button>--}}
{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <p class="text-slate-900 dark:text-white mb-2">Privileges and Benefit</p>--}}
{{--                        <ul class="space-y-2 mb-10">--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Access to client's area.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Open demo accounts.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Open real accounts.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Trade on real accounts.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Deposit money in real accounts.</span>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                    <a href="{{route('user.kyc.advance')}}" class="btn btn-dark block-btn mt-auto">Go to Sumsub</a>--}}
{{--                </div>--}}
{{--                <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">--}}
{{--                    <span class="badge bg-primary text-slate-900 capitalize">Semi-Automated</span>--}}
{{--                    <p class="text-base font-normal text-slate-500 dark:text-slate-300">--}}
{{--                        You will need to provide proof of your place of residence--}}
{{--                    </p>--}}
{{--                    <h4 class="text-2xl text-slate-500 dark:text-white">3 - Verify residential address</h4>--}}
{{--                    <div class="input-area w-full">--}}
{{--                        <div class="relative">--}}
{{--                            <input type="text" class="form-control form-control-lg !pr-9" placeholder="Add profile information">--}}
{{--                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">--}}
{{--                                <iconify-icon icon="lucide:folder-open"></iconify-icon>--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <p class="text-slate-900 dark:text-white mb-2">Privileges and Benefit</p>--}}
{{--                        <ul class="space-y-2 mb-10">--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Access to client's area.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Open demo & real accounts.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Trade on demo accounts</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Trade on Real Accounts.</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Deposit money in real accounts</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Withdrawal money from real accounts</span>--}}
{{--                            </li>--}}
{{--                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">--}}
{{--                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>--}}
{{--                                <span class="text-slate-500">Priority Customer Support</span>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                    <a href="" class="btn btn-light block-btn mt-auto">Complete step 2 to continue</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="card mt-5">
        <div class="card-body p-6">
            <div class="mb-10">
                <h4 class="card-title mb-2">{{ __('Verification Center') }}</h4>
                <p class="block font-normal text-sm text-slate-500">
                    {{ __('Ensure Trust and Security: Complete your KYC to access all features.') }}
                </p>
            </div>
            <div class="max-w-5xl mx-auto mb-6">
                <ul class="relative w-full m-0 flex list-none justify-between overflow-hidden p-0 transition-[height] duration-200 ease-in-out">
                   @if(isset($kycSettings))
                        @php
                            $activeKycLevels = $kycSettings->where('status', 1);
                            $activeCount = $activeKycLevels->count();
                        @endphp
                        @if($activeCount == 3)
                            @foreach($kycSettings as $kycLevel)
                                @if($kycLevel->status == 1)
                                    <li class="w-[4.5rem] flex-auto">
                                        <div class="flex items-center pl-2 leading-[1.3rem] no-underline after:ml-2 after:h-3px after:w-full after:flex-1
                                            @if(($kycLevel->slug == 'level-1' && $user->email_verified_at != null) || ($kycLevel->slug == 'level-2' && $user->is_level_2_completed == 1)|| ($kycLevel->slug == 'level-3' && $user->is_level_3_completed == 1))
                                                after:bg-primary
                                            @else
                                                after:bg-[#e0e0e0]
                                            @endif
                                            after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]"
                                        >
                                            <div>
                                                @if($kycLevel->slug == 'level-1' && $user->email_verified_at != null || $kycLevel->slug == 'level-2' && $user->is_level_2_completed == 1 || $kycLevel->slug == 'level-3' && $user->is_level_3_completed == 1)
                                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="9.5" cy="9.5" r="9.5" fill="#FED000"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z" fill="white"/>
                                                    </svg>
                                                @else
                                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="14" cy="13.5" r="9" stroke="#FED000"/>
                                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="#FED000" stroke-width="4"/>
                                                        <circle cx="14" cy="13.5" r="3.5" fill="#FED000"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            @foreach($activeKycLevels as $index => $kycLevel)
                                <li class="w-[4.5rem] flex-auto">
                                    <div class="flex items-center pl-2 leading-[1.3rem] no-underline
                                        @if($index < $activeCount - 1) after:ml-2 after:h-3px @endif
                                        after:w-full after:flex-1
                                        @if(($kycLevel->slug == 'level-1' && $user->email_verified_at != null) || ($kycLevel->slug == 'level-2' && $user->is_level_2_completed == 1) || ($kycLevel->slug == 'level-3' && $user->is_level_3_completed == 1))
                                            after:bg-primary
                                        @else
                                            after:bg-[#e0e0e0]
                                        @endif
                                        after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">
                                        <div>
                                            @if(($kycLevel->slug == 'level-1' && $user->email_verified_at != null) || ($kycLevel->slug == 'level-2' && $user->is_level_2_completed == 1) || ($kycLevel->slug == 'level-3' && $user->is_level_3_completed == 1))
                                                <svg width="28" height="27" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="9.5" cy="9.5" r="9.5" fill="#FED000"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z" fill="white"/>
                                                </svg>
                                            @else
                                                <svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="14" cy="13.5" r="9" stroke="#FED000"/>
                                                    <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="#FED000" stroke-width="4"/>
                                                    <circle cx="14" cy="13.5" r="3.5" fill="#FED000"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    @endif
                </ul>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @if(isset($kycSettings))
            @foreach($kycSettings as $kycLevel)
                @if($kycLevel->slug == 'level-1' && $kycLevel->status==1 )
                    @php
                        $emailKyc = $kycLevel->kyc->where('name', 'Email')->first();
                        $phoneKyc = $kycLevel->kyc->where('name', 'Phone')->first();
                    @endphp
                    @if(($emailKyc && $emailKyc->status == 1) || ($phoneKyc && $phoneKyc->status == 1))
                    <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                        <span class="badge bg-primary text-slate-900 capitalize">Automated</span>
                        <p class="text-base font-normal text-slate-500 dark:text-slate-300">Enter your details please</p>
                        <h4 class="text-2xl text-slate-900 dark:text-white">1 - Confirm email and phone</h4>

                        @if($phoneKyc && $phoneKyc->status == 1)
                            <div class="input-area w-full">
                                <div class="relative">
                                    <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->phone }}" disabled>
                                    <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                        <a href="javascript:;" class="py-1 px-2 bg-slate-200 text-sm rounded inline-flex items-center">
                                            Verify Now
                                        </a>
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($emailKyc && $emailKyc->status == 1)
                            <div class="input-area w-full">
                                <div class="relative">
                                    <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->email }}" disabled>
                                    <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                        @if($user->email_verified_at != null)
                                            <span class="flex items-center text-sm">
                                                Verified
                                                <iconify-icon class="text-base ml-1" icon="bxs:badge-check" style="color: #FED000;"></iconify-icon>
                                            </span>
                                        @else
                                            <button type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#emailVerifyModal" class="py-1 px-2 bg-slate-200 text-sm rounded inline-flex items-center">
                                                Verify Now
                                            </button>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div>
                            <p class="text-slate-900 dark:text-white mb-2">Privileges and Benefit</p>
                            <ul class="space-y-2 mb-10">
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Access to client's area.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Open demo accounts.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Trade on demo accounts.</span>
                                </li>
                            </ul>
                        </div>

                        <a href="javascript:;" class="btn @if($user->email_verified_at != null) btn-primary @else btn-dark @endif block-btn mt-auto">
                            @if($user->email_verified_at != null)
                                {{ __('Completed') }}
                            @else
                                {{ __('Submit') }}
                            @endif
                        </a>
                    </div>
                @endif
            @endif

            @if($kycLevel->slug == 'level-2' && $kycLevel->status==1)
                @php
                    $manualSetting = $level2Settings->where('unique_code', 'manual')->first();
                    $samsubSetting = $level2Settings->where('unique_code', 'samsub')->first();
                @endphp
                @if($samsubSetting && $samsubSetting->status == 1)
                    <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                        <span class="badge bg-primary text-slate-900 capitalize">Automated</span>
                        <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                            Provide a document confirming your name
                        </p>
                        <h4 class="text-2xl text-slate-900 dark:text-white">2 - Verify your identity using sumsub</h4>
                        <div>
                            <p class="text-slate-900 dark:text-white mb-2">Privileges and Benefit</p>
                            <ul class="space-y-2 mb-10">
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Access to client's area.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Open demo accounts.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Open real accounts.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Trade on real accounts.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Deposit money in real accounts.</span>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('user.kyc.advance') }}" class="btn btn-dark block-btn mt-auto">Go to Sumsub</a>
                    </div>
                @elseif($manualSetting && $manualSetting->status == 1)
                    <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                        <span class="badge bg-primary text-slate-900 capitalize">Manual</span>
                        <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                            Provide a document confirming your name
                        </p>
                        <h4 class="text-2xl text-slate-900 dark:text-white">2 - Verify your identity manually</h4>
                        <div>
                            <p class="text-slate-900 dark:text-white mb-2">Privileges and Benefit</p>
                            <ul class="space-y-2 mb-10">
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Access to client's area.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Open demo accounts.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Open real accounts.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Trade on real accounts.</span>
                                </li>
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                    <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                    <span class="text-slate-500">Deposit money in real accounts.</span>
                                </li>
                            </ul>
                        </div>
                        @if($user->is_level_2_completed==1)
                        <a href="#" class="btn btn-dark btn-primary block-btn mt-auto">Completed</a>
                        @elseif($user->kyc_credential != null)
                        <a href="#" class="btn btn-light block-btn mt-auto">Pending</a>

                        @else
                        <a href="{{ route('user.kyc.basic') }}" class="btn btn-dark block-btn mt-auto">Go to Manual Submission</a>
                        @endif
                    </div>
                @endif
            @endif
        @endforeach
        @endif
        @if(isset($kycLevel))
        @if($kycLevel->slug == 'level-3' && $kycLevel->status==1)
                <div class="h-100 flex flex-col items-start border border-slate-100 dark:border-slate-700 rounded p-4 gap-3">
                    <span class="badge bg-primary text-slate-900 capitalize">Semi-Automated</span>
                    <p class="text-base font-normal text-slate-500 dark:text-slate-300">
                        You will need to provide proof of your place of residence
                    </p>
                    <h4 class="text-2xl text-slate-500 dark:text-white">3 - Verify residential address</h4>

                    <div>
                        <p class="text-slate-900 dark:text-white mb-2">Privileges and Benefit</p>
                        <ul class="space-y-2 mb-10">
                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">Access to client's area.</span>
                            </li>
                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">Open demo & real accounts.</span>
                            </li>
                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">Trade on demo accounts</span>
                            </li>
                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">Trade on Real Accounts.</span>
                            </li>
                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">Deposit money in real accounts</span>
                            </li>
                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">Withdrawal money from real accounts</span>
                            </li>
                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                                <iconify-icon class="text-primary relative top-[1px]" icon="lucide:check"></iconify-icon>
                                <span class="text-slate-500">Priority Customer Support</span>
                            </li>
                        </ul>
                    </div>

                    @if($user->is_level_3_completed == 1)
                        <a href="#" class="btn btn-primary block-btn mt-auto">Completed</a>
                    @elseif($kycLevel->slug == 'level-2' && $kycLevel->status == 1 && $user->is_level_2_completed == 0)
                        <a href="#" class="btn btn-light block-btn mt-auto">Complete step 2 to continue</a>
                    @elseif($user->is_level_2_completed == 1 && $user->is_level_3_completed == 0 && $user->kyc_credential_level3 == null)
                        <a href="{{ route('user.kyc.level3') }}" class="btn btn-dark block-btn mt-auto">Go to Level 3 Submission</a>
                    @elseif($user->kyc_credential_level3 != null)
                    <a href="#" class="btn btn-light block-btn mt-auto">Pending</a>
                    @else
                    <a href="{{ route('user.kyc.level3') }}" class="btn btn-dark block-btn mt-auto">Go to Level 3 Submission</a>
                    @endif
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Modal for Verify Email -->
    @include('frontend::user.kyc.modal.__email_verify')

@endsection

