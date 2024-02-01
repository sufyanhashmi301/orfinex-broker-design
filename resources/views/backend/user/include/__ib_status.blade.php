@switch($ib_status)
    @case('pending')
    <div class="site-badge pending">{{ __('Pending') }}</div>
    @break
    @case('approved')
    <div class="site-badge success">{{ __('Approved') }}</div>
    @break
    @case('unknown')
    <div class="site-badge danger">{{ __('Unknown') }}</div>
    @break
    @case('failed')
    <div class="site-badge danger">{{ __('Failed') }}</div>
    @break
@endswitch
