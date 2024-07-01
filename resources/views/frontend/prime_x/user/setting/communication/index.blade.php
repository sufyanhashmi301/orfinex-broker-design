@extends('frontend::user.setting.index')
@section('title')
    {{ __('Communication') }}
@endsection
@section('settings-content')
    <div class="card">
        <div class="card-body p-6">
            <div class="mb-4">
                <h4 class="card-title mb-2">{{ __('Communication') }}</h4>
                <p class="block font-normal text-sm text-slate-500">
                    {{ __("Select your preferred language") }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                    <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                        <iconify-icon class="dark:text-slate-300" icon="system-uicons:translate"></iconify-icon>
                    </div>
                    <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                        English
                    </span>
                    <div class="mt-5">
                        <a href="javascript:;" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                            <span class="mr-1">Change</span>
                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-down"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
