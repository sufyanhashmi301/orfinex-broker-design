@switch($status)
    @case(1)
        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
        @break
    @case(2)
        <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Inactive') }}</div>
        @break
@endswitch
