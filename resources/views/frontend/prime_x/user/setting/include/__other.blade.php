<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="space-y-5">
        @if(setting('kyc_verification','permission'))
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('KYC') }}</h4>
                </div>
                <div class="card-body p-6">

                    @if($user->kyc == \App\Enums\KYCStatus::Level1->value)
                        <div class="badge bg-success text-success bg-opacity-30 capitalize rounded-3xl">
                            {{ __('KYC Verified') }}
                        </div>
                        <p class="mt-3">{{ json_decode($user->kyc_credential,true)['Action Message'] ?? __('') }}</p>
                    @else
                        <a href="{{ route('user.kyc') }}" class="btn btn-dark">{{ __('Upload KYC') }}</a>
                        <p class="mt-3">{{ json_decode($user?->kyc_credential,true)['Action Message'] ?? __('') }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
