@if($ib_status == \App\Enums\IBStatus::UNPROCESSED)
    <div class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">{{ __('Unprocessed') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::PENDING)
    <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">{{ __('Pending') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::APPROVED)
    <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Approved') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::REJECTED)
    <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Rejected') }}</div>
@endif
