@extends('backend.layouts.app')
@section('title')
    {{ __('Add new language') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Add new language') }}</h4>
                <div class="card-header-links">
                    <a href="{{ route('admin.language.index') }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                        {{ __('Back') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-6">
                <form action="{{ route('admin.language.store') }}" method="post" class="space-y-5">
                    @csrf
                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="md:col-span-3 col-span-12 form-label">{{ __('Language Name:') }}</label>
                        <div class="md:col-span-9 col-span-12">
                            <input type="text" class="form-control" required name="name"/>
                        </div>
                    </div>
                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="md:col-span-3 col-span-12 form-label">{{ __('Language Code:') }}</label>
                        <div class="md:col-span-9 col-span-12">
                            <input type="text" class="form-control" name="code" placeholder="Eg: en" required/>
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
                                    />
                                    <label for="language-default">{{ __('Yes') }}</label>
                                    <input
                                        type="radio"
                                        id="language-default-no"
                                        name="is_default"
                                        value="0"
                                        checked=""
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
                                        checked=""
                                    />
                                    <label for="language-status">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="language-status-no"
                                        name="status"
                                        value="0"

                                    />
                                    <label for="language-status-no">{{ __('DeActive') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center">
                            {{ __('Save Language') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection