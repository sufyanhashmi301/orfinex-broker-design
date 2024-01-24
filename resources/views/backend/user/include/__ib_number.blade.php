@php
    $user = \App\Models\User::find($user_id);
@endphp
@if(isset($user->ib_login))
    <div class="">{{ $user->ib_login }}</div>
@else
    <div class="">{{ __('NA') }}</div>
@endif
