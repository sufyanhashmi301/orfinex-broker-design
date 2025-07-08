<div class="bg-slate-50 dark:bg-slate-900 rounded p-4 flex flex-col gap-4">
    <div class="text-lg font-medium dark:text-white text-slate-900">
        {{ __('Incoming Transactions') }}
    </div>
    <div class="grid md:grid-cols-4 grid-cols-2 gap-3">
        @foreach($summary['incoming'] as $item)
            <div class="">
                <h4 class="text-slate-600 dark:text-slate-200 text-xs font-normal">
                    {{ $item['type'] }}
                </h4>
                <div class="text-sm font-medium text-slate-900 dark:text-white">
                    {{ number_format($item['total'], 2) }}
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-lg font-medium dark:text-white text-slate-900">
        {{ __('Outgoing Transactions') }}
    </div>
    <div class="grid md:grid-cols-4 grid-cols-2 gap-3">
        @foreach($summary['outgoing'] as $item)
            <div class="">
                <h4 class="text-slate-600 dark:text-slate-200 text-xs font-normal">
                    {{ $item['type'] }}
                </h4>
                <div class="text-sm font-medium text-slate-900 dark:text-white">
                    {{ number_format($item['total'], 2) }}
                </div>
            </div>
        @endforeach
    </div>
</div>
