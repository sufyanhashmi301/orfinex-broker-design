@switch($ib_status)
    @case('pending')
    <div class="badge badge-warning capitalize">
        {{ __('Pending') }}
    </div>
    @break
    @case('approved')
    <div class="badge badge-success capitalize">
        {{ __('Approved') }}
    </div>
    @break
    @case('unknown')
    <div class="badge badge-danger capitalize">
        {{ __('Unknown') }}
    </div>
    @break
    @case('failed')
    <div class="badge badge-danger capitalize">
        {{ __('Failed') }}
    </div>
    @break
@endswitch
