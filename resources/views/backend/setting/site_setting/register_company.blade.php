@extends('backend.setting.site_setting.index')
@section('title')
    {{ __('Register Company') }}
@endsection
@section('site-setting-content')
    @php
        $fieldsJson = setting('company_form_fields', 'company_register');
        $fields = [];
        if (is_string($fieldsJson) && !empty($fieldsJson)) {
            $decoded = json_decode($fieldsJson, true);
            $fields = is_array($decoded) ? $decoded : [];
        }
    @endphp
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Register Company Form Builder') }}
            </h4>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.settings.update') }}" method="post" class="space-y-4">
                    @csrf
                    <input type="hidden" name="section" value="company_register" />

                    <div>
                        <a href="javascript:void(0)" id="generate" class="btn btn-dark btn-sm inline-flex items-center mb-3">
                            {{ __('Add Field option') }}
                        </a>
                    </div>

                    <div class="addOptions space-y-4">
                        @if($fields && is_array($fields))
                            @foreach($fields as $index => $field)
                                <div class="option-remove-row grid grid-cols-12 gap-5">
                                    <div class="xl:col-span-4 lg:col-span-6 col-span-12">
                                        <div class="input-area">
                                            <input name="fields[{{ $index }}][name]" class="form-control" type="text" value="{{ $field['name'] ?? '' }}" required placeholder="Field Name">
                                        </div>
                                    </div>

                                    <div class="xl:col-span-4 lg:col-span-6 col-span-12">
                                        <div class="input-area">
                                            <select name="fields[{{ $index }}][type]" class="form-control w-100 field-type">
                                                <option value="text" {{ ($field['type'] ?? '') === 'text' ? 'selected' : '' }}>Input Text</noption>
                                                <option value="date" {{ ($field['type'] ?? '') === 'date' ? 'selected' : '' }}>Date</option>
                                                <option value="checkbox" {{ ($field['type'] ?? '') === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                                <option value="radio" {{ ($field['type'] ?? '') === 'radio' ? 'selected' : '' }}>Radio</option>
                                                <option value="dropdown" {{ ($field['type'] ?? '') === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                                <option value="file" {{ ($field['type'] ?? '') === 'file' ? 'selected' : '' }}>File Upload</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="xl:col-span-4 lg:col-span-6 col-span-12 options-container" style="display: {{ in_array($field['type'] ?? '', ['checkbox', 'radio', 'dropdown']) ? 'block' : 'none' }};">
                                        <div class="input-area options space-y-2">
                                            @if(isset($field['options']) && is_array($field['options']))
                                                @foreach($field['options'] as $optionIndex => $option)
                                                    <div class="option-row flex items-center gap-2">
                                                        <input name="fields[{{ $index }}][options][]" class="form-control" type="text" value="{{ $option }}" placeholder="Option {{ $optionIndex + 1 }}">
                                                        <button type="button" class="action-btn px-1 add-option">
                                                            <iconify-icon icon="lucide:plus"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="action-btn px-1 delete-option">
                                                            <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="option-row flex items-center gap-2">
                                                    <input name="fields[{{ $index }}][options][]" class="form-control" type="text" value="" placeholder="Option 1">
                                                    <button type="button" class="action-btn px-1 add-option">
                                                        <iconify-icon icon="lucide:plus"></iconify-icon>
                                                    </button>
                                                    <button type="button" class="action-btn px-1 delete-option">
                                                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="xl:col-span-4 lg:col-span-6 col-span-12">
                                        <div class="flex justify-between items-end space-x-5">
                                            <div class="input-area w-full">
                                                <select name="fields[{{ $index }}][validation]" class="form-control">
                                                    <option value="required" {{ ($field['validation'] ?? '') === 'required' ? 'selected' : '' }}>Required</option>
                                                    <option value="nullable" {{ ($field['validation'] ?? '') === 'nullable' ? 'selected' : '' }}>Optional</option>
                                                </select>
                                            </div>
                                            <button type="button" class="delete_field inline-flex items-center justify-center h-9 w-10 bg-danger-500 text-lg border rounded border-danger-500 text-white rb-zeplin-focused">
                                                <iconify-icon icon="fluent:delete-20-regular"></iconify-icon>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="max-w-xs">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable or disable the company registration form">
                                            {{ __('Form Status') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="company_form_status">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="company_form_status" value="1" class="sr-only peer" {{ setting('company_form_status', 'company_register') ? 'checked' : '' }}>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="max-w-xs">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="If enabled, new company registrations will require admin approval">
                                            {{ __('Admin Approval Required') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="company_form_admin_approval">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="company_form_admin_approval" value="1" class="sr-only peer" {{ setting('company_form_admin_approval', 'company_register') ? 'checked' : '' }}>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-dark">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function($){
            $(document).ready(function () {
                var i = {{ count($fields ?? []) }};
                "use strict";

                $(document).on('click', '#generate', function () {
                    ++i;
                    var form = `<div class="option-remove-row grid grid-cols-12 gap-5">
                    <div class="xl:col-span-4 lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <input name="fields[${i}][name]" class="form-control" type="text" value="" required placeholder="Field Name">
                        </div>
                    </div>

                    <div class="xl:col-span-4 lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <select name="fields[${i}][type]" class="form-control w-100 field-type">
                                <option value="text">Input Text</option>
                                <option value="date">Date</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio</option>
                                <option value="dropdown">Dropdown</option>
                                <option value="file">File Upload</option>
                            </select>
                        </div>
                    </div>

                    <div class="xl:col-span-4 lg:col-span-6 col-span-12 options-container" style="display: none;">
                        <div class="input-area options space-y-2">
                            <div class="option-row flex items-center gap-2">
                                <input name="fields[${i}][options][]" class="form-control" type="text" value="" placeholder="Option 1">
                                <button type="button" class="action-btn px-1 add-option">
                                    <iconify-icon icon="lucide:plus"></iconify-icon>
                                </button>
                                <button type="button" class="action-btn px-1 delete-option">
                                    <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="xl:col-span-4 lg:col-span-6 col-span-12">
                        <div class="flex justify-between items-end space-x-5">
                            <div class="input-area w-full">
                                <select name="fields[${i}][validation]" class="form-control">
                                    <option value="required">Required</option>
                                    <option value="nullable">Optional</option>
                                </select>
                            </div>
                            <button type="button" class="delete_field inline-flex items-center justify-center h-9 w-10 bg-danger-500 text-lg border rounded border-danger-500 text-white rb-zeplin-focused">
                                <iconify-icon icon="fluent:delete-20-regular"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>`;
                    $('.addOptions').append(form);
                });

                $(document).on('click', '.delete-option', function () {
                    var optionRow = $(this).closest('.option-row');
                    var optionRows = optionRow.parent().find('.option-row');
                    if (optionRows.length > 1) {
                        optionRow.remove();
                    }
                });

                $(document).on('change', '.field-type', function () {
                    var selectedType = $(this).val();
                    var optionsContainer = $(this).closest('.option-remove-row').find('.options-container');

                    if (selectedType === 'checkbox' || selectedType === 'radio' || selectedType === 'dropdown') {
                        optionsContainer.show();
                    } else {
                        optionsContainer.hide();
                    }
                });

                $(document).on('click', '.add-option', function () {
                    var optionsContainer = $(this).closest('.options');
                    var optionRow = optionsContainer.find('.option-row:first').clone();
                    optionRow.find('.form-control').val('');
                    optionsContainer.append(optionRow);
                });

                $(document).on('click', '.delete_field', function () {
                    $(this).closest('.option-remove-row').remove();
                });
            });
        })(jQuery);
    </script>
@endsection


