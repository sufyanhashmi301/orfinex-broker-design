@php
    $kyclevels = \App\Models\Kyclevel::get();
    $activeKyclevels = $kyclevels->where('status', 1); 
@endphp

@if($activeKyclevels->count() == 3)
    @if($user->kyc != \App\Enums\KYCStatus::Verified->value || !$user->is_level_2_completed || !$user->is_level_3_completed)
        <div class="alert alert-dismissible py-[18px] px-6 font-normal text-sm rounded-md border mb-5" style="background-color: rgba(254, 208, 0, 0.3); border-color: #FED000;" role="alert">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <iconify-icon class="text-2xl flex-0 text-danger-500" icon="typcn:warning"></iconify-icon>
                <p class="flex-1 font-Inter text-slate-900 dark:text-white">
                    @if($user->kyc == \App\Enums\KYCStatus::Pending->value)
                        <strong>{{ __('KYC Pending') }}</strong>
                    @else
                        {{ __('You need to submit your') }}
                        <strong>{{ __('KYC and Other Documents') }}</strong> {{ __('before proceed to the system.') }}
                    @endif
                </p>
                <div class="flex-0 text-xl cursor-pointer text-danger-500">
                    @if($user->kyc != \App\Enums\KYCStatus::Pending->value)
                        <a href="{{ route('user.kyc') }}" class="btn inline-flex justify-center btn-dark btn-sm">
                            <span>{{ __('Submit Now') }}</span>
                        </a>
                        <a href="" class="btn inline-flex justify-center btn-outline-dark btn-sm" type="button" data-bs-dismiss="alert" aria-label="Close">
                            <span>{{ __('Later') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
@elseif($activeKyclevels->contains('status', 0))
    @php
        $skipLevels = $activeKyclevels->filter(function($level) {
            return $level->status == 0;
        });
    @endphp

    @if($skipLevels->count() < 3)
        @if($user->kyc != \App\Enums\KYCStatus::Verified->value || 
            ($skipLevels->contains('level', 2) ? false : !$user->is_level_2_completed) || 
            ($skipLevels->contains('level', 3) ? false : !$user->is_level_3_completed))
            <div class="alert alert-dismissible py-[18px] px-6 font-normal text-sm rounded-md border mb-5" style="background-color: rgba(254, 208, 0, 0.3); border-color: #FED000;" role="alert">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <iconify-icon class="text-2xl flex-0 text-danger-500" icon="typcn:warning"></iconify-icon>
                    <p class="flex-1 font-Inter text-slate-900 dark:text-white">
                        @if($user->kyc == \App\Enums\KYCStatus::Pending->value)
                            <strong>{{ __('KYC Pending') }}</strong>
                        @else
                            {{ __('You need to submit your') }}
                            <strong>{{ __('KYC and Other Documents') }}</strong> {{ __('before proceed to the system.') }}
                        @endif
                    </p>
                    <div class="flex-0 text-xl cursor-pointer text-danger-500">
                        @if($user->kyc != \App\Enums\KYCStatus::Pending->value)
                            <a href="{{ route('user.kyc') }}" class="btn inline-flex justify-center btn-dark btn-sm">
                                <span>{{ __('Submit Now') }}</span>
                            </a>
                            <a href="" class="btn inline-flex justify-center btn-outline-dark btn-sm" type="button" data-bs-dismiss="alert" aria-label="Close">
                                <span>{{ __('Later') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif
