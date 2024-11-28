@switch($status)
    @case(0)
        <div class="badge badge-danger capitalize">
            {{ __('InActive') }}
        </div>
        @break
    @case(1)
        <div class="badge badge-success capitalize">
            {{ __('Active') }}
        </div>
        @break
@endswitch
