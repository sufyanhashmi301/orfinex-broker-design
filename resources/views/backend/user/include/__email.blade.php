@php
    $user = \App\Models\User::find($id);
@endphp

<a href="{{ route('admin.user.edit',$user->id) }}" class="flex">

    <div>
        <span class="text-sm text-slate-900 dark:text-white block normal-case">
            {{ safe($user->email) }}
        </span>

    </div>
</a>

