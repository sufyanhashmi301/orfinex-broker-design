@php
    $balance=0;
    try {
        $account = DB::connection('mt5_db2')
                    ->table('mt5_accounts')
                    ->where('Login', $login)
                     ->first();
            if($account){
                $balance = $account->Balance;
            }
        }catch (\Exception $e) {
        $balance=0;
    }
@endphp
<strong
    class="green-color">{{ $balance.' '.$currency }}</strong>

{{--<a class="link-btn link-underline link-underline-opacity-0 text-dark" href="{{ route('admin.user.edit',$user->id) }}">--}}
{{--    <div class="d-flex align-items-center">--}}
{{--        <span class="avatar-text">NA</span>--}}
{{--        <div class="ms-2">--}}
{{--            <span class="d-block lh-1 mb-1 fw-bold">{{ safe($user->username) }}</span>--}}
{{--            <span class="d-block lh-1 small">{{$user->email}}</span>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</a>--}}
