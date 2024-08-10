@if( $status == 'open')
    <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">
        {{ __('Open') }}
    </div>
@elseif($status == 'closed')
    <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
        {{ __('Completed') }}
    </div>
@endif