@if($status == 1)
    <span class="badge badge-success bg-opacity-30 text-success">{{ __('Active') }}</span>
@else
    <span class="badge badge-danger bg-opacity-30 text-danger">{{ __('Inactive') }}</span>
@endif
