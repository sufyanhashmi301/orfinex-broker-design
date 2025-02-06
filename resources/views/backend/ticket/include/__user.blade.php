@php
    $user = \App\Models\User::find($user_id)
@endphp
<a href="{{ route('admin.user.edit',$user->id) }}" class="link"> {{ $user->first_name.' '.$user->last_name }}</a>
