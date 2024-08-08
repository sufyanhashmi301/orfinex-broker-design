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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded p-4">
                    <p class="text-lg text-slate-900 dark:text-slate-50">
                        {{ __('English') }}
                    </p>
                    <a href="javascript:;" class="inline-flex items-center text-primary">
                        {{ __('Selected') }}
                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:check"></iconify-icon>
                    </a>
                </div>
                <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded p-4">
                    <p class="text-lg text-slate-900 dark:text-slate-50">
                        {{ __('Urdu') }}
                    </p>
                    <a href="javascript:;" class="inline-flex items-center text-slate-500">
                        {{ __('Select') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
