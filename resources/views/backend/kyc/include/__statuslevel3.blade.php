@switch($kyc)
    @case(\App\Enums\KYCStatus::Level3->value)
        <div class="site-badge success">{{ __('Verified') }}</div>
        @break
    @case(\App\Enums\KYCStatus::PendingLevel3->value)
        <div class="site-badge pending">{{ __('Pending') }}</div>
        @break
    @case(\App\Enums\KYCStatus::RejectLevel3->value)
        <div class="site-badge danger">{{ __('Rejected') }}</div>
        @break
@endswitch
