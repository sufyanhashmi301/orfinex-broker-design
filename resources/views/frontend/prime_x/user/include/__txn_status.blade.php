@switch($status)
    @case('pending')
        <div class="site-badge warnning">{{ __('Pending') }}</div>
    @break

    @case('success')
        <div class="site-badge success">{{ __('Success') }}</div>
    @break

    @case('failed')
        <div class="site-badge primary-bg">{{ __('canceled') }}</div>
    @break

    @case('expired')
        <div class="site-badge" style="background-color: #64748b; color: white;">{{ __('Expired') }}</div>
    @break
@endswitch
