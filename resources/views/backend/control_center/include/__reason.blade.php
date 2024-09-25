@if($reason == 0)
    {{ __('Client Terminal') }}
@elseif($reason == 1)
    {{ __('Expert Advisor') }}
@elseif($reason == 2)
    {{ __('Manger Terminal') }}
@elseif($reason == 10)
    {{ __('Signal') }}
@elseif($reason == 16)
    {{ __('Mobile App') }}
@elseif($reason == 17)
    {{ __('Web Terminal') }}
@elseif($reason == 18)
    {{ __('Unknown') }}
@endif
