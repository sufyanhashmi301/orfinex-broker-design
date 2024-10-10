@if ($paginator->hasPages())
    <nav class="flex justify-end items-center p-3">
        <ul class="flex list-none @if($isMobile) pagination-sm @endif">

            @if ($paginator->onFirstPage())
                <li class="inline-block disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px]" aria-hidden="true">
                        <iconify-icon icon="ic:round-keyboard-arrow-left"></iconify-icon>
                    </span>
                </li>
            @else
                <li class="inline-block">
                    <a class="loaderBtn flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px]" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('previous') }}">
                        <iconify-icon icon="ic:round-keyboard-arrow-left"></iconify-icon>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="inline-block disabled" aria-disabled="true">
                        <span class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px]">
                            {{ __($element) }}
                        </span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="inline-block" aria-current="page">
                                <span class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px] p-active">
                                    {{ __($page) }}
                                </span>
                            </li>
                        @else
                            <li class="inline-block">
                                <a class="loaderBtn flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px]" href="{{ $url }}">
                                    {{ __($page) }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="inline-block">
                    <a class="loaderBtn flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px]" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                        <iconify-icon icon="ic:round-keyboard-arrow-right"></iconify-icon>
                    </a>
                </li>
            @else
                <li class="inline-block disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800 dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all duration-300 relative top-[2px]" aria-hidden="true">
                        <iconify-icon icon="ic:round-keyboard-arrow-right"></iconify-icon>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
