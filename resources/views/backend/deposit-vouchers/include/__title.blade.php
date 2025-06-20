<div class="text-start">
    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap mb-[2px]">
        {{ $title }}
    </h4>
    @if($description)
        <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
            {{ Str::limit($description, 50) }}
        </div>
    @endif
</div>