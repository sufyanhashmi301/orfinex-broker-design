@php
    $kycLevels = \App\Models\KycLevel::with('kyc_sub_levels')->where('status', true)->get();
    $totalActiveLevels = $kycLevels->count();
    $completedSteps = 0;
    if($totalActiveLevels > 0){
        if($user->kyc >= \App\Enums\KYCStatus::Level1->value){
            $completedSteps = 1;
        }
        if($user->kyc >= \App\Enums\KYCStatus::Level2->value){
            $completedSteps = 2;
        }
        if($user->kyc >= \App\Enums\KYCStatus::Level3->value){
            $completedSteps = 3;
        }
    }
@endphp
@if($totalActiveLevels > 0 && $user->kyc < \App\Enums\KYCStatus::Level2->value)
    <div 
        x-data="kycAlert()" 
        x-init="init()" 
        x-show="visible" 
        x-cloak
        class="kyc-alert border-gray-200 bg-white lg:border-b dark:border-gray-800 dark:bg-gray-900 p-3">
        <div class="flex items-center gap-3">
            <div class="flex item-center gap-3 mx-auto">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ __('Verify your account to unlock additional features.') }}
                </p>
                <x-text-link href="{{ route('user.kyc') }}" size="sm" variant="primary" icon="arrow-right" icon-position="right">
                    {{ __('Begin Verification') }}
                </x-text-link>
            </div>
            <x-text-link href="#" size="md" variant="error" icon="x" icon-position="right" @click.prevent="close()">
                <span class="sr-only">{{ __('Close') }}</span>
            </x-text-link>
        </div>
    </div>
@endif

<script>
    function kycAlert() {
        return {
            visible: false,
            init() {
                const closedAt = localStorage.getItem("kycAlertClosedTime");
                const now = Date.now();
                if (!closedAt || now - closedAt >= 24 * 60 * 60 * 1000) {
                    this.visible = true;
                }
            },
            close() {
                this.visible = false;
                localStorage.setItem("kycAlertClosedTime", Date.now());
            }
        }
    }
</script>
