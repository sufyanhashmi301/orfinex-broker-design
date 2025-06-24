<div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 place-content-center">
    @forelse($summary as $row)
        <div class="bg-slate-50 dark:bg-slate-900 rounded p-4">
            <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ $row['desc'] }}">
                    {{ $row['type'] }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </div>
            <div class="text-slate-900 dark:text-white text-lg font-medium">
                {{ $currencySymbol.$row['total'] }}
            </div>
            <ul class="min-w-[184px] mt-4 flex justify-between flex-wrap items-center text-center gap-4">
                <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
                    <span class="bg-success-500 ring-success-500 inline-flex h-[6px] w-[6px] bg-success-500 ring-opacity-25 rounded-full ring-4"></span>
                    <span class="ml-1">{{ __('Completed:') }}</span>
                    <span>{{ $row['success'].$currencySymbol }}</span>
                </li>
                <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
                    <span class="bg-warning-500 ring-warning-500 inline-flex h-[6px] w-[6px] bg-warning-500 ring-opacity-25 rounded-full ring-4"></span>
                    <span class="ml-1">{{ __('Pending:') }}</span>
                    <span>{{ $row['pending'].$currencySymbol }}</span>
                </li>
                <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
                    <span class="bg-danger-500 ring-danger-500 inline-flex h-[6px] w-[6px] bg-danger-500 ring-opacity-25 rounded-full ring-4"></span>
                    <span class="ml-1">{{ __('Rejected:') }}</span>
                    <span>{{ $row['rejected'].$currencySymbol }}</span>
                </li>
            </ul>
        </div>
    @empty
        <div class="flex-1 flex flex-col justify-center items-center gap-3">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.988 37.5417H26.0075" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                {{ __('No data found') }}
            </p>
        </div>
    @endforelse
</div>
