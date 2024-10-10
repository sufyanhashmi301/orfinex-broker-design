@if($status == 1)
    <div class="badge bg-success text-success bg-opacity-30 capitalize">
        {{ __('Active') }}
    </div>
@else
    <div class="badge bg-danger text-danger bg-opacity-30 capitalize">
        {{ __('Deactivated') }}
    </div>
@endif
