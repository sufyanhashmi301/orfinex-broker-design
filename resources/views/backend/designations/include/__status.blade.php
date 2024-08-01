@switch($status)
    @case(1)
        <div class="site-badge success">{{ __('Active') }}</div>
        @break
    @case(2)
        <div class="site-badge pending">{{ __('Inactive') }}</div>
        @break
@endswitch
