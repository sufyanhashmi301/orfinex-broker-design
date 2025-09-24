<div class="text-center">
    @if ($request->rejection_reason)
        <div class="text-sm text-slate-600 dark:text-slate-300">
            {{ $request->rejection_reason }}
        </div>
    @else
        <div class="text-xs text-slate-500 dark:text-slate-400 italic">
            {{ __('No reason provided') }}
        </div>
    @endif
</div>
