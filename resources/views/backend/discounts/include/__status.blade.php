@switch($status)
    @case(1)
        <span class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</span>
        @break
    @case(0)
        <span class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Inactive') }}</span>
        @break
@endswitch
