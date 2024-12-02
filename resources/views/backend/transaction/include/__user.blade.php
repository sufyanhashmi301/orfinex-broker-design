@php
    $user = \App\Models\User::find($user_id);
@endphp
@if($user)
<a href="{{ route('admin.user.edit',$user->id) }}" class="flex">
    <span class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
        NA
    </span>
    <div>
        <span class="text-sm text-slate-900 dark:text-white block capitalize">
            {{ safe($user->username) }}
        </span>
        <span class="text-xs lowercase text-slate-500 dark:text-slate-300">
            {{$user->email}}
        </span>
    </div>
</a>
@endif
