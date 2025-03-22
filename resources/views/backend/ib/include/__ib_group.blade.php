@if($ib_status == \App\Enums\IBStatus::UNPROCESSED)
    <div class="badge bg-primary text-primary bg-opacity-30 capitalize">{{ __('Unprocessed') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::PENDING)
    <div class="badge badge-warning capitalize">{{ __('Pending') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::APPROVED)
    <div class="badge badge-success capitalize">{{ __('Approved') }}</div>
@elseif($ib_status == \App\Enums\IBStatus::REJECTED)
    <div class="badge badge-danger capitalize">{{ __('Rejected') }}</div>
@endif
