@if($staff->isNotEmpty())
    {{ $staff->pluck('full_name')->implode(', ') }}
@else
    N/A
@endif
