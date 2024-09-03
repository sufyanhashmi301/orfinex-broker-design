@php
    $user = \App\Models\User::find($user_id)
@endphp
<div class="flex items-center">
    <div class="flex-none">
      <div class="w-10 h-10 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
            <iconify-icon class="text-xl" icon="lucide:megaphone"></iconify-icon>
      </div>
    </div>
    <div class="flex-1 text-start">
        <h4 class="text-base font-medium text-slate-600 whitespace-nowrap mb-1">
            {{ $title }}
        </h4>
        <div class="flex items-center text-xs font-normal text-slate-600 dark:text-slate-400 space-x-3">
            <span>#{{ $uuid }} </span>
            <a href="{{ route('admin.user.edit',$user->id) }}" class="link"> {{ $user->username }}</a>
        </div>
    </div>
</div>
