@if($status == \App\Enums\ForexAccountStatus::Ongoing)
    <div class="badge badge-success capitalize">
        {{ __('Active') }}
    </div>
@elseif($status == \App\Enums\ForexAccountStatus::Archive)
    <div class="badge badge-danger capitalize">
        {{ __('Archive') }}
    </div>
@endif
