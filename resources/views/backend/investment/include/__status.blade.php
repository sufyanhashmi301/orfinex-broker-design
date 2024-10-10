@if($status == \App\Enums\ForexAccountStatus::Ongoing)
    <div class="badge bg-success text-success bg-opacity-30 capitalize">
        {{ __('Active') }}
    </div>
@elseif($status == \App\Enums\ForexAccountStatus::Archive)
    <div class="badge bg-danger text-danger bg-opacity-30 capitalize">
        {{ __('Archive') }}
    </div>
@endif
