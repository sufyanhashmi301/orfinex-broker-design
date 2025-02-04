<a href="https://localhost/prop-project/clinic/user/8/edit" class="flex">
  <span class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
      @if(null != $user->avatar)
          <img  class="w-full h-full rounded-[100%] object-cover" src="{{ asset($user->avatar)}}" alt="">
      @else
          {{ $user->first_name[0] }}{{ $user->last_name[0] }}
      @endif
  </span>
  <div>
      <span class="text-sm text-slate-900 dark:text-white block capitalize">
          {{ $user->first_name }} {{ $user->last_name }}
      </span>
      <span class="text-xs text-slate-500 dark:text-slate-300">
          {{ $user->email }}
      </span>
  </div>
</a>