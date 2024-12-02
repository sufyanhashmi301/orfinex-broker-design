@switch($kyc)
    @case(4)
        <div class="badge badge-success">{{ __('Verified') }}</div>
        @break
    @case(2)
        <div class="badge badge-warning">{{ __('Pending') }}</div>
        @break
    @case(3)
        <div class="badge badge-danger">{{ __('Rejected') }}</div>
        @break
@endswitch
