@switch($status)
    @case('pending')
        <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">
            {{ __('Pending') }}
        </div>
        @break
    @case('success')
        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
            {{ __('Success') }}
        </div>
        @break
    @case('failed')
        <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
            {{ __('Cancelled') }}
        </div>
        @break
@endswitch
