@if($status == 1)
    <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
        {{ __('Active') }}
    </div>
@else
    <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
        {{ __('Deactivated') }}
    </div>
@endif
