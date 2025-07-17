<div class="p-6">
    <div class="flex items-center mb-5">
        <div class="flex-none">
            <div class="h-[70px] w-[70px] rounded-[100%] border border-slate-200 dark:border-slate-700 ltr:mr-3 rtl:ml-3">
                <img src="{{ getFilteredPath($top1->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
            </div>
        </div>
        <div class="flex-1 text-start">
            <h4 class="text-lg font-semibold text-slate-600 whitespace-nowrap">
                {{ $top1->full_name }}
            </h4>
            <div class="text-sm font-normal text-slate-600 dark:text-slate-400">
                {{ $top1->email }}
            </div>
        </div>
    </div>
    @if (!empty($top1Details))
        <div class="bg-slate-50 dark:bg-slate-900 rounded p-4 flex flex-col gap-4">
            <div class="text-lg font-medium dark:text-white text-slate-900">
                {{ __('Incoming Payments') }}
            </div>
            <div class="grid md:grid-cols-3 grid-cols-2 gap-3 gap-y-5">
                @foreach ($top1Details['incoming'] as $type)
                    <div class="">
                        <h4 class="text-slate-600 dark:text-slate-200 text-xs font-normal">
                            {{ $type['type'] }}
                        </h4>
                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                            {{ $currencySymbol }}{{ number_format($type['total'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Outgoing Payments -->
            <div class="text-lg font-medium dark:text-white text-slate-900">
                {{ __('Outgoing Payments') }}
            </div>
            <div class="grid md:grid-cols-3 grid-cols-2 gap-3 gap-y-5">
                @foreach ($top1Details['outgoing'] as $type)
                    <div class="">
                        <h4 class="text-slate-600 dark:text-slate-200 text-xs font-normal">
                            {{ $type['type'] }}
                        </h4>
                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                            {{ $currencySymbol }}{{ number_format($type['total'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-muted">
            {{ __('No transaction data available for this user and their network.')}}
        </div>
    @endif
</div>