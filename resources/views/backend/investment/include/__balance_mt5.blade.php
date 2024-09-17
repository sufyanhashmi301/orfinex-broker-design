@php
        $balance=0;
        $cacheKey = 'mt5_db_connection_status';

        // Check if the database is marked as unavailable
        if (Cache::has($cacheKey) && Cache::get($cacheKey) === 'down') {
            // Return 0 immediately without attempting to connect
            return 0;
        }

        // Attempt to establish a database connection
        try {
            DB::connection('mt5_db')->getPdo();
        } catch (\PDOException $e) {
            \Log::error('MT5 DB connection failed: ' . $e->getMessage());
            Cache::put($cacheKey, 'down', now()->addMinutes(5)); // Adjust the duration as needed
            return 0;
        }
try {
        $account = DB::connection('mt5_db')
                    ->table('mt5_accounts')
                    ->where('Login', $login)
                    ->first();
        if($account){
            $balance = $account->Balance;
        }
        } catch (\Exception $e) {
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
