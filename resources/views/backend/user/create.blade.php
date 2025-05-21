@extends('backend.layouts.app')
@section('title')
    {{ __('New Customer') }}
@endsection
@section('content')
    <form action="{{ route('admin.user.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="space-y-5">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Basic Info') }}
            </h4>
            <div class="card">
                <div class="card-body p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('First Name') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" class="form-control" placeholder="e.g. John" value="{{ old('first_name') }}">
                            @error('first_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Last Name') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <input type="text" name="last_name" class="form-control" placeholder="e.g. Doe" value="{{ old('last_name') }}">
                            @error('last_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        @if(getPageSetting('country_show'))
                            <div class="input-area relative">
                                <label class="form-label">
                                    {{ __('Country') }}
                                    <span class="text-xs text-danger">*</span>
                                </label>
                                <div class="relative">
                                    <select name="country" id="countrySelect" class="select2 form-control w-full" data-placeholder="Select Country">
                                        <option value="" disabled selected>Select Country</option>
                                        @foreach( getCountries() as $country)
                                            <option value="{{ $country['name'].':'.$country['dial_code'] }}"
                                                @if( old('country') == $country['name'].':'.$country['dial_code']) selected @endif
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $country['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Username') }}
                            </label>
                            <input type="text" name="username" class="form-control" placeholder="e.g. johndoe" value="{{ old('username') }}">
                            @error('username')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Phone') }}
                            </label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. 1234567890" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Email') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="e.g. johndoe@example.com">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Gender') }}
                            </label>
                            <select name="gender" class="select2 form-control w-full" data-placeholder="Select Gender">
                                <option value="">{{ __('Select Gender') }}</option>
                                <option value="male" @if(old('gender') == 'male') selected @endif>{{ __('Male') }}</option>
                                <option value="female" @if(old('gender') == 'female') selected @endif>{{ __('Female') }}</option>
                                <option value="other" @if(old('gender') == 'other') selected @endif>{{ __('Other') }}</option>
                            </select>
                            @error('gender')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Date Of Birth') }}
                            </label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('City') }}
                            </label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. New York, Jaipur, Dubai" value="{{ old('city') }}">
                            @error('city')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Zip Code') }}
                            </label>
                            <input type="text" name="zip_code" class="form-control" placeholder="e.g. 90250" value="{{ old('zip_code') }}">
                            @error('zip_code')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Address') }}
                            </label>
                            <input type="text" name="address" class="form-control" placeholder="e.g. 132, My Street, Kingston, New York 12401" value="{{ old('address') }}">
                            @error('address')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Attach to Risk Profile') }}
                            </label>
                            <select name="risk_profile_tags[]" class="select2 form-control w-full" multiple="multiple" data-placeholder="Select Tags">
                                @foreach($riskProfileTags as $tag)
                                    <option value="{{ $tag->id }}" @if(old('risk_profile_tags') && in_array($tag->id, old('risk_profile_tags'))) selected @endif>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            @error('risk_profile_tags')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area relative lg:col-span-3">
                            <label class="form-label">
                                {{ __('Comment') }}
                            </label>
                            <textarea name="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Security') }}
            </h4>
            <div class="card">
                <div class="card-body p-6">
                    <div class="grid grid-cols-12 gap-5 items-center">
                        <div class="lg:col-span-4 col-span-12">
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Password') }}
                                    <span class="text-xs text-danger">*</span>
                                </label>
                                <input type="password" name="password" class="form-control" placeholder="********">
                                @error('password')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="lg:col-span-8 col-span-12">
                            <div class="grid md:grid-cols-3 col-span-1 gap-5">
                                <div class="input-area">
                                    <label for="" class="form-label invisible">
                                        {{ __('Email Verified') }}
                                    </label>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <input type="hidden" value="0" name="is_email_verified">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="is_email_verified" value="1" class="sr-only peer" @if(old('is_email_verified', 0)) checked @endif>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                        <label class="form-label !w-auto pt-0 !mb-0">
                                            {{ __('Email Verified') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label invisible">
                                        {{ __('Phone Verified') }}
                                    </label>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <input type="hidden" value="0" name="is_phone_verified">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="is_phone_verified" value="1" class="sr-only peer" @if(old('is_phone_verified', 0)) checked @endif>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                        <label class="form-label !w-auto pt-0 !mb-0">
                                            {{ __('Phone Verified') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label invisible">
                                        {{ __('Temporary Password') }}
                                    </label>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <input type="hidden" value="0" name="temporary_password">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="temporary_password" value="1" class="sr-only peer" @if(old('temporary_password', 0)) checked @endif>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                        <label class="form-label !w-auto pt-0 !mb-0">
                                            {{ __('Temporary Password') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             @if(auth()->user()->hasRole('Super-Admin'))
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Assign Customer to Staff Member') }}
            </h4>
            <div class="card">
          <div class="card-body p-6">
                    <div class="max-w-2xl w-full mx-auto">
                        <div class="space-y-5">
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Assign to Staff Member') }}
                            </label>
                            <select name="staff_id" class="select2 form-control w-full" data-placeholder="Select Staff Member">
                                <option value="">{{ __('No staff assignment') }}</option>
                                @foreach($staffMembers as $staff)
                                    <option value="{{ $staff->id }}" @if(old('staff_id') == $staff->id) selected @endif>
                                        {{ $staff->first_name }} {{ $staff->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            </div>
             @else
                        <input type="hidden" name="staff_id" value="{{ auth()->id() }}">
                        @endif
           <div class="card basicTable_wrapper">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">{{ __('Customer KYC Verification') }}</h4>
                        <p class="card-text">
                            {{ __('Complete the customer KYC verification during account creation (optional).') }}
                        </p>
                    </div>
                </div>
                <div class="card-body p-6">
                    <div class="max-w-2xl w-full mx-auto">
                        <div class="space-y-5">
                            <div class="input-area relative">
                                <label for="" class="form-label">{{ __('KYC Level') }}</label>
                                <select name="kyc_level" id="kycLevelSelect" class="select2 form-control w-full" data-placeholder="Select Level">
                                    <option value="" selected>{{ __('Select Level (Optional)') }}</option>
                                    <option value="1" @if(old('kyc_level') == '1') selected @endif>{{ __('Level 1') }}</option>
                                    <option value="3" @if(old('kyc_level') == '3') selected @endif>{{ __('Level 2') }}</option>
                                    <option value="5" @if(old('kyc_level') == '5') selected @endif>{{ __('Level 3') }}</option>
                                </select>
                            </div>
                            
                            <div class="input-area relative" id="verificationTypeContainer" style="display: none;">
                                <label for="" class="form-label">{{ __('Verification Type') }}</label>
                                <select id="kycTypeSelect" name="kyc_id" class="select2 form-control" data-placeholder="Select Type">
                                    <option value="" selected>{{ __('Select Type (Optional)') }}</option>
                                </select>
                            </div>
                            
                            @if(Auth::user() && Auth::user()->getRoleNames()->contains('Super-Admin'))
                            <div class="input-area relative" id="autoApproveContainer" style="display: none;">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto !mb-0">
                                        {{ __('Auto Approve') }}
                                    </label>
                                    <div class="form-switch" style="line-height: 0;">
                                        <input type="hidden" name="is_auto_approve" value="0"/>
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_auto_approve" value="1" class="sr-only peer" @if(old('is_auto_approve', 1)) checked @endif>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="kycData"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                {{ __('Add New Customer') }}
            </button>
        </div>
    </form>
@endsection
@section('script')
<script>
$(document).ready(function() {
    // Initialize select2
    $('.select2').select2();

    // Function to toggle KYC fields based on level
    function toggleKycFields(level) {
        if (level === '1') {
            $('#verificationTypeContainer').hide();
            $('#autoApproveContainer').show();
        } else if (level === '3' || level === '5') {
            $('#verificationTypeContainer').show();
            $('#autoApproveContainer').show();
        } else {
            $('#verificationTypeContainer').hide();
            $('#autoApproveContainer').hide();
        }
    }

    // Initialize based on old value if exists
    var oldKycLevel = '{{ old("kyc_level") }}';
    if (oldKycLevel) {
        toggleKycFields(oldKycLevel);
    }

    $('#kycLevelSelect').on('change', function() {
        var level = $(this).val();
        $('.kycData').empty();
        toggleKycFields(level);
        
        if (level) {
            $('#kycTypeSelect').prop('disabled', false).empty().append('<option value="">Select Type</option>');
            
            $.ajax({
                url: '{{ route("admin.kyc.kycMethods") }}',
                type: "GET",
                data: { 
                    kyc_level: level,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.kycs && response.kycs.length > 0) {
                        $.each(response.kycs, function(index, kyc) {
                            $('#kycTypeSelect').append(
                                $('<option></option>').val(kyc.id).text(kyc.name)
                            );
                        });
                        // Set old value if exists
                        var oldKycId = '{{ old("kyc_id") }}';
                        if (oldKycId) {
                            $('#kycTypeSelect').val(oldKycId).trigger('change');
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error loading KYC types:', xhr.responseText);
                    alert('Failed to load KYC types. Please try again.');
                }
            });
        } else {
            $('#kycTypeSelect').prop('disabled', true).val('').trigger('change');
        }
    });

    $('#kycTypeSelect').on('change', function() {
        var id = $(this).val();
        $('.kycData').empty();

        if (!id) return;

        $.ajax({
            url: '{{ route("admin.kyc.data", "") }}/' + id,
            type: "GET",
            success: function(response) {
                $('.kycData').html(response);
                initFileUploads();
            },
            error: function(xhr) {
                console.error('Error loading KYC fields:', xhr.responseText);
                alert('Failed to load KYC fields. Please try again.');
            }
        });
    });

    function initFileUploads() {
        $('.wrap-custom-file input[type="file"]').each(function() {
            var $input = $(this);
            var $label = $input.next('label');
            var labelVal = $label.html();

            $input.on('change', function(e) {
                var fileName = e.target.value.split('\\').pop();
                fileName ? $label.find('span').html(fileName) : $label.html(labelVal);

                if (e.target.files && e.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $label.find('img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });
    }
});
</script>
@endsection