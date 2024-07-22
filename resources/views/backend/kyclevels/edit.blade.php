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
                    @if($kycLevel->slug=='level-2')
                        <a data-bs-toggle="modal" data-bs-target="#addKycLevel2Formmodal" class="inline-flex items-center justify-center text-success-500 adds-new-form" id="addNewLevel2Button">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>
                            Add New
                        </a>
                    @endif
                    @if($kycLevel->slug=='level-3')
                        <a data-bs-toggle="modal" data-bs-target="#addKycLevel3Formmodal" class="inline-flex items-center justify-center text-success-500 adds-new-form">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>
                            Add New
                        </a>
                    @endif
                    @php
                        // Group settings by kyclevel and unique_code
                        $groupedSettings = $kycLevelSettings->groupBy(['kyclevel.slug', 'unique_code']);
                    @endphp
                    @foreach($groupedSettings as $kyclevelName => $settingsByCode)
                   
                        @php
                        
                            $isExpanded = $kyclevelName == 'level-2' && $settingsByCode->has('manual'); // Expand the "manual" section by default
                        @endphp
                       
                        @if($kyclevelName == 'level-1')
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
                                            <a type="button" class="action-btn cursor-pointer editLevel1" data-id="{{ $setting->id }}" data-status="{{ $setting->status }}">
                                                <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                        
                            @if($kyclevelName == 'level-2')
                            
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
                                
                            @endif
                            @foreach($settingsByCode as $uniqueCode => $settings)
                            
                                <div class="settings-wrapper @if($uniqueCode == 'manual') manual-settings @else automatic-settings @endif">
                                    <div class="space-y-5">
                                        @foreach($settings as $setting)
                                      
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
                                                    @if($uniqueCode == 'manual')
                                                    
                                                        <a type="button" class="action-btn cursor-pointer editLevel2" data-id="{{$setting->id}}" data-route="{{ route('admin.kyc.editKycLevel2', $setting->kyc_id) }}">
                                                            <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                                        </a>
                                                        @else
                                                        <a type="button" class="action-btn cursor-pointer editLevel2auto" data-id="{{$setting->id}}" >
                                                            <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @php
                            $isExpanded = false; 
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
@include('backend.kyclevels.include.__editlevel1')
@include('backend.kyclevels.include.__editlevel2manual')
@include('backend.kyclevels.include.__editlevel2auto')
@include('backend.kyclevels.include.__addlevel2manual')
@include('backend.kyclevels.include.__addlevel3manual')
@endsection

@section('script')
<script>
    
    $(document).ready(function () {
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
                var actionUrl = "{{ route('admin.kyclevels.level1status.update', ':id') }}".replace(':id', recordId);
                $('#editStatusForm').attr('action', actionUrl);
                if (currentStatus === 1) {
                    $('#active-status').prop('checked', true);
                    $('#deactivate-status').prop('checked', false);
                } else {
                    $('#active-status').prop('checked', false);
                    $('#deactivate-status').prop('checked', true);
                }
                $('#editPlugin').modal('show');
            });
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
                    <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row delete_desc" type="button">
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
                        $('input[name="status"][value="' + response.kyc.status + '"]').prop('checked', true);
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
                complete: function() {
            // Hide loader and enable button regardless of the outcome
            $submitBtn.prop('disabled', false);
            $submitBtn.find('.btn-loader').hide();
            $submitBtn.find('.btn-text').show();
        }
            });
        });
    });
    
});


    document.addEventListener('DOMContentLoaded', function() {
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
