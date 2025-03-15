@switch($status)
    @case(1)
        <div class="badge badge-success capitalize">{{ __('Active') }}</div>
        @break
    @case(2)
        <div class="badge badge-danger capitalize">{{ __('Inactive') }}</div>
        @break
@endswitch
