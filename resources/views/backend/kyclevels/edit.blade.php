@extends('backend.layouts.app')
@section('title')
{{ __('Update KYC Level') }}
@endsection
@section('content')
<div class="transition-all duration-150 rtl:mr-[200px] p-6">
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
                <div class="card border">
                    <div class="card-header noborder">
                        <h3 class="card-title mb-0">{{ __('All Settings') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="role-cat-items">
                            <div class="accordion">
                                @php
                                    // Group settings by kyclevel and unique_code
                                    $groupedSettings = $kycLevelSettings->groupBy(['kyclevel.name', 'unique_code']);
                                @endphp
                                @foreach($groupedSettings as $kyclevelName => $settingsByCode)
                                    @php
                                        $isExpanded = $kyclevelName == 'Level 2' && $settingsByCode->has('manual'); // Expand the "manual" section by default
                                    @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header text-lg" id="heading{{ $kyclevelName }}">
                                            <button
                                                class="accordion-button @if(!$isExpanded) collapsed @endif flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-x-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ str_replace(' ', '', $kyclevelName) }}"
                                                aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                                                aria-controls="{{ str_replace(' ', '', $kyclevelName) }}">
                                                <span class="flex items-center">
                                                    <span class="icon h-5 w-5 rounded-full bg-slate-100 flex items-center justify-center ltr:mr-2 rtl:ml-2">
                                                        <iconify-icon class="text-sm" icon="lucide:check"></iconify-icon>
                                                    </span>
                                                    {{ $kyclevelName }}
                                                </span>
                                                <svg data-accordion-icon class="w-3 h-3 @if($isExpanded) rotate-180 @endif shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="{{ str_replace(' ', '', $kyclevelName) }}"
                                             class="accordion-collapse collapse @if($isExpanded) show @endif p-5 border border-b-0 border-x-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900"
                                             aria-labelledby="heading{{ $kyclevelName }}">
                                            <div class="accordion-body">
                                                @if($kyclevelName == 'Level 1')
                                                    <table class="table-auto w-full border border-slate-100 dark:border-slate-700">
                                                        <thead>
                                                            <tr>
                                                                <th class="px-4 py-2">{{ __('Title') }}</th>
                                                                <th class="px-4 py-2">{{ __('Status') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($settingsByCode->flatten() as $setting)
                                                                <tr>
                                                                    <td class="border px-4 py-2">{{ ucwords(str_replace('-', ' ', $setting->title)) }}</td>
                                                                    <td class="border px-4 py-2">
                                                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                                            <input type="checkbox" class="sr-only peer" id="{{ $setting->title }}"
                                                                            name="permissions[]"
                                                                            value="{{ $setting->id }}"
                                                                            @if($setting->status) checked @endif>
                                                                            <span class="w-9 h-5 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3.5px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    @foreach($settingsByCode as $uniqueCode => $settings)
                                                        <div class="mb-4">
                                                            <h4 class="text-lg font-medium mb-2">
                                                                @if($uniqueCode == 'manual')
                                                                    <input type="radio" name="level2_setting" value="{{ $uniqueCode }}" class="mr-2" @if($uniqueCode == 'manual') checked @endif>
                                                                    {{ __('Unique Code: ') . $uniqueCode }}
                                                                    <a type="button" class="action-btn cursor-pointer editPlugin" data-id="manualForm">
                                                                        <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                                                    </a>
                                                                @else
                                                                    <input type="radio" name="level2_setting" value="{{ $uniqueCode }}" class="mr-2" @if($uniqueCode == 'manual') checked @endif>
                                                                    {{ __('Unique Code: ') . $uniqueCode }}
                                                                @endif
                                                            </h4>
                                                            <table class="table-auto w-full border border-slate-100 dark:border-slate-700">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="px-4 py-2">{{ __('Title') }}</th>
                                                                        <th class="px-4 py-2">{{ __('Status') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($settings as $setting)
                                                                        <tr>
                                                                            <td class="border px-4 py-2">{{ ucwords(str_replace('-', ' ', $setting->title)) }}</td>
                                                                            <td class="border px-4 py-2">
                                                                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                                                    <input type="checkbox" class="sr-only peer" id="{{ $setting->title }}"
                                                                                    name="permissions[]"
                                                                                    value="{{ $setting->id }}"
                                                                                    data-unique-code="{{ $uniqueCode }}"
                                                                                    @if($setting->status) checked @endif>
                                                                                    <span class="w-9 h-5 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3.5px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $isExpanded = false; // Collapse subsequent accordion items
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                        <div class="text-right p-5">
                            <button class="btn btn-dark inline-flex items-center justify-center" type="submit">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
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
</script>
@endsection
