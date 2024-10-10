@switch($status)
    @case(0)
        <div class="badge bg-danger text-danger bg-opacity-30 capitalize">
            {{ __('InActive') }}
        </div>
        @break
    @case(1)
        <div class="badge bg-success text-success bg-opacity-30 capitalize">
            {{ __('Active') }}
        </div>
        @break
@endswitch
