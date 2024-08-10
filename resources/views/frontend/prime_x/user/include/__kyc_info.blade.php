@php
    $kycLevels = \App\Models\Kyclevel::with('kyc_sub_levels')->where('status', true)->get();
    $totalActiveLevels = $kycLevels->count();
    $completedSteps = 0;
    if($totalActiveLevels > 0){
    if($user->email_verified_at != null)
        $completedSteps++;

    if($user->is_level_2_completed != null)
        $completedSteps++;
    }
@endphp
@if($totalActiveLevels > 0 && $completedSteps < $totalActiveLevels)
    <div class="alert alert-dismissible py-[18px] px-6 font-normal text-sm rounded-md border mb-5"
         style="background-color: rgba(254, 208, 0, 0.3); border-color: #FED000;" role="alert">
        <div class="flex flex-wrap items-center space-x-3 space-y-3 rtl:space-x-reverse">
            <div class="flex-1 flex items-center space-x-3">
                <iconify-icon class="text-2xl flex-0 text-danger-500" icon="typcn:warning"></iconify-icon>
                <div class="font-inter text-slate-900 dark:text-white">
                    <p class="text-lg font-medium">
                        {{ __('Steps Completed: ') }}{{$completedSteps}}{{__('/')}}{{$totalActiveLevels}}
                    </p>
                    @if($user->kyc == \App\Enums\KYCStatus::Pending->value)
                        <strong>{{ __('KYC Pending') }}</strong>
                    @else
                        {{ __('You need to submit your KYC and Other Documents before proceed to the system.') }}
                    @endif
                </div>
            </div>
            <div class="flex-0 text-xl cursor-pointer text-danger-500">
                @if($user->kyc != \App\Enums\KYCStatus::Pending->value)
                    <a href="{{ route('user.kyc') }}" class="btn inline-flex justify-center btn-dark btn-sm">
                        <span>{{ __('Submit Now') }}</span>
                    </a>
                    <a href="" class="btn inline-flex justify-center btn-outline-dark btn-sm" type="button"
                       data-bs-dismiss="alert" aria-label="Close">
                        <span>{{ __('Later') }}</span>
                    </a>
                @endif
            </div>
        </div>
        @if($totalActiveLevels > 1)
            <div class="max-w-4xl mt-6">
                <ul class="relative w-full m-0 flex list-none justify-between overflow-hidden p-0 transition-[height] duration-200 ease-in-out">
                    <!--First item-->
                    <li class="w-[4.5rem] flex-auto">
                        <div
                            class="flex items-center pl-2 leading-[1.3rem] no-underline after:ml-2 after:h-3px after:w-full after:flex-1 @if($user->email_verified_at != null) after:bg-primary @else after:bg-[#e0e0e0] @endif after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">
                            <div>
                                @if($user->email_verified_at != null)
                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="9.5" cy="9.5" r="9.5" fill="#FED000"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z"
                                              fill="white"/>
                                    </svg>
                                @else
                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="14" cy="13.5" r="9" stroke="#FED000"/>
                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="#FED000"
                                                stroke-width="4"/>
                                        <circle cx="14" cy="13.5" r="3.5" fill="#FED000"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!--Second item-->
                    <li class="w-[4.5rem] flex-auto">
                        <div
                            class="flex items-center leading-[1.3rem] no-underline before:mr-2 before:h-3px before:w-full before:flex-1 @if($user->kyc == 1) before:bg-primary @else before:bg-[#e0e0e0] @endif before:content-[''] @if($totalActiveLevels > 2)after:ml-2 after:h-3px after:w-full after:flex-1 after:bg-[#e0e0e0] after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:before:bg-neutral-600 dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b] @endif">
                            <div>
                                @if($user->kyc == 1)
                                    <svg width="28" height="27" viewBox="0 0 19 19" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="9.5" cy="9.5" r="9.5" fill="#FED000"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M15.6628 6.08736C15.8906 6.31516 15.8906 6.68451 15.6628 6.91232L8.6628 13.9123C8.435 14.1401 8.06565 14.1401 7.83785 13.9123L4.33785 10.4123C4.11004 10.1845 4.11004 9.81516 4.33785 9.58736C4.56565 9.35955 4.935 9.35955 5.1628 9.58736L8.25033 12.6749L14.8378 6.08736C15.0657 5.85955 15.435 5.85955 15.6628 6.08736Z"
                                              fill="white"/>
                                    </svg>
                                @else
                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="14" cy="13.5" r="9" stroke="#FED000"/>
                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="#FED000"
                                                stroke-width="4"/>
                                        <circle cx="14" cy="13.5" r="3.5" fill="#FED000"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </li>

                @if($totalActiveLevels == 3)
                    <!--Third item-->
                        <li class="w-[4.5rem] flex-auto">
                            <div
                                class="flex items-center pr-2 leading-[1.3rem] no-underline before:mr-2 before:h-3px before:w-full before:flex-1 before:bg-[#e0e0e0] before:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:before:bg-neutral-600 dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">
                                <div>
                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="14" cy="13.5" r="9" stroke="#FED000"/>
                                        <circle opacity="0.4" cx="14" cy="13.5" r="11.5" stroke="#FED000"
                                                stroke-width="4"/>
                                        <circle cx="14" cy="13.5" r="3.5" fill="#FED000"/>
                                    </svg>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
@endif


