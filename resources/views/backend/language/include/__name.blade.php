<div class="flex items-center">
    <div class="flex-none">
        <div class="w-10 h-10 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
            <iconify-icon class="text-lg" icon="lucide:languages"></iconify-icon>
        </div>
    </div>
    <div class="flex-1 text-start">
        <div class="flex">
            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                {{ $name }}
            </h4>
            @if($is_default)
                <span class="badge bg-slate-900 text-white capitalize ml-2">
                    {{ __('Default') }}
                </span>
            @endif
        </div>
        <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
            {{$locale}}
        </div>
    </div>
</div>