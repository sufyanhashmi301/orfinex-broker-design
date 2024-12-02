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
                <form action="{{ route('user.setting.communication.language') }}" method="POST">
                    @csrf
                    <input type="hidden" name="language" value="english">
                    <button type="submit" class="flex items-center justify-between w-full border border-slate-100 dark:border-slate-700 rounded p-4">
                        <p class="text-lg text-slate-900 dark:text-slate-50">
                            {{ __('English') }}
                        </p>
                        <div class="inline-flex items-center text-slate-500">
                            @if($selectedLanguage == 'english')
                                <span class="text-primary">{{ __('Selected') }}</span>
                                <iconify-icon class="text-primary text-xl ltr:ml-2 rtl:mr-2" icon="lucide:check"></iconify-icon>
                            @else
                                {{ __('Select') }}
                            @endif
                        </div>
                    </button>
                </form>

                <form action="{{ route('user.setting.communication.language') }}" method="POST">
                    @csrf
                    <input type="hidden" name="language" value="french">
                    <button type="submit" class="flex items-center justify-between w-full border border-slate-100 dark:border-slate-700 rounded p-4">
                        <p class="text-lg text-slate-900 dark:text-slate-50">
                            {{ __('French') }}
                        </p>
                        <div class="inline-flex items-center text-slate-500">
                            @if($selectedLanguage == 'french')
                                <span class="text-primary">{{ __('Selected') }}</span>
                                <iconify-icon class="text-primary text-xl ltr:ml-2 rtl:mr-2" icon="lucide:check"></iconify-icon>
                            @else
                                {{ __('Select') }}
                            @endif
                        </div>
                    </button>
                </form>

                <form action="{{ route('user.setting.communication.language') }}" method="POST">
                    @csrf
                    <input type="hidden" name="language" value="spanish">
                    <button type="submit" class="flex items-center justify-between w-full border border-slate-100 dark:border-slate-700 rounded p-4">
                        <p class="text-lg text-slate-900 dark:text-slate-50">
                            {{ __('Spanish') }}
                        </p>
                        <div class="inline-flex items-center text-slate-500">
                            @if($selectedLanguage == 'spanish')
                                <span class="text-primary">{{ __('Selected') }}</span>
                                <iconify-icon class="text-primary text-xl ltr:ml-2 rtl:mr-2" icon="lucide:check"></iconify-icon>
                            @else
                                {{ __('Select') }}
                            @endif
                        </div>
                    </button>
                </form>

                <form action="{{ route('user.setting.communication.language') }}" method="POST">
                    @csrf
                    <input type="hidden" name="language" value="chinese">
                    <button type="submit" class="flex items-center justify-between w-full border border-slate-100 dark:border-slate-700 rounded p-4">
                        <p class="text-lg text-slate-900 dark:text-slate-50">
                            {{ __('Chinese') }}
                        </p>
                        <div class="inline-flex items-center text-slate-500">
                            @if($selectedLanguage == 'chinese')
                                <span class="text-primary">{{ __('Selected') }}</span>
                                <iconify-icon class="text-primary text-xl ltr:ml-2 rtl:mr-2" icon="lucide:check"></iconify-icon>
                            @else
                                {{ __('Select') }}
                            @endif
                        </div>
                    </button>
                </form>

                <form action="{{ route('user.setting.communication.language') }}" method="POST">
                    @csrf
                    <input type="hidden" name="language" value="arabic">
                    <button type="submit" class="flex items-center justify-between w-full border border-slate-100 dark:border-slate-700 rounded p-4">
                        <p class="text-lg text-slate-900 dark:text-slate-50">
                            {{ __('Arabic') }}
                        </p>
                        <div class="inline-flex items-center text-slate-500">
                            @if($selectedLanguage == 'arabic')
                                <span class="text-primary">{{ __('Selected') }}</span>
                                <iconify-icon class="text-primary text-xl ltr:ml-2 rtl:mr-2" icon="lucide:check"></iconify-icon>
                            @else
                                {{ __('Select') }}
                            @endif
                        </div>
                    </button>
                </form>

                <form action="{{ route('user.setting.communication.language') }}" method="POST">
                    @csrf
                    <input type="hidden" name="language" value="hindi">
                    <button type="submit" class="flex items-center justify-between w-full border border-slate-100 dark:border-slate-700 rounded p-4">
                        <p class="text-lg text-slate-900 dark:text-slate-50">
                            {{ __('Hindi') }}
                        </p>
                        <div class="inline-flex items-center text-slate-500">
                            @if($selectedLanguage == 'hindi')
                                <span class="text-primary">{{ __('Selected') }}</span>
                                <iconify-icon class="text-primary text-xl ltr:ml-2 rtl:mr-2" icon="lucide:check"></iconify-icon>
                            @else
                                {{ __('Select') }}
                            @endif
                        </div>
                    </button>
                </form>

                <form action="{{ route('user.setting.communication.language') }}" method="POST">
                    @csrf
                    <input type="hidden" name="language" value="urdu">
                    <button type="submit" class="flex items-center justify-between w-full border border-slate-100 dark:border-slate-700 rounded p-4">
                        <p class="text-lg text-slate-900 dark:text-slate-50">
                            {{ __('Urdu') }}
                        </p>
                        <div class="inline-flex items-center text-slate-500">
                            @if($selectedLanguage == 'urdu')
                                <span class="text-primary">{{ __('Selected') }}</span>
                                <iconify-icon class="text-primary text-xl ltr:ml-2 rtl:mr-2" icon="lucide:check"></iconify-icon>
                            @else
                                {{ __('Select') }}
                            @endif
                        </div>
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection
