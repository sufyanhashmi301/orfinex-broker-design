@php
    $user = \App\Models\User::find($user_id);
@endphp

<a class="link-btn link-underline link-underline-opacity-0 text-dark" href="{{ route('admin.user.edit',$user->id) }}">
    <div class="d-flex align-items-center">
        <span class="avatar-text">NA</span>
        <div class="ms-2">
            <span class="d-block lh-1 mb-1 fw-bold">{{ safe($user->username) }}</span>
            <span class="d-block lh-1 small">{{$user->email}}</span>
        </div>
    </div>
</a>
