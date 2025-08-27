@props([
    'timeout' => config('notify.timeout') ?? 4000,
])

@if (session()->has('notify.message'))
    <div class="notify fixed inset-0 flex items-end justify-end px-4 py-6 pointer-events-none sm:p-6 sm:justify-end" aria-live="assertive" style="z-index:9999">
        <div 
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, {{ $timeout }})"
            x-show="show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @class([
                'pointer-events-auto w-full max-w-sm overflow-hidden shadow-lg rounded-lg border-l-4',
                'bg-white text-gray-900' => config('notify.theme') === 'light',
                'bg-slate-800 text-white' => config('notify.theme') !== 'light',
                'border-success-500' => session('notify.type') === 'success',
                'border-warning-500' => session('notify.type') === 'warning',
                'border-info-500' => session('notify.type') === 'info',
                'border-error-500' => session('notify.type') === 'error',
            ])
        >
            <div class="relative rounded-lg shadow-xs overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        @switch(session('notify.type'))
                            @case('success')
                                <svg class="h-6 w-6 text-success-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @break
                            @case('warning')
                                <svg class="w-6 h-6 text-warning-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                                @break
                            @case('info')
                                <svg class="w-6 h-6 text-info-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                @break
                            @case('error')
                                <svg class="w-6 h-6 text-error-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @break
                        @endswitch
                        <div class="ml-4 w-0 flex-1">
                            <p @class([
                                'text-sm leading-5 font-medium capitalize',
                                'text-slate-900' => config('notify.theme') === 'light',
                                'text-white' => config('notify.theme') !== 'light',
                            ])>
                                {{ session()->get('notify.title') ?? session()->get('notify.type') }}
                            </p>
                            <p @class([
                                    'mt-1 text-sm leading-5',
                                    'text-slate-500' => config('notify.theme') === 'light',
                                    'text-white' => config('notify.theme') !== 'light',
                                ])>
                                {{ session()->get('notify.message') }}
                            </p>
                        </div>
                        <div class="ml-4 flex shrink-0">
                            <button @click="show = false;" class="inline-flex rounded-md text-slate-400 hover:text-slate-500 focus:outline-none">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
