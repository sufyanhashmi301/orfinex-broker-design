@if($ib_status == \App\Enums\IBStatus::UNPROCESSED)
    <div class="site-badge primary">{{ __('Unprocessed') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::PENDING)
    <div class="site-badge pending">{{ __('Pending') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::APPROVED)
    <div class="site-badge success">{{ __('Approved') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::REJECTED)
    <div class="site-badge danger">{{ __('Rejected') }}</div>
@endif
