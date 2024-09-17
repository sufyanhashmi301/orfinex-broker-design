@extends('backend.setting.misc.index')
@section('title')
    {{ __('Edit language') }}
@endsection
@section('misc-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Edit  language') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.language.index') }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.language.update',$language->id) }}" method="post" class="space-y-5">
                @method('PUT')
                @csrf
                <div class="input-area grid grid-cols-12 gap-5">
                    <label for="" class="md:col-span-3 col-span-12 form-label">{{ __('Language Name:') }}</label>
                    <div class="md:col-span-9 col-span-12">
                        <input type="text" class="form-control" required name="name" @if($language->locale == 'en') readonly @endif
                        value="{{ $language->name }}"/>
                    </div>
                </div>
                <div class="input-area grid grid-cols-12 gap-5">
                    <label for="" class="md:col-span-3 col-span-12 form-label">{{ __('Language Code:') }}</label>
                    <div class="md:col-span-9 col-span-12">
                        <input type="text" class="form-control" name="code" value="{{ $language->locale }}" @if($language->locale == 'en') readonly @endif
                        placeholder="Eg: en" required/>
                    </div>
                </div>

                <div class="input-area grid grid-cols-12 gap-5">
                    <label for="" class="md:col-span-3 col-span-12 form-label">{{ __('Default Language:') }}</label>
                    <div class="md:col-span-9 col-span-12">
                        <div class="site-input-groups max-w-xs">
                            <div class="switch-field flex overflow-hidden mb-0">
                                <input
                                    type="radio"
                                    id="language-default"
                                    name="is_default"
                                    value="1"
                                    @if($language->is_default) checked @endif
                                />
                                <label for="language-default">{{ __('Yes') }}</label>
                                <input
                                    type="radio"
                                    id="language-default-no"
                                    name="is_default"
                                    value="0"
                                    @if(!$language->is_default) checked @endif
                                />
                                <label for="language-default-no">{{ __('No') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-area grid grid-cols-12 gap-5">
                    <label for="" class="md:col-span-3 col-span-12 form-label">{{ __('Language Status:') }}</label>
                    <div class="md:col-span-9 col-span-12">
                        <div class="site-input-groups max-w-xs">
                            <div class="switch-field flex overflow-hidden mb-0">
                                <input
                                    type="radio"
                                    id="language-status"
                                    name="status"
                                    value="1"
                                    @if($language->status) checked @endif
                                />
                                <label for="language-status">{{ __('Active') }}</label>
                                <input
                                    type="radio"
                                    id="language-status-no"
                                    name="status"
                                    value="0"
                                    @if(!$language->status) checked @endif

                                />
                                <label for="language-status-no">{{ __('DeActive') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-dark inline-flex items-center">
                        {{ __('Update Language') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

