<div class="bg-slate-50 dark:bg-slate-900 rounded p-4">
    <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ $row['desc'] }}">
            {{ $row['type'] }}
            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px] ml-1"></iconify-icon>
        </span>
    </div>
    <div class="text-slate-900 dark:text-white text-lg font-medium">
        {{ $currencySymbol.$row['total'] }}
    </div>
    <ul class="min-w-[184px] mt-4 flex justify-between flex-wrap items-center text-center gap-4">
        <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
            <span class="bg-success-500 ring-success-500 inline-flex h-[6px] w-[6px] bg-success-500 ring-opacity-25 rounded-full ring-4"></span>
            <span class="ml-2">{{ __('Completed:') }}</span>
            <span>{{ $row['success'].$currencySymbol }}</span>
        </li>
        <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
            <span class="bg-warning-500 ring-warning-500 inline-flex h-[6px] w-[6px] bg-warning-500 ring-opacity-25 rounded-full ring-4"></span>
            <span class="ml-2">{{ __('Pending:') }}</span>
            <span>{{ $row['pending'].$currencySymbol }}</span>
        </li>
        <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
            <span class="bg-danger-500 ring-danger-500 inline-flex h-[6px] w-[6px] bg-danger-500 ring-opacity-25 rounded-full ring-4"></span>
            <span class="ml-2">{{ __('Rejected:') }}</span>
            <span>{{ $row['rejected'].$currencySymbol }}</span>
        </li>
    </ul>
</div>
