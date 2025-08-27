@extends('frontend::user.setting.index')
@section('title')
    {{ __('Communication') }}
@endsection
@section('settings-content')
    <!-- Theme Section -->
    <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-5 mb-6">
        <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ __('Theme') }}
                </h3>
                <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                    {{ __("Select your preferred theme for the interface.") }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <form action="{{ route('user.setting.preference.theme') }}" method="POST">
                @csrf
                <input type="hidden" name="user_theme" value="light">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3V1m0 22v-2m9-9h2M1 12h2m15.363-6.364l1.414-1.414M4.222 19.778l1.414-1.414m0-12.728L4.222 4.222m15.556 15.556l-1.414-1.414M16 12a4 4 0 1 1-8 0a4 4 0 0 1 8 0"/>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('Light Mode') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('Default light interface') }}
                        </p>
                    </div>
                    <div class="flex-none ml-auto">
                        @if($activeTheme == 'light')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <i data-lucide="chevron-right" class="w-6 h-6"></i>
                            </div>
                        @endif
                    </div>
                </button>
            </form>
            <form action="{{ route('user.setting.preference.theme') }}" method="POST">
                @csrf
                <input type="hidden" name="user_theme" value="dark">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <path fill="currentColor" d="M12 21q-3.75 0-6.375-2.625T3 12t2.625-6.375T12 3q.35 0 .688.025t.662.075q-1.025.725-1.638 1.888T11.1 7.5q0 2.25 1.575 3.825T16.5 12.9q1.375 0 2.525-.613T20.9 10.65q.05.325.075.662T21 12q0 3.75-2.625 6.375T12 21"/>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('Dark Mode') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('Dark interface theme') }}
                        </p>
                    </div>
                    <div class="flex-none ms-auto">
                        @if($activeTheme == 'dark')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <i data-lucide="chevron-right" class="w-6 h-6 ltr:ml-2 rtl:mr-2"></i>
                            </div>
                        @endif
                    </div>
                </button>
            </form>
        </div>
    </div>

    <!-- Language Section -->
    <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-5">
        <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ __('Language') }}
                </h3>
                <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                    {{ __("Select your preferred language for the interface.") }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
            <form action="{{ route('user.setting.preference.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="english">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M5 8h14l-5 8h4l-5 8"/>
                                <path d="M2 2h20"/>
                            </g>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('English') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('English (US)') }}
                        </p>
                    </div>
                    <div class="flex-none ms-auto">
                        @if($selectedLanguage == 'english')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <i data-lucide="chevron-right" class="w-6 h-6 ltr:ml-2 rtl:mr-2"></i>
                            </div>
                        @endif
                    </div>
                </button>
            </form>

            <form action="{{ route('user.setting.preference.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="french">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M5 8h14l-5 8h4l-5 8"/>
                                <path d="M2 2h20"/>
                            </g>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('French') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('Français') }}
                        </p>
                    </div>
                    <div class="flex-none ms-auto">
                        @if($selectedLanguage == 'french')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <i data-lucide="chevron-right" class="w-6 h-6 ltr:ml-2 rtl:mr-2"></i>
                            </div>
                        @endif
                    </div>
                </button>
            </form>

            <form action="{{ route('user.setting.preference.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="spanish">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M5 8h14l-5 8h4l-5 8"/>
                                <path d="M2 2h20"/>
                            </g>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('Spanish') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('Español') }}
                        </p>
                    </div>
                    <div class="flex-none ms-auto">
                        @if($selectedLanguage == 'spanish')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <i data-lucide="chevron-right" class="w-6 h-6"></i>
                            </div>
                        @endif
                    </div>
                </button>
            </form>

            <form action="{{ route('user.setting.preference.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="chinese">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M5 8h14l-5 8h4l-5 8"/>
                                <path d="M2 2h20"/>
                            </g>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('Chinese') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('中文') }}
                        </p>
                    </div>
                    <div class="flex-none ms-auto">
                        @if($selectedLanguage == 'chinese')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <i data-lucide="chevron-right" class="w-6 h-6"></i>
                            </div>
                        @endif
                    </div>
                </button>
            </form>

            <form action="{{ route('user.setting.preference.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="arabic">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M5 8h14l-5 8h4l-5 8"/>
                                <path d="M2 2h20"/>
                            </g>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('Arabic') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('العربية') }}
                        </p>
                    </div>
                    <div class="flex-none ms-auto">
                        @if($selectedLanguage == 'arabic')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <i data-lucide="chevron-right" class="w-6 h-6"></i>
                            </div>
                        @endif
                    </div>
                </button>
            </form>

            <form action="{{ route('user.setting.preference.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="hindi">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M5 8h14l-5 8h4l-5 8"/>
                                <path d="M2 2h20"/>
                            </g>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('Hindi') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('हिन्दी') }}
                        </p>
                    </div>
                    <div class="flex-none ms-auto">
                        @if($selectedLanguage == 'hindi')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </div>
                        @endif
                    </div>
                </button>
            </form>

            <form action="{{ route('user.setting.preference.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="urdu">
                <button type="submit" class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800 w-full hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M5 8h14l-5 8h4l-5 8"/>
                                <path d="M2 2h20"/>
                            </g>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="mb-2 text-gray-800 dark:text-white/90">
                            {{ __('Urdu') }}
                        </h3>
                        <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                            {{ __('اردو') }}
                        </p>
                    </div>
                    <div class="flex-none ms-auto">
                        @if($selectedLanguage == 'urdu')
                            <div class="inline-flex items-center text-brand-600 dark:text-brand-400">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm dark:text-slate-300">
                                <i data-lucide="chevron-right" class="w-6 h-6"></i>
                            </div>
                        @endif
                    </div>
                </button>
            </form>
        </div>
    </div>
@endsection
