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
            {{ __('Failed') }}
        </div>
    @break

    @case('review')
        <div class="badge badge-info capitalize">
            {{ __('Review') }}
        </div>
    @break

    @case('none')
        <div class="badge badge-secondary capitalize">
            {{ __('None') }}
        </div>
    @break

    @default
        <div class="badge badge-secondary capitalize">
            {{ __(ucfirst($status)) }}
        </div>
    @break
@endswitch
