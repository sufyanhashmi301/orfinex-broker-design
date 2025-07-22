@extends('backend.setting.misc.index')
@section('title')
    {{ __('Add new language') }}
@endsection
@section('misc-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Add new language') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.language.index') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.language.store') }}" method="post">
                @csrf
                <div class="space-y-5">
                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="md:col-span-3 col-span-12 form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the full name of the language to be added (e.g., English, Spanish, French)">
                                {{ __('Language Name') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="md:col-span-9 col-span-12">
                            <input type="text" class="form-control" required name="name" placeholder="e.g. Spanish"/>
                        </div>
                    </div>
                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="md:col-span-3 col-span-12 form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Provide the ISO 639-1 language code (e.g., en for English, es for Spanish)">
                                {{ __('Language Code') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="md:col-span-9 col-span-12">
                            <input type="text" class="form-control" name="code" placeholder="e.g. en, es, fr" required/>
                            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-6">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Toggle this on if this should be the system’s default language for all users">
                                            {{ __('Default Language') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="is_default">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_default" value="1" class="sr-only peer">
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Toggle to activate or deactivate this language in the application">
                                            {{ __('Language Status') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="status">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="status" value="1" class="sr-only peer">
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Save Language') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
