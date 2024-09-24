
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
                if (uniqueCode === 'manual') {
                    const samsubCheckboxes = document.querySelectorAll('input[name="permissions[]"][data-unique-code="samsub"]');
                    samsubCheckboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    });
                    $('.samsub-settings').addClass('hidden');
                    $('.manual-settings').removeClass('hidden');
                    $('#addNewButton').removeClass('hidden');
                } else if (uniqueCode === 'samsub') {
                    const manualCheckboxes = document.querySelectorAll('input[name="permissions[]"][data-unique-code="manual"]');
                    manualCheckboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    });
                    $('.manual-settings').addClass('hidden');
                    $('.samsub-settings').removeClass('hidden');
                    $('#addNewButton').addClass('hidden');
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

        
            $('.editLevel1').on('click', function () {
                var recordId = $(this).data('id');
                var currentStatus = $(this).data('status');
                var actionUrl = "{{ route('admin.kyclevels.update', ':id') }}".replace(':id', recordId);
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
            const route = button.data('route'); // Get the route URL from data attribute
            
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
    
        $(document).on('submit', '#editKycForm', function (e) {
            console.log('currentKycId', currentKycId);
            e.preventDefault();
            const formData = new FormData(this);
            const updateUrlTemplate = "{{ route('admin.kyc.updateLevel2Kyc',':id') }}";
            const updateUrl = updateUrlTemplate.replace(':id', currentKycId); 
           
            console.log('updateUrl', updateUrl);
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
                }
            });
        });
    });
    
});

