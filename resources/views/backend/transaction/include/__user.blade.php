@php
    $user = \App\Models\User::find($user_id);
@endphp

<a class="link" href="{{ route('admin.user.edit',$user->id) }}"><span class="d-block lh-1 mb-2">{{ safe($user->username) }}</span><span class="d-block lh-1">{{$user->email}}</span></a>
