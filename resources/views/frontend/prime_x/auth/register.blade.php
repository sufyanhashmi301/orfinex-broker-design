@extends('frontend::layouts.auth')

@section('title')
    {{ __('Register') }}
@endsection
@section('content')
    @php
        // Manage the invite cookie
        $invite = request()->query('invite'); // Get the invite from the query string

        if ($invite) {
            // Store the invite code in the cookie for 60 minutes
            \Cookie::queue('invite', $invite, 60);
        } elseif (\Cookie::has('invite')) {
            // Remove the cookie if the invite is not present in the URL
            \Cookie::queue(\Cookie::forget('invite'));
        }

        // Retrieve the invite from the cookie for use in the form
        $inviteCode = $invite ?? \Cookie::get('invite');
    @endphp

    <div class="max-h-[calc(100vh-150px)] overflow-y-auto shadow-xl rounded-xl border p-8">
        <h2 class="text-2xl text-center font-semibold text-gray-700 mb-5">
            {{ __('Create Your Account') }}
        </h2>
        <div class="">
            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show mt-2 text-sm" role="alert">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <p class="flex-1 font-Inter">
                            @foreach($errors->all() as $error)
                                {{$error}}
                            @endforeach
                        </p>
                        <div class="flex-0 text-lg cursor-pointer">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}">
                                <iconify-icon icon="line-md:close"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            <!-- BEGIN: Login Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="schema" value="{{ request('schema') ?? old('schema') }}" >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="fromGroup">
                        <label class="block capitalize form-label">
                            {{ __('First Name*') }}
                        </label>
                        <div class="relative ">
                            <input type="text" class="form-control py-2 h-[48px]" placeholder="{{ __('Your First Name') }}"
                                   name="first_name"
                                   value="{{ old('first_name') }}"
                                   required>
                        </div>
                    </div>
                    <div class="fromGroup">
                        <label class="block capitalize form-label">{{ __('Last Name*') }}</label>
                        <div class="relative ">
                            <input type="text" class="form-control py-2 h-[48px]" placeholder="{{ __('Your Last Name') }}"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   required>
                        </div>
                    </div>
                </div>
                <div class="fromGroup">
                    <label class="block capitalize form-label">{{ __('Email Address*') }}</label>
                    <div class="relative ">
                        <input type="email" class="form-control py-2 h-[48px]"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="{{ __('Enter Your Email Address') }}"
                               required>
                    </div>
                </div>
                @if(getPageSetting('username_show'))
                    <div class="fromGroup">
                        <label class="block capitalize form-label">{{ __('User Name*') }}</label>
                        <div class="relative ">
                            <input
                                class="form-control py-2 h-[48px]"
                                type="text"
                                placeholder="{{ __('Enter Your User Name') }}"
                                name="username"
                                value="{{ old('username') }}"
                                required
                            />
                        </div>
                    </div>
                @endif

                @if(getPageSetting('country_show'))
                    <div class="formGroup">
                        <label class="block capitalize form-label">{{ __('Select Country*') }}</label>
                        <div class="relative">
                            <select name="country" id="countrySelect" class="select2 form-control py-2 h-[48px] w-full mt-2">
                                @foreach( getCountries() as $country)
                                    <option @if( $location->country_code == $country['code']) selected
                                            @endif value="{{ $country['name'].':'.$country['dial_code'] }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        {{ $country['name']  }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if(getPageSetting('phone_show'))
                    <div class="formGroup phone-input-wrapper">
                        <label class="block capitalize form-label">{{ __('Phone Number*') }}</label>
                        <div class="relative">
                            <input type="text"
                                   class="form-control py-2 h-[48px]"
                                   placeholder="{{ __('Phone Number') }}"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone') }}"
                                   aria-label="{{ __('Phone Number') }}"
                                   aria-describedby="basic-addon1"
                            >
                        </div>
                    </div>
                @endif

                @if(getPageSetting('referral_code_show'))
                    <div class="formGroup">
                        <div class="flex items-center justify-between">
                            <label class="block capitalize form-label">{{ __('Referral Code') }}</label>
                            <a href="javascript:;" class="btn-link referralToggle">
                                {{ ($inviteCode || old('invite')) ? 'Hide' : 'Show' }}
                            </a>
                        </div>
                        <div class="relative {{ ($inviteCode || old('invite')) ? '' : 'hidden' }}" id="referral-input">
                            <input
                                class="form-control py-2 h-[48px]"
                                type="text"
                                placeholder="{{ __('Enter Your Referral Code') }}"
                                name="invite"
                                value="{{ old('invite') ?? $inviteCode }}"
                            />
                        </div>
                    </div>
                @endif

                @php
                    $companyFormEnabled = (bool) setting('company_form_status', 'company_register');
                    $companyFieldsJson = setting('company_form_fields', 'company_register');
                    $companyFields = [];
                    if ($companyFormEnabled && is_string($companyFieldsJson) && !empty($companyFieldsJson)) {
                        $decoded = json_decode($companyFieldsJson, true);
                        $companyFields = is_array($decoded) ? $decoded : [];
                    }
                @endphp
                @if($companyFormEnabled)
                    <div class="formGroup">
                        <label class="block capitalize form-label">{{ __('Register as') }}</label>
                        <div class="flex items-center gap-6 mt-2">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="registration_type" value="individual" class="form-radio" checked>
                                <span>{{ __('Individual') }}</span>
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="registration_type" value="company" class="form-radio">
                                <span>{{ __('Company') }}</span>
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ __('Choose Company if you want to register a business account. Additional details will be required.') }}</p>
                    </div>
                    <div id="company-form-fields" class="space-y-4 hidden">
                        @foreach($companyFields as $field)
                            @php
                                $fname = $field['name'] ?? '';
                                $ftype = $field['type'] ?? 'text';
                                $fvalidation = $field['validation'] ?? 'nullable';
                                $foptions = $field['options'] ?? [];
                                $isRequired = $fvalidation === 'required';
                                $safeName = \Illuminate\Support\Str::slug($fname, '_');
                            @endphp
                            <div class="formGroup">
                                <label class="block capitalize form-label">{{ $fname }} @if($isRequired)<span class="text-danger-500">*</span>@endif</label>
                                <div class="relative mt-1">
                                    @if($ftype === 'text' || $ftype === 'date')
                                        <input type="{{ $ftype === 'date' ? 'date' : 'text' }}" class="form-control py-2 h-[48px] company-field" data-required="{{ $isRequired ? '1' : '0' }}" name="company_form[{{ $safeName }}]" disabled>
                                    @elseif($ftype === 'dropdown')
                                        <select class="form-control py-2 h-[48px] company-field" data-required="{{ $isRequired ? '1' : '0' }}" name="company_form[{{ $safeName }}]" disabled>
                                            @foreach($foptions as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($ftype === 'radio')
                                        <div class="flex flex-wrap gap-4">
                                            @foreach($foptions as $opt)
                                                <label class="inline-flex items-center gap-2">
                                                    <input type="radio" class="company-field-radio" data-required="{{ $isRequired ? '1' : '0' }}" name="company_form[{{ $safeName }}]" value="{{ $opt }}" disabled>
                                                    <span>{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif($ftype === 'checkbox')
                                        <div class="flex flex-wrap gap-4">
                                            @foreach($foptions as $opt)
                                                <label class="inline-flex items-center gap-2">
                                                    <input type="checkbox" class="company-field-checkbox" data-required="{{ $isRequired ? '1' : '0' }}" name="company_form[{{ $safeName }}][]" value="{{ $opt }}" disabled>
                                                    <span>{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif($ftype === 'file')
                                        <input type="file" class="form-control py-2 h-[48px] company-field-file" data-required="{{ $isRequired ? '1' : '0' }}" name="company_form_files[{{ $safeName }}]" disabled>
                                    @else
                                        <input type="text" class="form-control py-2 h-[48px] company-field" data-required="{{ $isRequired ? '1' : '0' }}" name="company_form[{{ $safeName }}]" disabled>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="formGroup">
                        <label class="block capitalize form-label">{{ __('Password*') }}</label
                        >
                        <div class="relative">
                            <input
                                class="form-control py-2 h-[48px]"
                                type="password"
                                id="password"
                                name="password"
                                placeholder="{{ __('Enter your password') }}"
                                required
                            />
                            <button type="button" class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center" data-toggle="#password">
                                <iconify-icon class="text-lg" icon="heroicons:eye-slash"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="formGroup">
                        <label class="block capitalize form-label" for="password">{{ __('Confirm Password*') }}</label>
                        <div class="relative">
                            <input
                                class="form-control py-2 h-[48px]"
                                type="password"
                                id="confirm-pass"
                                name="password_confirmation"
                                placeholder="{{ __('Enter your password') }}"
                                required
                            />
                            <button type="button" class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center" data-toggle="#confirm-pass">
                                <iconify-icon class="text-lg" icon="heroicons:eye-slash"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="formGroup">
                    @if($cloudflareTurnstile)
                        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
                        <div class="cf-turnstile" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
                    @else
                        @if($googleReCaptcha)
                            <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                                 data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                            </div>
                        @endif
                    @endif
                </div>
                <div class="flex justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="i_agree" class="hiddens mr-2" required>
                        <span class="text-slate-500 dark:text-slate-400 text-xs leading-6 capitalize">
                            {{ __('I agree with') }}

                            @php
                                $privacyPolicyShow = getPageSetting('privacy_policy_show');
                                $clientAgreementShow = getPageSetting('client_agreement_show');
                                $privacyPolicyLink = document_link_by_slug('privacy_policy');
                                $clientAgreementLink = document_link_by_slug('client_agreement');
                                $privacyPolicyTitle = trim(getPageSetting('privacy_policy_title')) ?: __('Privacy & Policy');
                                $clientAgreementTitle = trim(getPageSetting('client_agreement_title')) ?: __('Client Agreement');
                            @endphp

                            @if($privacyPolicyShow)
                                <a href="{{ $privacyPolicyLink ? $privacyPolicyLink->link : '#' }}" class="btn-link" target="_blank">
                                    {{ $privacyPolicyTitle }}
                                </a>
                            @endif

                            @if($privacyPolicyShow && $clientAgreementShow)
                                {{ __(' and ') }}
                            @endif

                            @if($clientAgreementShow)
                                <a href="{{ $clientAgreementLink ? $clientAgreementLink->link : '#' }}" class="btn-link" target="_blank">
                                    {{ $clientAgreementTitle }}
                                </a>
                            @endif
                        </span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary block w-full text-center">
                    {{ __('Create Account') }}
                </button>
            </form>
            <!-- END: Login Form -->
            @php
                $socialLogins = App\Models\Social::activePlatforms();
            @endphp
            @if($socialLogins->isNotEmpty())
                <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                    <div class="absolute inline-block bg-body dark:bg-body dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
                        {{ __('Or continue with') }}
                    </div>
                </div>
                <div class="max-w-[242px] mx-auto mt-8 w-full">
                    <!-- BEGIN: Social Log in Area -->
                    <ul class="flex justify-center gap-2">
                        @foreach ($socialLogins as $socialLogin)
                            <li>
                                <a href="{{ route('social.redirect', $socialLogin->driver) }}" class="inline-flex h-10 w-10 flex-col items-center justify-center">
                                    <img src="https://cdn.brokeret.com/crm-assets/admin/social/{{ strtolower($socialLogin->title) }}.webp" class="w-full" alt="{{ ucfirst($socialLogin->title) }}">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- END: Social Log In Area -->
                </div>
            @endif
            <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 mt-12 uppercase text-sm text-center">
                {{ __("Already have an account? ") }}
                <a href="{{ route('login') }}" class="text-slate-900 dark:text-white font-medium uppercase hover:underline">
                    {{ __('Login now.') }}
                </a>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    @if(getPageSetting('phone_show'))
        <script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>
        <script>
            const input = document.querySelector("#phone");
            window.intlTelInput(input, {
                initialCountry: "auto",
                showSelectedDialCode: true,
                utilsScript: "{{ asset('frontend/js/utils.js') }}",
            });
        </script>
    @endif

    <script>

        $('#countrySelect').on('change', function (e) {
            "use strict";
            e.preventDefault();
            var country = $(this).val();
            $('#dial-code').html(country.split(":")[1])
        });

        $(document).ready(function() {
            $('.referralToggle').on('click', function (){
                $('#referral-input').toggleClass('hidden');

                if ($('#referral-input').hasClass('hidden')) {
                    $(this).text('Show');
                } else {
                    $(this).text('Hide');
                }
            })

            function setCompanyFieldsEnabled(enabled){
                const $container = $('#company-form-fields');
                if (enabled) {
                    $container.removeClass('hidden');
                    // enable all
                    $container.find('input, select, textarea').prop('disabled', false);
                    // add required based on data-required
                    $container.find('.company-field,[type=file]').each(function(){
                        const req = $(this).data('required') === 1 || $(this).data('required') === '1';
                        $(this).prop('required', !!req);
                    });
                    // radio groups: set required on first radio if needed
                    $container.find('.company-field-radio').each(function(){
                        const name = $(this).attr('name');
                        const group = $container.find('input.company-field-radio[name="'+name.replace(/([\\\[\]\.\*\+\?\^\$\(\)\|\{\}])/g,'\\$1')+'"]');
                        const req = $(this).data('required') === 1 || $(this).data('required') === '1';
                        if (group.length) {
                            group.prop('required', false);
                            if (req) group.first().prop('required', true);
                        }
                    });
                    // checkbox groups: HTML requires at least one, add required to first if needed
                    $container.find('.company-field-checkbox').each(function(){
                        const name = $(this).attr('name');
                        const group = $container.find('input.company-field-checkbox[name="'+name.replace(/([\\\[\]\.\*\+\?\^\$\(\)\|\{\}])/g,'\\$1')+'"]');
                        const req = $(this).data('required') === 1 || $(this).data('required') === '1';
                        group.prop('required', false);
                        if (req && group.length) group.first().prop('required', true);
                    });
                } else {
                    // hide and disable to skip validation
                    $container.addClass('hidden');
                    $container.find('input, select, textarea').each(function(){
                        $(this).prop('required', false).prop('disabled', true);
                    });
                }
            }

            // default state
            setCompanyFieldsEnabled($('input[name="registration_type"]:checked').val() === 'company');

            // Toggle on change
            $('input[name="registration_type"]').on('change', function() {
                setCompanyFieldsEnabled($(this).val() === 'company');
            });
        });

    </script>
@endsection

