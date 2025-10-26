@if($status == \App\Enums\ForexAccountStatus::Ongoing)
    <div class="badge badge-success capitalize">
        {{ __('Active') }}
    </div>
@elseif($status == \App\Enums\ForexAccountStatus::Archive)
    <div class="badge badge-danger capitalize">
        {{ __('Archive') }}
    </div>
@elseif($status == \App\Enums\ForexAccountStatus::Pending)
    <div class="badge badge-warning capitalize">
        {{ __('Pending') }}
    </div>
@elseif($status == \App\Enums\ForexAccountStatus::Canceled)
    <div class="badge badge-danger capitalize">
        {{ __('Rejected') }}
    </div>
@endif
