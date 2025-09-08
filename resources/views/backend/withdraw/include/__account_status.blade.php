@switch($status)
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
    @case('rejected')
        <div class="badge badge-danger capitalize">
            {{ __('Rejected') }}
        </div>
        @break
    @default
        <div class="badge badge-secondary capitalize">
            {{ ucfirst($status) }}
        </div>
@endswitch 