@foreach ($pipeline->stages as $stage)
    <div class="w-[320px] flex-none rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
        <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px]" style="background-color: {{ $stage->label_color }}"></span>
            <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                {{ $stage->name }}
            </h3>
        </div>

        <div id="{{ __('stage-container__').$stage->slug }}" class="min-h-full" data-stage-id="{{ $stage->id }}" data-stage-slug="{{ $stage->slug }}">
            @if ($stage->deals->isEmpty())
                <div class="p-2">
                    <a href="javascript:;" data-pipeline-id="{{ $pipeline->id }}" class="non-draggable w-full leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal px-2 py-5">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:plus"></iconify-icon>
                            <span>{{ __('Add Deal') }}</span>
                        </span>
                    </a>
                </div>
            @else
                @foreach ($stage->deals as $deal)
                    <div class="p-2 h-full space-y-4 rounded-bl rounded-br" data-deal-id="{{ $deal->id }}">
                        <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body p-3">
                            <div class="flex fle-wrap items-center justify-between gap-3 mb-3">
                                <a href="{{ route('admin.deal.show', $deal->id) }}" class="font-medium hover:underline">
                                    {{ $deal->name }}
                                </a>
                                <p class="text-slate-600 dark:text-slate-400 text-xs">
                                    {{ setting('site_currency', 'global').' '.$deal->value }}
                                </p>
                            </div>
                            <div class="flex items-center text-slate-600 dark:text-slate-400 text-sm">
                                <iconify-icon class="mr-1" icon="lucide:building-2"></iconify-icon>
                                {{ $deal->lead->first_name.' '.$deal->lead->last_name }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endforeach
