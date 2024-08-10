@if($status == \App\Enums\ForexAccountStatus::Ongoing)
    <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
        {{ __('Active') }}
    </div>
@elseif($status == \App\Enums\ForexAccountStatus::Archive)
    <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
        {{ __('Archive') }}
    </div>
@endif
