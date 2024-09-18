<div class="mobile-screen-show mb-5">
    <div class="user-kyc-mobile">
        @if($user->kyc == \App\Enums\KYCStatus::Pending->value)
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="material-symbols:fingerprint"></iconify-icon>
            {{ __('KYC Pending') }}
        @elseif($user->kyc != \App\Enums\KYCStatus::Level1->value)
            <i icon-name="fingerprint" class="kyc-star"></i>
            {{ __('Please Verify Your Identity') }} <a
                href="{{ route('user.kyc') }}">{{ __('Submit Now') }}</a>
        @endif
    </div>
</div>
