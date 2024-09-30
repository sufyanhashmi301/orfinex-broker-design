@if($type === 'percentage')
    {{ $percentage }}%
@elseif($type === 'fixed')
    ${{ $fixed_amount }}
@endif
