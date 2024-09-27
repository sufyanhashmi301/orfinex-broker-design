@php
    $kycLevels = \App\Models\KycLevel::with('kyc_sub_levels')->where('status', true)->get();
    $totalActiveLevels = $kycLevels->count();
    $completedSteps = 0;
    if($totalActiveLevels > 0){
    if($user->email_verified_at != null)
        $completedSteps++;

    if($user->is_level_2_completed != null || $user->kyc ==1)
        $completedSteps++;
    }
@endphp
@if($totalActiveLevels > 0 && $completedSteps < $totalActiveLevels)
    <div class="alert alert-dismissible bg-warning-500 bg-opacity-[14%] text-warning-500 py-[18px] px-6 font-normal text-sm rounded-md border border-warning-500 mb-3" role="alert">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex-1 flex items-center space-x-3">
                <iconify-icon class="text-2xl flex-0" icon="typcn:warning"></iconify-icon>
                <div class="font-inter">
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
                    <a href="javascript:;" class="btn inline-flex justify-center btn-danger btn-sm" type="button" data-bs-dismiss="alert" aria-label="Close">
                        <span>{{ __('Later') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif


