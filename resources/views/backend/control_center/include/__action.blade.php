@if($action == 0)
    <span class="badge bg-success-500 text-white capitalize">{{ __('Buy') }}</span>
@else
    <span class="badge bg-danger-500 text-white capitalize">{{ __('Sell') }}</span>
@endif
