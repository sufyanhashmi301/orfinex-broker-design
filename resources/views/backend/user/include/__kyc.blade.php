@if($kyc >= kyc_required_completed_level())
    <span class="badge badge-success bg-opacity-30 text-success">{{ __('Verified') }}</span>
@else
    <span class="badge badge-danger bg-opacity-30 text-danger">{{ __('Unverified') }}</span>
@endif
