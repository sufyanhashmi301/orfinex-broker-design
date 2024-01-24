@if($status == \App\Enums\ForexAccountStatus::Ongoing)
    <div class="site-badge success">{{ __('Active') }}</div>
@elseif($status == \App\Enums\ForexAccountStatus::Archive)
    <div class="site-badge danger">{{ __('Archive') }}</div>
@endif
