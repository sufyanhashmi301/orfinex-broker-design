@switch($status)
    @case(1)
        <span class="badge badge-success capitalize">{{ __('Active') }}</span>
        @break
    @case(2)
        <span class="badge badge-danger capitalize">{{ __('Inactive') }}</span>
        @break
@endswitch
