@extends('backend.setting.user_management.index')
@section('title')
    {{ __('Update KYC Level') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Update KYC Level') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.kyclevels.index') }}"
               class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>

    <form action="{{ route('admin.kyc.level.update', $kycLevel->id) }}" method="post" class="space-y-4">
        @csrf
        <div class="grid grid-cols-12 gap-5">
            <div class="lg:col-span-4 col-span-12">
                <div class="card h-full">
                    <div class="card-body h-full flex flex-col p-6 pt-0">
                        <div class="card-header noborder !px-0">
                            <div>
                                <h4 class="card-title">
                                    {{ __('KYC Details') }}
                                </h4>
                                <p class="block font-normal text-sm text-slate-500">
                                    {{ __('Set Details For KYC :name', ['name' => $kycLevel->name]) }}
                                </p>
                            </div>
                        </div>
                        <div class="input-area mb-5">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control" required="" name="name"
                                   value="{{ $kycLevel->name }}"/>
                        </div>
                        <div class="input-area mb-5">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto pt-0">
                                    {{ __('Status:') }}
                                </label>
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="status">
                                    <label
                                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="status" value="1" class="sr-only peer"
                                               @if($kycLevel->status==1) ? checked : ''@endif>
                                        <span
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-auto">
                            <button class="btn btn-dark inline-flex items-center justify-center" type="submit">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-8 col-span-12">
                <div class="card h-full">
                    <div class="card-body p-6 pt-0">
                        <div class="card-header noborder !px-0">
                            <div>
                                <h4 class="card-title mb-2">
                                    {{ __('KYC Methods') }}
                                </h4>
                                <p class="block font-normal text-sm text-slate-500">
                                    {{ __('Set Methods For KYC :name', ['name' => $kycLevel->name]) }}
                                </p>
                            </div>
                        </div>
                        <div class="role-cat-items relative space-y-5">
                            @foreach($kycSubLevels as $kycSubLevel)

                                @if($kycLevel->slug==\App\Enums\KycLevelSlug::LEVEL1)
                                    <div
                                        class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                                        <div class="gateway-name flex items-center gap-2">
                                            <div class="gateway-icon mr-1">
                                                <iconify-icon class="text-3xl"
                                                              icon="mdi:id-card-outline"></iconify-icon>
                                            </div>
                                            <div class="gateway-title">
                                                <h4 class="text-base">
                                                    {{ ucwords($kycSubLevel->name)}}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="gateway-right flex items-center gap-2">
                                            <div class="gateway-status">
                                                @if($kycSubLevel->status)
                                                    <div
                                                        class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                        {{ __('Active') }}
                                                    </div>
                                                @else
                                                    <div
                                                        class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                        {{ __('Deactivated') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="gateway-edit">
                                                <a type="button" class="action-btn cursor-pointer editLevel1"
                                                   data-id="{{ $kycSubLevel->id }}"
                                                   data-status="{{ $kycSubLevel->status }}">
                                                    <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                @elseif($kycLevel->slug==\App\Enums\KycLevelSlug::LEVEL2 && $level2Show)
                                    @php
                                        $level2Show = false;
                                    @endphp
                                    {{--                                    {{dd($kycSubLevel->status)}}--}}
                                    {{--                                        @if($kycSubLevel->name == \App\Enums\KycType::AUTOMATIC) {{dd('ss')}} @endif--}}

                                    <div class="input-area">
                                        <label for="" class="form-label">
                                            {{ __('KYC Method') }}
                                        </label>
                                        <div class="flex items-center space-x-7 flex-wrap pb-3">
                                            @foreach($kycSubLevels as $kycSubLevelmenue)
                                            @if($kycSubLevelmenue->name == \App\Enums\KycType::MANUAL)
                                                <div class="success-radio">
                                                    <label class="flex items-center cursor-pointer">
                                                        <input
                                                            type="radio"
                                                            class="hidden"
                                                            name="level2_setting"
                                                            value="{{\App\Enums\KycType::MANUAL}}"
                                                            @if($kycSubLevelmenue->status == 1) checked @endif
                                                        >
                                                        <span
                                                            class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                        <span class="text-success-500 text-sm leading-6 capitalize">
                   {{ __('Manual') }}
               </span>
                                                    </label>
                                                </div>
                                            @endif
                                            @if($kycSubLevelmenue->name == \App\Enums\KycType::AUTOMATIC)

                                                <div class="success-radio">

                                                    <label class="flex items-center cursor-pointer">
                                                        <input
                                                            type="radio"
                                                            class="hidden"
                                                            name="level2_setting"
                                                            value="{{\App\Enums\KycType::AUTOMATIC}}"
                                                            @if($kycSubLevelmenue->status == 1) checked @endif
                                                        >
                                                        <span
                                                            class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                        <span class="text-success-500 text-sm leading-6 capitalize">
                   {{ __('Automatic') }}
               </span>
                                                    </label>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>


                                    <!-- Manual Content -->
                                    <div id="manualContent"
                                         class="space-y-5  hidden ">
                                        <div class="absolute right-0 top-3">
                                            <a data-bs-toggle="modal" data-bs-target="#addKycLevel2Formmodal"
                                               class="btn btn-dark btn-sm inline-flex items-center justify-center adds-new-form">
                                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                              icon="lucide:plus"></iconify-icon>
                                                {{ __('Add New') }}
                                            </a>
                                        </div>
                                        @foreach($manulKycs as $kyc)
                                            {{--                                        @if($kycSubLevel->name == \App\Enums\KycType::MANUAL)--}}
                                            <div
                                                class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                                                <div class="gateway-name flex items-center gap-2">
                                                    <div class="gateway-icon mr-1">
                                                        <iconify-icon class="text-3xl"
                                                                      icon="mdi:id-card-outline"></iconify-icon>
                                                    </div>
                                                    <div class="gateway-title">
                                                        <h4 class="text-base">
                                                            {{ ucwords($kyc->name)}}
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="gateway-right flex items-center gap-2">
                                                    <div class="gateway-status">
                                                        @if($kyc->status)
                                                            <div
                                                                class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                                {{ __('Active') }}
                                                            </div>
                                                        @else
                                                            <div
                                                                class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                                {{ __('Deactivated') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="gateway-edit">
                                                        <a type="button" class="action-btn cursor-pointer editLevel2"
                                                           data-id="{{ $kyc->id }}"
                                                           data-status="{{ $kyc->status }}"
                                                           data-route="{{ route('admin.kyc.editKycLevel2', $kyc->id) }}">
                                                            <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--                                        @endif--}}
                                        @endforeach
                                    </div>

                                    <!-- Automatic Content -->
                                    <div id="automaticContent"
                                         class="hidden">
                                        <div
                                            class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                                            <div class="gateway-name flex items-center gap-2">
                                                <div class="gateway-icon mr-1">
                                                    <iconify-icon class="text-3xl"
                                                                  icon="mdi:id-card-outline"></iconify-icon>
                                                </div>
                                                <div class="gateway-title">
                                                    <h4 class="text-base">
                                                        {{ __('Sumsub')}}
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="gateway-right flex items-center gap-2">
                                                <div class="gateway-status">
                                                    @if($sumsub->status)
                                                        <div
                                                            class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                            {{ __('Active') }}
                                                        </div>
                                                    @else
                                                        <div
                                                            class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                            {{ __('Deactivated') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="gateway-edit">
                                                    <a type="button" class="action-btn cursor-pointer editPlugin"
                                                       data-id="{{ $sumsub->id }}"
                                                       data-status="{{ $sumsub->status }}">
                                                        <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('backend.kyc_levels.include.__editlevel1')
    @include('backend.kyc_levels.include.__editlevel2manual')
    @include('backend.kyc_levels.include.__editlevel2auto')
    @include('backend.kyc_levels.include.__addlevel2manual')
    @include('backend.kyc_levels.include.__addlevel3manual')
@endsection

@section('user-management-script')
    <script>
        $(document).ready(function () {
            function toggleSettings() {
                const manualRadio = $('input[name="level2_setting"][value={{\App\Enums\KycType::MANUAL}}]');
                const automaticRadio = $('input[name="level2_setting"][value={{\App\Enums\KycType::AUTOMATIC}}]');
                const manualContent = $('#manualContent');
                const automaticContent = $('#automaticContent');

                if (manualRadio.is(':checked')) {
                    manualContent.removeClass('hidden');
                    automaticContent.addClass('hidden');
                } else if (automaticRadio.is(':checked')) {
                    manualContent.addClass('hidden');
                    automaticContent.removeClass('hidden');
                }
            }

// Initial toggle based on the default checked radio button
            toggleSettings();

// Add event listeners to radio buttons
            $('input[name="level2_setting"]').change(toggleSettings);

// Existing jQuery logic
            var i1 = 0;
            $(".generateCreate").on('click', function () {
                ++i1;
                var form = `<div class="option-remove-row grid grid-cols-12 gap-5">
<div class="xl:col-span-4 col-span-12">
<div class="input-area">
<input name="fields[` + i1 + `][name]" class="form-control" type="text" value="" required placeholder="Field Name">
</div>
</div>
<div class="xl:col-span-4 col-span-12">
<div class="input-area">
<select name="fields[` + i1 + `][type]" class="form-control w-full mb-3">
<option value="text">Input Text</option>
<option value="textarea">Textarea</option>
<option value="file">File upload</option>
</select>
</div>
</div>
<div class="xl:col-span-3 col-span-12">
<div class="input-area">
<select name="fields[` + i1 + `][validation]" class="form-control w-full mb-3">
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
                $('.addOptions').append(form);
            });
            $('.samsub-settings').addClass('hidden');
            $('#addNewButton').addClass('hidden');
            const radioButtons = document.querySelectorAll('input[name="level2_setting"]');
            radioButtons.forEach((radio) => {
                radio.addEventListener('change', function () {

                    const uniqueCode = this.value;

                    if (uniqueCode == 'manual') {

                        $('.samsub-settings').addClass('hidden');
                        $('.manual-settings').removeClass('hidden');
                        $('#addNewLevel2Button').removeClass('hidden');
                    } else if (uniqueCode == 'automatic') {

                        $('.manual-settings').addClass('hidden');
                        $('.samsub-settings').removeClass('hidden');
                        $('#addNewLevel2Button').addClass('hidden');
                    }
                });
            });

            $('.editLevel1').on('click', function () {
                var recordId = $(this).data('id');
                var currentStatus = $(this).data('status');
                var actionUrl = "{{ route('admin.kyc.subLevel.update', ':id') }}".replace(':id', recordId);
                $('#editStatusForm').attr('action', actionUrl);
                if (currentStatus === 1) {
                    $('#active-status').prop('checked', true);
                    $('#deactivate-status').prop('checked', false);
                } else {
                    $('#active-status').prop('checked', false);
                    $('#deactivate-status').prop('checked', true);
                }
                $('#editLevel1Modal').modal('show');
            });

            $('.editPlugin').on('click', function (e) {
                "use strict"
                var id = $(this).data('id');
                $('.edit-plugin-section').empty();

                var url = '{{ route("admin.settings.plugin.data",":id") }}';
                url = url.replace(':id', id);
                $.get(url, function ($data) {
                    $('.edit-plugin-section').append($data)
                    $('#editPlugin').modal('show');

                })
            })

            $('.add-new-form').on('click', function () {

//var actionUrl = "{{ route('admin.kyclevels.update', ':id') }}".replace(':id', recordId);
                $('#addKycForm').attr('action', actionUrl);

                $('#addKycFormmodal').modal('show');
            });


// Field option generation
            var i = 0;
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
<button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row" type="button">
<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
<path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
</svg>
</button>
</div>
</div>`;
                $('.addOptions').append(form);
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.option-remove-row').remove();
            });
        });
        $(document).ready(function () {
            $('input[name="level2_setting"]').change(function () {
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
        $(document).ready(function () {
            let currentKycId;

            $(document).ready(function () {
                $('.editLevel2').on('click', function () {
                    const button = $(this);
                    const route = button.data('route');
// Perform AJAX request
                    $.ajax({
                        url: route,
                        method: 'GET',
                        success: function (response) {
                            console.log('response', response);
                            if (response.kyc) {
                                currentKycId = response.kyc.id;
                                $('#editLevel2').modal('show');

// Populate the modal fields with the response data
                                $('#kycName').val(response.kyc.name);

// Clear existing options before adding new ones
                                $('.addOptions').empty();

// Parse the fields and add them to the form
                                const fields = JSON.parse(response.kyc.fields);
                                $.each(fields, function (key, value) {
                                    const form = `
<div class="option-remove-row grid grid-cols-12 gap-5">
<div class="xl:col-span-4 col-span-12">
<div class="input-area">
<input name="fields[${key}][name]" class="form-control" type="text" value="${value.name}" required placeholder="Field Name">
</div>
</div>
<div class="xl:col-span-4 col-span-12">
<div class="input-area">
<select name="fields[${key}][type]" class="form-control w-full mb-3">
   <option value="text" ${value.type == 'text' ? 'selected' : ''}>Input Text</option>
   <option value="textarea" ${value.type == 'textarea' ? 'selected' : ''}>Textarea</option>
   <option value="file" ${value.type == 'file' ? 'selected' : ''}>File upload</option>
</select>
</div>
</div>
<div class="xl:col-span-3 col-span-12">
<div class="input-area">
<select name="fields[${key}][validation]" class="form-control w-full mb-3">
   <option value="required" ${value.validation == 'required' ? 'selected' : ''}>Required</option>
   <option value="nullable" ${value.validation == 'nullable' ? 'selected' : ''}>Optional</option>
</select>
</div>
</div>
<div class="col-span-1">
<button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row" type="button">
<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
   <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
</svg>
</button>
</div>
</div>`;
                                    $('.addOptions').append(form);
                                });

// Set the status
// Set the status based on response.kyc.status
                                if (response.kyc.status == 1) {
                                    $('input[name="status"][value="1"]').prop('checked', true);
                                } else {
                                    $('input[name="status"][value="0"]').prop('checked', true);
                                }
                                var i = $('.addOptions .option-remove-row').length;
// Add a new option row
                                $('#generate').off('click').on('click', function () {
                                    ++i;

                                    const newForm = `
<div class="option-remove-row grid grid-cols-12 gap-5">
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
<button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row" type="button">
<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
   <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
</svg>
</button>
</div>
</div>`;
                                    $('.addOptions').append(newForm);
                                });

// Removing an option row
                                $(document).on('click', '.delete-option-row', function () {
                                    $(this).closest('.option-remove-row').remove();
                                });
                            } else {
                                console.error('No KYC data found in the response.');
                            }
                        },
                        error: function (xhr) {
                            console.error('Error fetching data:', xhr);
                        }
                    });
                });
                $('.editLevel2auto').on('click', function () {
                    $('#editLevel2auto').modal('show');


                });
                $(document).on('submit', '#editKycForm', function (e) {
                    e.preventDefault();
                    const $submitBtn = $('#submitBtn');
                    $submitBtn.prop('disabled', true);
                    $submitBtn.find('.btn-loader').show();
                    $submitBtn.find('.btn-text').hide();
                    const formData = new FormData(this);
                    const updateUrlTemplate = "{{ route('admin.kyc.updateLevel2Kyc',':id') }}";
                    const updateUrl = updateUrlTemplate.replace(':id', currentKycId);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: updateUrl,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#editLevel2').modal('hide');
                            window.location.reload();
                        },
                        error: function (xhr) {
                            console.error('Error updating KYC form:', xhr);
                        },
                        complete: function () {
// Hide loader and enable button regardless of the outcome
                            $submitBtn.prop('disabled', false);
                            $submitBtn.find('.btn-loader').hide();
                            $submitBtn.find('.btn-text').show();
                        }
                    });
                });
            });

        });
        document.addEventListener('DOMContentLoaded', function () {
            const manualRadio = document.querySelector('input[name="level2_setting"][value="manual"]');
            const automaticRadio = document.querySelector('input[name="level2_setting"][value="automatic"]');
            const manualSettings = document.querySelector('.manual-settings');
            const automaticSettings = document.querySelector('.automatic-settings');

            function toggleSettings() {
                if (manualRadio.checked) {
                    manualSettings.style.display = 'block';
                    automaticSettings.style.display = 'none';
                } else {
                    manualSettings.style.display = 'none';
                    automaticSettings.style.display = 'block';
                }
            }

// Initial toggle based on the default checked radio button
            toggleSettings();

// Add event listeners to radio buttons
            manualRadio.addEventListener('change', toggleSettings);
            automaticRadio.addEventListener('change', toggleSettings);
        });
    </script>
@endsection
