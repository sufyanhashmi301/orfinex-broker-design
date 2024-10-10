@if($action == 0)
    <span class="badge bg-success text-white capitalize">{{ __('Buy') }}</span>
@else
    <span class="badge bg-danger text-white capitalize">{{ __('Sell') }}</span>
@endif
