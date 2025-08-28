@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Payment Deposit Form') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Add New Payment Deposit Form') }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('admin.payment-deposit-form.index') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{route('admin.payment-deposit.save.form')}}" method="post" class="space-y-4">
                    @csrf
                    <div class="input-area">
                        <label class="form-label" for="">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the name of the Payment Deposit form">
                                {{ __('Name') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Payment Deposit Form Name"/>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <a href="javascript:void(0)" id="generate" class="btn btn-dark btn-sm inline-flex items-center mb-3">
                            {{ __('Add Field option') }}
                        </a>
                    </div>
                    <div class="addOptions space-y-4">

                    </div>
                    <div class="max-w-xs">
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable or disable this Payment Deposit form">
                                        {{ __('Status') }}
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
                    <div class="text-right">
                        <button type="submit" class="btn btn-dark">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function (e) {
            var i = 0;
            "use strict";

            $("#generate").on('click', function () {
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

            $(document).on('focus', '.form-control', function () {
                $(this).closest('.option-remove-row').find('.delete-option').prop('disabled', false);
            });

            $(document).on('click', '.delete_field', function () {
                $(this).closest('.option-remove-row').remove();
            });
        });
    </script>
@endsection
