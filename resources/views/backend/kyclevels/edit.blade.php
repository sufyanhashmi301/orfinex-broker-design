@extends('backend.layouts.app')
@section('title')
{{ __('Update KYC Level') }}
@endsection
@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Update KYC Level') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center text-success-500">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.kyclevels.update', $kycLevel->id) }}" method="post" class="space-y-4">
                @csrf
                <div class="input-area">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-control" required="" name="name" value="{{ $kycLevel->name }}"/>
                </div>
                <div class="role-cat-items space-y-5">
                    @php
                        // Group settings by kyclevel and unique_code
                        $groupedSettings = $kycLevelSettings->groupBy(['kyclevel.name', 'unique_code']);
                    @endphp
                    @foreach($groupedSettings as $kyclevelName => $settingsByCode)
                        @php
                            $isExpanded = $kyclevelName == 'Level 2' && $settingsByCode->has('manual'); // Expand the "manual" section by default
                        @endphp
                        @if($kyclevelName == 'Level 1')
                            @foreach($settingsByCode->flatten() as $setting)
                                <div class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                                    <div class="gateway-name flex items-center gap-2">
                                        <div class="gateway-icon mr-1">
                                            <iconify-icon class="text-3xl" icon="mdi:id-card-outline"></iconify-icon>
                                        </div>
                                        <div class="gateway-title">
                                            <h4 class="text-base">
                                                {{ ucwords(str_replace('-', ' ', $setting->title)) }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="gateway-right flex items-center gap-2">
                                        <div class="gateway-status">
                                            @if($setting->status)
                                                <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                    {{ __('Active') }}
                                                </div>
                                            @else
                                                <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                    {{ __('Deactivated') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="gateway-edit">
                                            <a type="button" class="action-btn cursor-pointer editPlugin" data-id="1">
                                                <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('KYC Method') }}
                                </label>
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="success-radio">
                                        <label class="flex items-center cursor-pointer">
                                            <input 
                                                type="radio"
                                                class="hidden"
                                                name="level2_setting"
                                                value="manual"
                                                checked
                                            >
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-success-500 text-sm leading-6 capitalize">
                                                {{ __('Manual') }}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="success-radio">
                                        <label class="flex items-center cursor-pointer">
                                            <input 
                                                type="radio"
                                                class="hidden" 
                                                name="level2_setting" 
                                                value="automatic"
                                            >
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-success-500 text-sm leading-6 capitalize">
                                                {{ __('Automatic') }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @foreach($settingsByCode as $uniqueCode => $settings)
                                <div class="settings-wrapper @if($uniqueCode == 'manual') manual-settings @else automatic-settings @endif">
                                    <div class="space-y-5">
                                        @foreach($settings as $setting)
                                            @if($uniqueCode == 'manual')
                                                <div class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                                                    <div class="gateway-name flex items-center gap-2">
                                                        <div class="gateway-icon mr-1">
                                                            <iconify-icon class="text-3xl" icon="mdi:id-card-outline"></iconify-icon>
                                                        </div>
                                                        <div class="gateway-title">
                                                            <h4 class="text-base">
                                                                {{ ucwords(str_replace('-', ' ', $setting->title)) }}
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class="gateway-right flex items-center gap-2">
                                                        <div class="gateway-status">
                                                            @if($setting->status)
                                                                <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                                    {{ __('Active') }}
                                                                </div>
                                                            @else
                                                                <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                                    {{ __('Deactivated') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="gateway-edit">
                                                            <a type="button" class="action-btn cursor-pointer editPlugin" data-id="1">
                                                                <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                                                    <div class="gateway-name flex items-center gap-2">
                                                        <div class="gateway-icon mr-1">
                                                            <iconify-icon class="text-3xl" icon="mdi:id-card-outline"></iconify-icon>
                                                        </div>
                                                        <div class="gateway-title">
                                                            <h4 class="text-base">
                                                                {{ __('samsub') }}
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class="gateway-right flex items-center gap-2">
                                                        <div class="gateway-status">
                                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                                {{ __('Active') }}
                                                            </div>
                                                        </div>
                                                        <div class="gateway-edit">
                                                            <a type="button" class="action-btn cursor-pointer editPlugin" data-id="1">
                                                                <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @php
                            $isExpanded = false; // Collapse subsequent accordion items
                        @endphp
                    @endforeach
                </div>
                <div class="">
                    <button class="btn btn-dark inline-flex items-center justify-center mt-10" type="submit">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editPlugin" tabindex="-1" aria-labelledby="editPlugin" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="popup-body-text p-6 pt-5 edit-plugin-section">
                    <form action="{{ route('admin.kyc-form.store') }}" method="post" class="space-y-4">
                        @csrf
                        
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Name:') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    placeholder="KYC Type Name" required/>
                        </div>

                        <div>
                            <a href="javascript:void(0)" id="generate" class="btn btn-outline-dark btn-sm inline-flex items-center justify-center mb-3">
                                {{ __('Add Field option') }}
                            </a>
                        </div>
                        <div class="addOptions">

                        </div>
                        <div class="max-w-xs">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Status:') }}</label>
                                <div class="switch-field flex mb-3 overflow-hidden">
                                    <input
                                        type="radio"
                                        id="active-status"
                                        name="status"
                                        checked=""
                                        value="1"
                                    />
                                    <label for="active-status">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="deactivate-status"
                                        name="status"
                                        value="0"
                                    />
                                    <label for="deactivate-status">{{ __('Deactivate') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-area text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
     $('.editPlugin').on('click', function (e) {
            
                $('#editPlugin').modal('show');

        
        })
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listener for radio buttons
        const radioButtons = document.querySelectorAll('input[name="level2_setting"]');
        radioButtons.forEach((radio) => {
            radio.addEventListener('change', function () {
                const uniqueCode = this.value;
                if (uniqueCode === 'manual') {
                    // Uncheck all samsub checkboxes
                    const samsubCheckboxes = document.querySelectorAll('input[name="permissions[]"][data-unique-code="samsub"]');
                    samsubCheckboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    });
                } else if (uniqueCode === 'samsub') {
                    // Uncheck all manual checkboxes
                    const manualCheckboxes = document.querySelectorAll('input[name="permissions[]"][data-unique-code="manual"]');
                    manualCheckboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    });
                }
            });
        });

        // Add event listener for manual checkboxes to deselect samsub when manual is selected
        const manualCheckboxes = document.querySelectorAll('input[name="permissions[]"][data-unique-code="manual"]');
        manualCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const isChecked = this.checked;
                if (isChecked) {
                    // Uncheck samsub checkboxes
                    const samsubCheckboxes = document.querySelectorAll('input[name="permissions[]"][data-unique-code="samsub"]');
                    samsubCheckboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    });
                }
            });
        });

        // Add event listener for samsub checkboxes to deselect manual when samsub is selected
        const samsubCheckboxes = document.querySelectorAll('input[name="permissions[]"][data-unique-code="samsub"]');
        samsubCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const isChecked = this.checked;
                if (isChecked) {
                    // Uncheck manual checkboxes
                    const manualCheckboxes = document.querySelectorAll('input[name="permissions[]"][data-unique-code="manual"]');
                    manualCheckboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    });
                }
            });
        });
    });

    $(document).ready(function (e) {
        var i = 0;
        "use strict";

        $("#generate").on('click', function () {
            ++i;
            var form = `<div class="option-remove-row grid grid-cols-12 gap-5">
                <div class="xl:col-span-4 col-span-12">
                    <div class="input-area">
                    <input name="fields[` + i + `][name]" class="form-control" type="text" value="" required placeholder="Field Name">
                    </div>
                </div>

                <div class="xl:col-span-4 col-span-12">
                    <div class="input-area">
                    <select name="fields[` + i + `][type]" class="form-control w-full mb-3">
                        <option value="text">Input Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="file">File upload</option>
                    </select>
                    </div>
                </div>
                <div class="xl:col-span-3 col-span-12">
                    <div class="input-area">
                    <select name="fields[` + i + `][validation]" class="form-control w-full mb-3">
                        <option value="required">Required</option>
                        <option value="nullable">Optional</option>
                    </select>
                    </div>
                </div>

                <div class="col-span-1">
                    <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row delete_desc" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
                    </svg>
                    </button>
                </div>
                </div>`;
            $('.addOptions').append(form)
        });

        $(document).on('click', '.delete_desc', function () {
            $(this).closest('.option-remove-row').remove();
        });
    });

    $(document).ready(function() {
        $('input[name="level2_setting"]').change(function() {
            var value = $(this).val();
            if (value == 'manual') {
                $('.automatic-settings').addClass('hidden');
                $('.manual-settings').removeClass('hidden');
            } else if (value == 'automatic') {
                $('.manual-settings').addClass('hidden');
                $('.automatic-settings').removeClass('hidden');
            }
        });
    });

</script>
@endsection
