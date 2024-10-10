@switch($ib_status)
    @case('pending')
    <div class="badge bg-warning text-warning bg-opacity-30 capitalize">
        {{ __('Pending') }}
    </div>
    @break
    @case('approved')
    <div class="badge bg-success text-success bg-opacity-30 capitalize">
        {{ __('Approved') }}
    </div>
    @break
    @case('unknown')
    <div class="badge bg-danger text-danger bg-opacity-30 capitalize">
        {{ __('Unknown') }}
    </div>
    @break
    @case('failed')
    <div class="badge bg-danger text-danger bg-opacity-30 capitalize">
        {{ __('Failed') }}
    </div>
    @break
@endswitch
