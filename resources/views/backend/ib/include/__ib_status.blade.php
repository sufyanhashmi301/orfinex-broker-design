@if($ib_status == \App\Enums\IBStatus::UNPROCESSED)
    <div class="badge bg-primary text-primary bg-opacity-30 capitalize">{{ __('Unprocessed') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::PENDING)
    <div class="badge bg-warning text-warning bg-opacity-30 capitalize">{{ __('Pending') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::APPROVED)
    <div class="badge bg-success text-success bg-opacity-30 capitalize">{{ __('Approved') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::REJECTED)
    <div class="badge bg-danger text-danger bg-opacity-30 capitalize">{{ __('Rejected') }}</div>
@endif
