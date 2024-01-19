@php
    $user = \App\Models\User::find($user_id);
@endphp
<a class="link" href="{{ route('admin.user.edit',$user->id) }}">{{ safe($user->username) }}<br><span>{{$user->email}}</span></a>
