<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    {{-- 2 Factor Authentication --}}
    @include('frontend::user.setting.include.__two_fa')
    <div class="space-y-5">
        @if(setting('kyc_verification','permission'))
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('KYC') }}</h4>
                </div>
                <div class="card-body p-6">

                    @if($user->kyc == \App\Enums\KYCStatus::Verified->value)
                        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize rounded-3xl">
                            {{ __('KYC Verified') }}
                        </div>
                        <p class="mt-3">{{ json_decode($user->kyc_credential,true)['Action Message'] ?? '' }}</p>
                    @else
                        <a href="{{ route('user.kyc') }}" class="btn btn-dark">{{ __('Upload KYC') }}</a>
                        <p class="mt-3">{{ json_decode($user?->kyc_credential,true)['Action Message'] ?? '' }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Change Password') }}</h4>
            </div>
            <div class="card-body p-6">
                <a href="{{ route('user.change.password') }}" class="btn btn-dark">
                    {{ __('Change Password') }}
                </a>
            </div>
        </div>
    </div>
</div>