@switch($status)
    @case(1)
        <div class="badge bg-success text-success bg-opacity-30 capitalize">{{ __('Active') }}</div>
        @break
    @case(2)
        <div class="badge bg-danger text-danger bg-opacity-30 capitalize">{{ __('Inactive') }}</div>
        @break
@endswitch
