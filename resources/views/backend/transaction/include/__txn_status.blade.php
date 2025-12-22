@switch($status)
    @case('pending')
        <div class="badge badge-warning capitalize">
            {{ __('Pending') }}
        </div>
    @break

    @case('success')
        <div class="badge badge-success capitalize">
            {{ __('Success') }}
        </div>
    @break

    @case('failed')
        <div class="badge badge-danger capitalize">
            {{ __('Cancelled') }}
        </div>
    @break

    @case('review')
        <div class="badge badge-secondary capitalize">
            {{ __('Review') }}
        </div>
    @break

    @case('expired')
        <div class="badge bg-slate-500 text-white capitalize">
            {{ __('Expired') }}
        </div>
    @break
@endswitch
