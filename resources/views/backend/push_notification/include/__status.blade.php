@if($status == 1)
    <div class="badge badge-success capitalize">
        {{ __('Active') }}
    </div>
@else
    <div class="badge badge-danger capitalize">
        {{ __('Deactivated') }}
    </div>
@endif
