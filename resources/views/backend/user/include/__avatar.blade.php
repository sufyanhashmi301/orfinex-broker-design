<div class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
    @if(null != $avatar)
        <img  class="w-full h-full rounded-[100%] object-cover" src="{{ asset($avatar)}}" alt="">
    @else
        {{ $first_name[0] }}{{ $last_name[0] }}
    @endif
</div>