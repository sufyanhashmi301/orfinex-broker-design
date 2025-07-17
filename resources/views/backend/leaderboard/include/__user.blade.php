<a href="{{ route('admin.user.edit',$user->id) }}" class="flex">
    <div class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
        <img  class="w-full h-full rounded-[100%] object-cover" src="{{ getFilteredPath($user->avatar, 'fallback/user.png') }}" alt="">
    </div>
    <div>
        <span class="text-sm text-slate-900 dark:text-white block capitalize">
            {{ safe($user->full_name) }}
        </span>
        <span class="text-xs lowercase text-slate-500 dark:text-slate-300">
            {{$user->email}}
        </span>
    </div>
</a>