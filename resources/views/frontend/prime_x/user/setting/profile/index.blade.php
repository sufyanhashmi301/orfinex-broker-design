@extends('frontend::user.setting.index')
@section('title')
    {{ __('Profile Settings') }}
@endsection
@section('settings-content')
    <div class="space-y-5 profile-page">
        <div class="grid grid-cols-12 gap-6">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <div class="profiel-wrap px-[35px] pb-10 pt-10 rounded-lg bg-white dark:bg-secondary lg:space-y-0 space-y-6 relative z-[1]">
                    <div class="customer-profile-cover absolute left-0 top-0 h-[115px] w-full z-[-1] rounded-t-lg" style="background-image: url('{{ config('app.r2_asset_url') . '/fallback/user-header.png' }}')"></div>
                    <div class="profile-box">
                        <div class="flex items-center justify-center h-[140px] w-[140px] mb-4 rounded-full ring-4 ring-slate-100 relative bg-slate-300 dark:bg-body dark:text-white text-slate-900 mx-auto">
                            <img
                                class="w-full h-full object-cover rounded-full"
                                src="{{ getFilteredPath($user->avatar, 'fallback/user.png') }}"
                                alt="{{$user->first_name}}"
                            />
                            <label class="absolute right-1 h-8 w-8 bg-slate-50 text-slate-600 rounded-full shadow-sm flex flex-col items-center justify-center top-[100px] cursor-pointer">
                                <input type="file" class="hidden" id="file-input" name="image" accept="image/*">
                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                            </label>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                                {{$user->first_name .' '. $user->last_name}}
                                @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                                    <span class="badge badge-success ml-1">
                                        {{ __('Verified') }}
                                    </span>
                                @else
                                    <span class="badge badge-danger ml-1">
                                        {{ __('Unverified') }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm font-light text-slate-600 dark:text-slate-200">
                                {{ucwords($user->city)}}@if($user->city != ''), @endif{{ $user->country }}
                            </div>
                            <div class="text-sm font-light text-slate-600 dark:text-slate-200 my-5">
                                <span class="font-medium">
                                    {{ __('Member since: ') }}
                                </span>
                                {{ toSiteTimezone($user->created_at, 'D, M d, Y h:i A') }}
                            </div>
                        </div>
                        <ul class="space-y-5">
                            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-100">
                                <span>{{ __('Customer Group: ') }}</span>
                                @if($user->customerGroups->isNotEmpty())
                                    @foreach($user->customerGroups as $group)
                                        <span>{{ $group->name }}</span>
                                    @endforeach
                                @else
                                    <span>{{ 'N/A' }}</span>
                                @endif
                            </li>
                            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-100">
                                <span>{{ __('Risk Profile:') }}</span> <!-- Added colon here -->
                                <span class="flex items-center gap-2">
                                    @if($user->riskProfileTags->isEmpty())
                                        {{ __('N/A') }}
                                    @else
                                        {{ $user->riskProfileTags->pluck('name')->implode(', ') }}
                                    @endif
                                </span>
                            </li>

                            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-100">
                                <span>{{ __('KYC Level:') }}</span>
                                <span>
                                    @php
                                        $displayName = 'N/A';

                                        if(isset($user->kyc) && $user->kyc > 0) {
                                            // Determine the appropriate KycLevel based on the user's KYC status
                                            if ($user->kyc == 1) {
                                                // If KYC is 1, fetch the name from KycLevel where id == 1
                                                $kycLevel = \App\Models\KycLevel::where('id', 1)->first();
                                            } elseif (in_array($user->kyc, [2, 3, 4])) {
                                                // If KYC is 2, 3, or 4, fetch the name from KycLevel where id == 2
                                                $kycLevel = \App\Models\KycLevel::where('id', 2)->first();
                                            } elseif (in_array($user->kyc, [5, 6, 7])) {
                                                // If KYC is 5, 6, or 7, fetch the name from KycLevel where id == 3
                                                $kycLevel = \App\Models\KycLevel::where('id', 3)->first();
                                            }

                                            // If we found a matching KycLevel
                                            if (isset($kycLevel)) {
                                                if (in_array($user->kyc, [1, 4, 7])) {
                                                    // Only show the KycLevel->name for kyc == 1, 4, or 7
                                                    $displayName = $kycLevel->name;
                                                } else {
                                                    // Get the KYCStatus enum name
                                                    $kycStatus = App\Enums\KYCStatus::from($user->kyc)->name;
                                                    $kycStatusFormatted = ucwords(str_replace('_', ' ', strtolower($kycStatus)));
                                                    // Show both KycLevel->name and KYCStatus for other values
                                                    $displayName = $kycLevel->name . ' - ' . $kycStatusFormatted;
                                                }
                                            } else {
                                                // Fallback to just showing the KYCStatus if no KycLevel is found
                                                $kycStatus = App\Enums\KYCStatus::from($user->kyc)->name;
                                                $displayName = ucwords(str_replace('_', ' ', strtolower($kycStatus)));
                                            }
                                        }
                                    @endphp
                                    {{ $displayName }}
                                </span>
                            </li>
                            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-100">
                                <span>{{ __('IB Member:') }}</span> <!-- Added colon here -->
                                <span class="flex items-center gap-2">
                                    @if($user->ib_status == 'Unprocessed')
                                        {{ __('N/A') }}
                                    @else
                                        {{ ucfirst($user->ib_status)  }}
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                {{--profile settings--}}
                @include('frontend::user.setting.include.__profile')
            </div>
        </div>
    </div>

    {{-- Modal for image crop--}}
    @include('frontend::user.setting.include.__avatar_cropper_modal')

@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('global/css/cropper.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/intlTelInput.css') }}">
@endsection
@section('script')
    <script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('global/js/cropper.js') }}"></script>
    <script>
        (function() {
            var input = document.querySelector('#phone');
            var userPhone = "{{ $user->phone }}";
            if (input && window.intlTelInput) {
                try {
                    var iti = window.intlTelInput(input, {
                        separateDialCode: true,
                        showSelectedDialCode: true,
                        formatOnDisplay: true,
                        autoPlaceholder: 'polite',
                        utilsScript: "{{ asset('frontend/js/utils.js') }}"
                    });
                    if (userPhone) {
                        try { iti.setNumber(userPhone); } catch (e) {}
                    }
                    var form = document.getElementById('profile-update-form');
                    if (form) {
                        form.addEventListener('submit', function() {
                            try {
                                // Ensure the submitted value is full E.164
                                input.value = iti.getNumber();
                            } catch (e) {}
                        });
                    }
                } catch (e) {}
            }
        })();
        // No client-side phone validation required; server handles with libphonenumber
        // $('#profile-update-save').on('click', function (event) {
        //     event.preventDefault();
        //     var form = $("#profile-update-form");
        //     // var input = $("#phone");
        //     // Get the selected country data
        //     var countryData = input.intlTelInput('getSelectedCountryData');
        //
        //     // Get the full phone number, including the country code
        //     var fullPhoneNumber = "+" + countryData.dialCode + input.val();
        //
        //     // You can now use 'fullPhoneNumber' to save it into your form or perform other actions
        //     console.log("Full Phone Number:", fullPhoneNumber);
        //
        //     // For example, if you want to save it to a hidden input field before submitting the form
        //     var hiddenInput = $("<input>")
        //         .attr("type", "hidden")
        //         .attr("name", "fullPhoneNumber")
        //         .val(fullPhoneNumber);
        //
        //     // Append the hidden input to the form
        //     form.append(hiddenInput);
        //
        //     // Now, you can submit the form with the updated data
        //     form.submit();
        // });

        //Profile picture JS
        $(document).ready(function () {
            var $image = $('#uploadedAvatar');
            var $input = $('#file-input');
            var $cropBtn = $('#crop-image');
            var $modal = $('#cropperModal');
            var cropper;

            $input.on('change', function (e) {
                var files = e.target.files;

                if (files && files.length > 0) {
                    var file = files[0];
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $image.attr('src', e.target.result);
                        $modal.modal('show');
                    };

                    reader.readAsDataURL(file);
                }
            });

            $modal.on('shown.bs.modal', function () {
                cropper = new Cropper($image[0], {
                    aspectRatio: 1,
                    viewMode: 0,
                    responsive: true,
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            $cropBtn.on('click', function () {
                $modal.modal('hide');

                if (cropper) {
                    var canvas = cropper.getCroppedCanvas({
                        width: 600,
                        height: 600,
                    });

                    canvas.toBlob(function (blob) {
                        var formData = new FormData();
                        formData.append('avatar', blob, 'avatar.jpg');

                        $.ajax({
                            url: '{{ route("user.setting.updateAvatar") }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data.success) {
                                    tNotify('success', 'Profile picture updated successfully');
                                    location.reload();
                                } else {
                                    tNotify('error', 'Upload failed');
                                }
                            },
                            error: function () {
                                tNotify('error', 'Something went wrong');
                            }
                        });
                    }, 'image/jpeg');
                }
            });
        });

    </script>
@endsection
